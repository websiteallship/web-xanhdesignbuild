<?php
/**
 * Admin pages — menu registration, asset enqueue, view routing.
 *
 * @package Xanh_AI_Content
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xanh_AI_Admin {

	/**
	 * Initialize admin hooks.
	 */
	public function init(): void {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/*--------------------------------------------------------------
	 * Menu Registration
	 *------------------------------------------------------------*/

	/**
	 * Register admin menu and submenus.
	 */
	public function register_menu(): void {
		// Top-level menu.
		add_menu_page(
			__( 'XANH AI Content', 'xanh-ai-content' ),
			__( 'XANH AI', 'xanh-ai-content' ),
			'edit_posts',
			'xanh-ai',
			[ $this, 'render_generator_page' ],
			'dashicons-edit-page',
			30
		);

		// Submenu: Generator (same as parent).
		add_submenu_page(
			'xanh-ai',
			__( 'Tạo Bài Viết', 'xanh-ai-content' ),
			__( 'Tạo Bài Viết', 'xanh-ai-content' ),
			'edit_posts',
			'xanh-ai',
			[ $this, 'render_generator_page' ]
		);

		// Submenu: Settings (admin only).
		add_submenu_page(
			'xanh-ai',
			__( 'Cài Đặt', 'xanh-ai-content' ),
			__( 'Cài Đặt', 'xanh-ai-content' ),
			'manage_options',
			'xanh-ai-settings',
			[ $this, 'render_settings_page' ]
		);
	}

	/*--------------------------------------------------------------
	 * Asset Enqueue
	 *------------------------------------------------------------*/

	/**
	 * Enqueue admin CSS and JS only on our plugin pages.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		// Only load on our plugin pages.
		if ( strpos( $hook_suffix, 'xanh-ai' ) === false ) {
			return;
		}

		// CSS.
		wp_enqueue_style(
			'xanh-ai-admin',
			XANH_AI_URL . 'admin/css/xanh-ai-admin.css',
			[],
			XANH_AI_VERSION
		);

		// No custom fonts; using WP dashicons.

		// JS — admin common (settings page).
		wp_enqueue_script(
			'xanh-ai-admin',
			XANH_AI_URL . 'admin/js/xanh-ai-admin.js',
			[ 'jquery' ],
			XANH_AI_VERSION,
			true
		);

		// Shared localization.
		wp_localize_script( 'xanh-ai-admin', 'xanhAI', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'xanh_ai_ajax' ),
			'i18n'    => [
				'testing'       => __( 'Đang kiểm tra...', 'xanh-ai-content' ),
				'testSuccess'   => __( 'Kết nối thành công!', 'xanh-ai-content' ),
				'testFailed'    => __( 'Kết nối thất bại.', 'xanh-ai-content' ),
				'saving'        => __( 'Đang lưu...', 'xanh-ai-content' ),
				'saved'         => __( 'Đã lưu!', 'xanh-ai-content' ),
				'confirmDelete' => __( 'Bạn có chắc muốn xóa?', 'xanh-ai-content' ),
			],
		] );

		// Generator page — extra JS + data.
		if ( strpos( $hook_suffix, 'xanh-ai' ) !== false && strpos( $hook_suffix, 'settings' ) === false ) {
			wp_enqueue_script(
				'xanh-ai-generator',
				XANH_AI_URL . 'admin/js/xanh-ai-generator.js',
				[ 'jquery', 'xanh-ai-admin' ],
				XANH_AI_VERSION,
				true
			);

			wp_localize_script( 'xanh-ai-generator', 'xanhAIGen', [
				'angles' => Xanh_AI_Angles::get_all(),
				'i18n'   => [
					'generating'     => __( 'Đang tạo nội dung...', 'xanh-ai-content' ),
					'analyzing'      => __( 'Đang phân tích chủ đề...', 'xanh-ai-content' ),
					'writing'        => __( 'Đang viết bài...', 'xanh-ai-content' ),
					'optimizing'     => __( 'Đang tối ưu SEO...', 'xanh-ai-content' ),
					'generated'      => __( 'Đã tạo xong!', 'xanh-ai-content' ),
					'generateFailed' => __( 'Tạo nội dung thất bại.', 'xanh-ai-content' ),
					'saving'         => __( 'Đang lưu draft...', 'xanh-ai-content' ),
					'saved'          => __( 'Đã lưu draft thành công!', 'xanh-ai-content' ),
					'saveFailed'     => __( 'Lưu draft thất bại.', 'xanh-ai-content' ),
					'regenerating'   => __( 'Đang viết lại section...', 'xanh-ai-content' ),
					'regenerated'    => __( 'Đã viết lại xong!', 'xanh-ai-content' ),
					'genImage'       => __( 'Đang tạo hình ảnh...', 'xanh-ai-content' ),
					'genImageDone'   => __( 'Đã tạo hình ảnh!', 'xanh-ai-content' ),
				],
			] );
		}
	}

	/*--------------------------------------------------------------
	 * Page Renderers
	 *------------------------------------------------------------*/

	/**
	 * Render the Generator page.
	 */
	public function render_generator_page(): void {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Bạn không có quyền truy cập trang này.', 'xanh-ai-content' ) );
		}

		include XANH_AI_DIR . 'admin/views/generator-page.php';
	}

	/**
	 * Render the Settings page.
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Bạn không có quyền truy cập trang này.', 'xanh-ai-content' ) );
		}

		include XANH_AI_DIR . 'admin/views/settings-page.php';
	}

	/*--------------------------------------------------------------
	 * Helper: Check if we're on a plugin page
	 *------------------------------------------------------------*/

	/**
	 * Check if current screen is a XANH AI page.
	 *
	 * @return bool
	 */
	public static function is_plugin_page(): bool {
		$screen = get_current_screen();
		return $screen && strpos( $screen->id, 'xanh-ai' ) !== false;
	}
}
