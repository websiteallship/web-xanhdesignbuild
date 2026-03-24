<?php
/**
 * Admin Settings Page — Settings → Float Contact.
 *
 * Uses WordPress Settings API for secure option management.
 *
 * @package XanhFloatContact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XFC_Settings {

	/** Option group name. */
	const OPTION_GROUP = 'xfc_settings_group';

	/** Settings page slug. */
	const PAGE_SLUG = 'xfc-settings';

	/**
	 * Constructor — register hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Add settings page under Settings menu.
	 */
	public function add_menu_page() {
		add_options_page(
			__( 'Float Contact', 'xanh-float-contact' ),
			__( 'Float Contact', 'xanh-float-contact' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Enqueue color picker on settings page.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script( 'wp-color-picker', "
			jQuery(document).ready(function($){
				$('.xfc-color-picker').wpColorPicker();
			});
		" );
	}

	/**
	 * Register all settings and fields.
	 */
	public function register_settings() {
		// ── Section: General ──
		add_settings_section(
			'xfc_section_general',
			__( 'Cài Đặt Chung', 'xanh-float-contact' ),
			'__return_false',
			self::PAGE_SLUG
		);

		$this->add_field( 'xfc_enabled', __( 'Bật/Tắt Widget', 'xanh-float-contact' ), 'render_checkbox', 'xfc_section_general' );
		$this->add_field( 'xfc_position', __( 'Vị Trí', 'xanh-float-contact' ), 'render_position_select', 'xfc_section_general' );
		$this->add_field( 'xfc_color', __( 'Màu Nút Chính', 'xanh-float-contact' ), 'render_color_picker', 'xfc_section_general' );

		// ── Section: Channels ──
		add_settings_section(
			'xfc_section_channels',
			__( 'Kênh Liên Hệ', 'xanh-float-contact' ),
			function () {
				echo '<p class="description">' . esc_html__( 'Để trống kênh nào thì kênh đó sẽ không hiển thị.', 'xanh-float-contact' ) . '</p>';
			},
			self::PAGE_SLUG
		);

		$this->add_field( 'xfc_phone', __( 'Số Hotline', 'xanh-float-contact' ), 'render_text', 'xfc_section_channels', [ 'placeholder' => '0903 123 456' ] );
		$this->add_field( 'xfc_phone_label', __( 'Label Hotline', 'xanh-float-contact' ), 'render_text', 'xfc_section_channels', [ 'placeholder' => 'Gọi ngay' ] );
		$this->add_field( 'xfc_zalo', __( 'Link Zalo', 'xanh-float-contact' ), 'render_url', 'xfc_section_channels', [ 'placeholder' => 'https://zalo.me/0903123456' ] );
		$this->add_field( 'xfc_zalo_label', __( 'Label Zalo', 'xanh-float-contact' ), 'render_text', 'xfc_section_channels', [ 'placeholder' => 'Chat Zalo' ] );
		$this->add_field( 'xfc_messenger', __( 'Link Messenger', 'xanh-float-contact' ), 'render_url', 'xfc_section_channels', [ 'placeholder' => 'https://m.me/yourpage' ] );
		$this->add_field( 'xfc_messenger_label', __( 'Label Messenger', 'xanh-float-contact' ), 'render_text', 'xfc_section_channels', [ 'placeholder' => 'Messenger' ] );
		$this->add_field( 'xfc_email', __( 'Email', 'xanh-float-contact' ), 'render_email', 'xfc_section_channels', [ 'placeholder' => 'info@xanhdesignbuild.vn' ] );
		$this->add_field( 'xfc_email_label', __( 'Label Email', 'xanh-float-contact' ), 'render_text', 'xfc_section_channels', [ 'placeholder' => 'Gửi Email' ] );

		// ── Register each option ──
		$options = [
			'xfc_enabled'         => 'sanitize_text_field',
			'xfc_phone'           => 'sanitize_text_field',
			'xfc_phone_label'     => 'sanitize_text_field',
			'xfc_zalo'            => 'esc_url_raw',
			'xfc_zalo_label'      => 'sanitize_text_field',
			'xfc_messenger'       => 'esc_url_raw',
			'xfc_messenger_label' => 'sanitize_text_field',
			'xfc_email'           => 'sanitize_email',
			'xfc_email_label'     => 'sanitize_text_field',
			'xfc_position'        => [ $this, 'sanitize_position' ],
			'xfc_color'           => 'sanitize_hex_color',
		];

		foreach ( $options as $name => $sanitize ) {
			register_setting( self::OPTION_GROUP, $name, [
				'sanitize_callback' => $sanitize,
			] );
		}
	}

	/* ── Field renderers ── */

	public function render_checkbox( $args ) {
		$value = get_option( $args['label_for'], '1' );
		printf(
			'<label><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s /> %3$s</label>',
			esc_attr( $args['label_for'] ),
			checked( $value, '1', false ),
			esc_html__( 'Hiển thị widget trên frontend', 'xanh-float-contact' )
		);
	}

	public function render_text( $args ) {
		$value = get_option( $args['label_for'], '' );
		printf(
			'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="regular-text" placeholder="%3$s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $value ),
			esc_attr( $args['placeholder'] ?? '' )
		);
	}

	public function render_url( $args ) {
		$value = get_option( $args['label_for'], '' );
		printf(
			'<input type="url" id="%1$s" name="%1$s" value="%2$s" class="regular-text" placeholder="%3$s" />',
			esc_attr( $args['label_for'] ),
			esc_url( $value ),
			esc_attr( $args['placeholder'] ?? '' )
		);
	}

	public function render_email( $args ) {
		$value = get_option( $args['label_for'], '' );
		printf(
			'<input type="email" id="%1$s" name="%1$s" value="%2$s" class="regular-text" placeholder="%3$s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $value ),
			esc_attr( $args['placeholder'] ?? '' )
		);
	}

	public function render_color_picker( $args ) {
		$value = get_option( $args['label_for'], '#FF8A00' );
		printf(
			'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="xfc-color-picker" data-default-color="#FF8A00" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $value )
		);
	}

	public function render_position_select( $args ) {
		$value   = get_option( $args['label_for'], 'bottom-right' );
		$options = [
			'bottom-right' => __( 'Góc phải dưới', 'xanh-float-contact' ),
			'bottom-left'  => __( 'Góc trái dưới', 'xanh-float-contact' ),
		];
		echo '<select id="' . esc_attr( $args['label_for'] ) . '" name="' . esc_attr( $args['label_for'] ) . '">';
		foreach ( $options as $key => $label ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $key ),
				selected( $value, $key, false ),
				esc_html( $label )
			);
		}
		echo '</select>';
	}

	/**
	 * Sanitize position value.
	 *
	 * @param  string $value Raw value.
	 * @return string Sanitized value.
	 */
	public function sanitize_position( $value ) {
		$allowed = [ 'bottom-right', 'bottom-left' ];
		return in_array( $value, $allowed, true ) ? $value : 'bottom-right';
	}

	/* ── Helpers ── */

	/**
	 * Shorthand for add_settings_field.
	 */
	private function add_field( $id, $title, $callback, $section, $extra = [] ) {
		add_settings_field(
			$id,
			$title,
			[ $this, $callback ],
			self::PAGE_SLUG,
			$section,
			array_merge( [ 'label_for' => $id ], $extra )
		);
	}

	/**
	 * Render the settings page HTML.
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button( __( 'Lưu Cài Đặt', 'xanh-float-contact' ) );
				?>
			</form>
		</div>
		<?php
	}
}
