<?php
/**
 * Frontend — Render float contact widget + enqueue assets.
 *
 * @package XanhFloatContact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class XFC_Frontend {

	/**
	 * Constructor — register hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'wp_footer', [ $this, 'render_widget' ], 99 );
	}

	/**
	 * Enqueue CSS and JS on frontend.
	 */
	public function enqueue_assets() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		wp_enqueue_style(
			'xfc-float-contact',
			XFC_PLUGIN_URL . 'assets/css/float-contact.css',
			[],
			XFC_VERSION
		);

		wp_enqueue_script(
			'xfc-float-contact',
			XFC_PLUGIN_URL . 'assets/js/float-contact.js',
			[],
			XFC_VERSION,
			[ 'in_footer' => true, 'strategy' => 'defer' ]
		);

		// Pass dynamic color as CSS custom property.
		$color = get_option( 'xfc_color', '#FF8A00' );
		wp_add_inline_style( 'xfc-float-contact', sprintf(
			':root { --xfc-brand-color: %s; }',
			sanitize_hex_color( $color ) ?: '#FF8A00'
		) );
	}

	/**
	 * Render the FAB widget HTML in wp_footer.
	 */
	public function render_widget() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$phone     = get_option( 'xfc_phone', '' );
		$zalo      = get_option( 'xfc_zalo', '' );
		$messenger = get_option( 'xfc_messenger', '' );
		$email     = get_option( 'xfc_email', '' );
		$position  = get_option( 'xfc_position', 'bottom-right' );

		$phone_label     = get_option( 'xfc_phone_label', 'Gọi ngay' );
		$zalo_label      = get_option( 'xfc_zalo_label', 'Chat Zalo' );
		$messenger_label = get_option( 'xfc_messenger_label', 'Messenger' );
		$email_label     = get_option( 'xfc_email_label', 'Gửi Email' );

		// At least one channel must exist.
		if ( ! $phone && ! $zalo && ! $messenger && ! $email ) {
			return;
		}

		$position_class = 'bottom-left' === $position ? 'xfc--left' : 'xfc--right';
		?>
		<!-- XANH Float Contact v<?php echo esc_attr( XFC_VERSION ); ?> -->
		<div id="xfc-float-contact" class="xfc-float <?php echo esc_attr( $position_class ); ?>" role="complementary" aria-label="<?php esc_attr_e( 'Liên hệ nhanh', 'xanh-float-contact' ); ?>">

			<!-- Channel items (hidden by default) -->
			<div class="xfc-float__channels" aria-hidden="true">

				<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"
				   class="xfc-float__item xfc-float__item--email"
				   aria-label="<?php echo esc_attr( $email_label ); ?>"
				   data-tooltip="<?php echo esc_attr( $email_label ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect width="20" height="16" x="2" y="4" rx="2" />
						<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
					</svg>
				</a>
				<?php endif; ?>

				<?php if ( $messenger ) : ?>
				<a href="<?php echo esc_url( $messenger ); ?>"
				   class="xfc-float__item xfc-float__item--messenger"
				   target="_blank" rel="noopener noreferrer"
				   aria-label="<?php echo esc_attr( $messenger_label ); ?>"
				   data-tooltip="<?php echo esc_attr( $messenger_label ); ?>">
					<svg viewBox="0 0 24 24" fill="currentColor">
						<path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.91 1.2 5.42 3.15 7.2.17.15.27.37.28.6l.06 1.87c.02.63.67 1.04 1.24.78l2.09-.92c.18-.08.38-.1.56-.06.88.24 1.82.37 2.62.37 5.64 0 10-4.13 10-9.7S17.64 2 12 2zm5.89 7.58-2.89 4.58c-.45.72-1.41.9-2.09.39l-2.3-1.72a.6.6 0 0 0-.72 0l-3.1 2.35c-.41.31-.95-.17-.68-.62l2.89-4.58c.45-.72 1.41-.9 2.09-.39l2.3 1.72a.6.6 0 0 0 .72 0l3.1-2.35c.41-.31.95.17.68.62z"/>
					</svg>
				</a>
				<?php endif; ?>

				<?php if ( $zalo ) : ?>
				<a href="<?php echo esc_url( $zalo ); ?>"
				   class="xfc-float__item xfc-float__item--zalo"
				   target="_blank" rel="noopener noreferrer"
				   aria-label="<?php echo esc_attr( $zalo_label ); ?>"
				   data-tooltip="<?php echo esc_attr( $zalo_label ); ?>">
					<svg viewBox="0 0 614.501 613.667" fill="currentColor">
						<path d="M464.721,301.399c-13.984-0.014-23.707,11.478-23.944,28.312c-0.251,17.771,9.168,29.208,24.037,29.202   c14.287-0.007,23.799-11.095,24.01-27.995C489.028,313.536,479.127,301.399,464.721,301.399z" />
						<path d="M291.83,301.392c-14.473-0.316-24.578,11.603-24.604,29.024c-0.02,16.959,9.294,28.259,23.496,28.502   c15.072,0.251,24.592-10.87,24.539-28.707C315.214,313.318,305.769,301.696,291.83,301.392z" />
						<path d="M310.518,3.158C143.102,3.158,7.375,138.884,7.375,306.3s135.727,303.142,303.143,303.142   c167.415,0,303.143-135.727,303.143-303.142S477.933,3.158,310.518,3.158z M217.858,391.083   c-33.364,0.818-66.828,1.353-100.133-0.343c-21.326-1.095-27.652-18.647-14.248-36.583c21.55-28.826,43.886-57.065,65.792-85.621   c2.546-3.305,6.214-5.996,7.15-12.705c-16.609,0-32.784,0.04-48.958-0.013c-19.195-0.066-28.278-5.805-28.14-17.652   c0.132-11.768,9.175-17.329,28.397-17.348c25.159-0.026,50.324-0.06,75.476,0.026c9.637,0.033,19.604,0.105,25.304,9.789   c6.22,10.561,0.284,19.512-5.646,27.454c-21.26,28.497-43.015,56.624-64.559,84.902c-2.599,3.41-5.119,6.88-9.453,12.725   c23.424,0,44.123-0.053,64.816,0.026c8.674,0.026,16.662,1.873,19.941,11.267C237.892,379.329,231.368,390.752,217.858,391.083z    M350.854,330.211c0,13.417-0.093,26.841,0.039,40.265c0.073,7.599-2.599,13.647-9.512,17.084   c-7.296,3.642-14.71,3.028-20.304-2.968c-3.997-4.281-6.214-3.213-10.488-0.422c-17.955,11.728-39.908,9.96-56.597-3.866   c-29.928-24.789-30.026-74.803-0.211-99.776c16.194-13.562,39.592-15.462,56.709-4.143c3.951,2.619,6.201,4.815,10.396-0.053   c5.39-6.267,13.055-6.761,20.271-3.357c7.454,3.509,9.935,10.165,9.776,18.265C350.67,304.222,350.86,317.217,350.854,330.211z    M395.617,369.579c-0.118,12.837-6.398,19.783-17.196,19.908c-10.779,0.132-17.593-6.966-17.646-19.512   c-0.179-43.352-0.185-86.696,0.007-130.041c0.059-12.256,7.302-19.921,17.896-19.222c11.425,0.752,16.992,7.448,16.992,18.833   c0,22.104,0,44.216,0,66.327C395.677,327.105,395.828,348.345,395.617,369.579z M463.981,391.868   c-34.399-0.336-59.037-26.444-58.786-62.289c0.251-35.66,25.304-60.713,60.383-60.396c34.631,0.304,59.374,26.306,58.998,61.986   C524.207,366.492,498.534,392.205,463.981,391.868z" />
					</svg>
				</a>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"
				   class="xfc-float__item xfc-float__item--phone"
				   aria-label="<?php echo esc_attr( $phone_label ); ?>"
				   data-tooltip="<?php echo esc_attr( $phone_label ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.73a16 16 0 0 0 6 6l.52-.93a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z"/>
					</svg>
				</a>
				<?php endif; ?>

			</div>

			<!-- FAB trigger button -->
			<button class="xfc-float__trigger" aria-expanded="false" aria-label="<?php esc_attr_e( 'Mở menu liên hệ', 'xanh-float-contact' ); ?>" type="button">
				<span class="xfc-float__trigger-icon xfc-float__trigger-icon--chat">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
					</svg>
				</span>
				<span class="xfc-float__trigger-icon xfc-float__trigger-icon--close">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="6" x2="6" y2="18"/>
						<line x1="6" y1="6" x2="18" y2="18"/>
					</svg>
				</span>
			</button>

		</div>
		<?php
	}

	/**
	 * Check whether the widget is enabled.
	 *
	 * @return bool
	 */
	private function is_enabled() {
		return '1' === get_option( 'xfc_enabled', '1' );
	}
}
