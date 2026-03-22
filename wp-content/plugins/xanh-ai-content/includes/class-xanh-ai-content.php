<?php
/**
 * Main plugin singleton — orchestrates all modules.
 *
 * @package Xanh_AI_Content
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xanh_AI_Content {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Get singleton instance.
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor — use instance().
	 */
	private function __construct() {
		$this->load_textdomain();
		$this->init_hooks();
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton.' );
	}

	/*--------------------------------------------------------------
	 * Text domain
	 *------------------------------------------------------------*/
	private function load_textdomain(): void {
		load_plugin_textdomain(
			'xanh-ai-content',
			false,
			dirname( XANH_AI_BASENAME ) . '/languages'
		);
	}

	/*--------------------------------------------------------------
	 * Hook registration
	 *------------------------------------------------------------*/
	private function init_hooks(): void {
		// Admin-only modules.
		if ( is_admin() ) {
			// Admin UI (menu, assets, AJAX).
			$admin = new Xanh_AI_Admin();
			$admin->init();

			// Settings.
			$settings = new Xanh_AI_Settings();
			$settings->init();
		}

		// AJAX handlers (fire on both admin and front for logged-in users).
		add_action( 'wp_ajax_xanh_ai_test_connection',    [ $this, 'ajax_test_connection' ] );
		add_action( 'wp_ajax_xanh_ai_generate_content',   [ $this, 'ajax_generate_content' ] );
		add_action( 'wp_ajax_xanh_ai_regenerate_section', [ $this, 'ajax_regenerate_section' ] );
		add_action( 'wp_ajax_xanh_ai_save_draft',         [ $this, 'ajax_save_draft' ] );
		add_action( 'wp_ajax_xanh_ai_generate_image',     [ $this, 'ajax_generate_image' ] );
		add_action( 'wp_ajax_xanh_ai_preview_prompt',     [ $this, 'ajax_preview_prompt' ] );
		add_action( 'wp_ajax_xanh_ai_suggest_keywords',   [ $this, 'ajax_suggest_keywords' ] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Suggest keywords via AI
	 *------------------------------------------------------------*/
	public function ajax_suggest_keywords(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'edit_posts' ) ) {
			return;
		}

		$angle_id = sanitize_text_field( wp_unslash( $_POST['angle_id'] ?? '' ) );
		$topic    = sanitize_text_field( wp_unslash( $_POST['topic'] ?? '' ) );
		$keyword  = sanitize_text_field( wp_unslash( $_POST['primary_keyword'] ?? '' ) );

		if ( empty( $topic ) ) {
			wp_send_json_error( [
				'message' => __( 'Vui lòng nhập chủ đề trước khi gợi ý từ khóa.', 'xanh-ai-content' ),
			] );
		}

		// Get angle label for better context.
		$angle       = Xanh_AI_Angles::get( $angle_id );
		$angle_label = $angle['label'] ?? 'Kiến Thức';

		$api    = new Xanh_AI_API();
		$result = $api->suggest_keywords( $topic, $angle_label, $keyword );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [
				'message' => $result->get_error_message(),
			] );
		}

		wp_send_json_success( [
			'keywords' => $result,
			'source'   => 'ai',
		] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Test API connection
	 *------------------------------------------------------------*/
	public function ajax_test_connection(): void {
		// Security checks.
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'manage_options' ) ) {
			return; // validate_ajax_request sends the error response.
		}

		// Accept API key from form field (allows test before saving).
		$raw_key = sanitize_text_field( wp_unslash( $_POST['api_key'] ?? '' ) );

		$api    = new Xanh_AI_API();
		$result = $api->test_connection( $raw_key );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [
				'message' => $result->get_error_message(),
			] );
		}

		// Save the key automatically upon successful test
		if ( ! empty( $raw_key ) ) {
			update_option( 'xanh_ai_gemini_key', Xanh_AI_Security::encrypt_key( $raw_key ) );
		}

		wp_send_json_success( [
			'message' => __( 'Kết nối API thành công!', 'xanh-ai-content' ),
			'model'   => $result['model'] ?? '',
		] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Preview prompt (0 API calls)
	 *------------------------------------------------------------*/
	public function ajax_preview_prompt(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'edit_posts' ) ) {
			return;
		}

		$params = Xanh_AI_Security::sanitize_generator_input( $_POST );

		if ( empty( $params['topic'] ) || empty( $params['keyword'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Vui lòng nhập chủ đề và từ khóa.', 'xanh-ai-content' ),
			] );
		}

		$angle_id      = $params['angle_id'] ?? 'knowledge';
		$system_prompt = Xanh_AI_Prompts::build_system_prompt( $angle_id );
		$user_prompt   = Xanh_AI_Prompts::build_user_prompt( $params );
		$full_prompt   = $system_prompt . "\n\n" . $user_prompt;

		// Rough token estimate: ~1 token per 4 chars for Vietnamese.
		$token_estimate = (int) ceil( mb_strlen( $full_prompt ) / 4 );

		wp_send_json_success( [
			'full_prompt'    => $full_prompt,
			'token_estimate' => $token_estimate,
		] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Generate content
	 *------------------------------------------------------------*/
	public function ajax_generate_content(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'edit_posts' ) ) {
			return;
		}

		if ( ! Xanh_AI_Security::check_rate_limit() ) {
			wp_send_json_error( [
				'message' => __( 'Vui lòng đợi 30 giây trước khi tạo tiếp.', 'xanh-ai-content' ),
				'code'    => 'rate_limited',
			], 429 );
		}

		$params = Xanh_AI_Security::sanitize_generator_input( $_POST );

		if ( empty( $params['topic'] ) || empty( $params['keyword'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Vui lòng nhập chủ đề và từ khóa.', 'xanh-ai-content' ),
			] );
		}

		// Support custom prompt from the prompt preview step.
		$custom_prompt = wp_unslash( $_POST['custom_prompt'] ?? '' );
		if ( ! empty( $custom_prompt ) ) {
			$params['custom_prompt'] = $custom_prompt;
		}

		$generator = new Xanh_AI_Generator();
		$result    = $generator->generate( $params );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [
				'message' => $result->get_error_message(),
			] );
		}

		wp_send_json_success( $result );
	}

	/*--------------------------------------------------------------
	 * AJAX: Regenerate a single section
	 *------------------------------------------------------------*/
	public function ajax_regenerate_section(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'edit_posts' ) ) {
			return;
		}

		$content       = wp_kses_post( wp_unslash( $_POST['content'] ?? '' ) );
		$section_title = sanitize_text_field( wp_unslash( $_POST['section_title'] ?? '' ) );
		$notes         = sanitize_text_field( wp_unslash( $_POST['notes'] ?? '' ) );
		$angle_id      = sanitize_text_field( wp_unslash( $_POST['angle_id'] ?? '' ) );

		if ( empty( $content ) || empty( $section_title ) ) {
			wp_send_json_error( [
				'message' => __( 'Thiếu nội dung hoặc tiêu đề section.', 'xanh-ai-content' ),
			] );
		}

		$generator  = new Xanh_AI_Generator();
		$new_section = $generator->regenerate_section( $content, $section_title, $notes, $angle_id );

		if ( is_wp_error( $new_section ) ) {
			wp_send_json_error( [
				'message' => $new_section->get_error_message(),
			] );
		}

		wp_send_json_success( [
			'section_html' => $new_section,
		] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Save draft
	 *------------------------------------------------------------*/
	public function ajax_save_draft(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'edit_posts' ) ) {
			return;
		}

		// Rebuild data from POST.
		$raw_title = sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) );

		// Auto-append site name suffix if not already present.
		$site_name = get_bloginfo( 'name' );
		if ( ! empty( $site_name ) && mb_stripos( $raw_title, $site_name ) === false ) {
			$raw_title .= ' | ' . $site_name;
		}

		$data = [
			'title'            => $raw_title,
			'slug'             => sanitize_title( wp_unslash( $_POST['slug'] ?? '' ) ),
			'meta_description' => sanitize_text_field( wp_unslash( $_POST['meta_description'] ?? '' ) ),
			'excerpt'          => sanitize_text_field( wp_unslash( $_POST['excerpt'] ?? '' ) ),
			'content_html'     => wp_kses_post( wp_unslash( $_POST['content_html'] ?? '' ) ),
			'tags'             => array_map( 'sanitize_text_field', (array) ( $_POST['tags'] ?? [] ) ),
			'faq'              => json_decode( wp_unslash( $_POST['faq'] ?? '[]' ), true ) ?: [],
			'image_prompt'     => sanitize_text_field( wp_unslash( $_POST['image_prompt'] ?? '' ) ),
			'score'            => json_decode( wp_unslash( $_POST['score'] ?? '{}' ), true ) ?: [],
			'tokens'           => absint( $_POST['tokens'] ?? 0 ),
			'angle'            => Xanh_AI_Angles::get( sanitize_text_field( $_POST['angle_id'] ?? 'knowledge' ) ),
		];

		$params = [
			'angle_id' => sanitize_text_field( $_POST['angle_id'] ?? 'knowledge' ),
			'keyword'  => sanitize_text_field( wp_unslash( $_POST['keyword'] ?? '' ) ),
		];

		$generator = new Xanh_AI_Generator();
		$post_id   = $generator->save_draft( $data, $params );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [
				'message' => $post_id->get_error_message(),
			] );
		}

		// Set featured image if attachment_id was provided from image generation.
		$attachment_id = absint( $_POST['attachment_id'] ?? 0 );
		if ( $attachment_id > 0 && wp_attachment_is_image( $attachment_id ) ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}

		wp_send_json_success( [
			'post_id'  => $post_id,
			'edit_url' => get_edit_post_link( $post_id, 'raw' ),
			'message'  => __( 'Đã lưu bài viết nháp thành công!', 'xanh-ai-content' ),
		] );
	}

	/*--------------------------------------------------------------
	 * AJAX: Generate featured image
	 *------------------------------------------------------------*/
	public function ajax_generate_image(): void {
		if ( ! Xanh_AI_Security::validate_ajax_request( 'xanh_ai_ajax', 'upload_files' ) ) {
			return;
		}

		$image_prompt = sanitize_text_field( wp_unslash( $_POST['image_prompt'] ?? '' ) );

		if ( empty( $image_prompt ) ) {
			wp_send_json_error( [
				'message' => __( 'Thiếu prompt cho hình ảnh.', 'xanh-ai-content' ),
			] );
		}

		$api           = new Xanh_AI_API();
		$attachment_id = $api->generate_image( $image_prompt );

		if ( is_wp_error( $attachment_id ) ) {
			wp_send_json_error( [
				'message' => $attachment_id->get_error_message(),
			] );
		}

		wp_send_json_success( [
			'attachment_id' => $attachment_id,
			'url'           => wp_get_attachment_url( $attachment_id ),
		] );
	}
}
