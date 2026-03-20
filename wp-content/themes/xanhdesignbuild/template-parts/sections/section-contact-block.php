<?php
/**
 * Template Part: Contact Block — Form + Info + Map.
 *
 * Two-column layout: left = contact form (Fluent Form shortcode),
 * right = contact info + Google Maps embed.
 *
 * ACF fields: contact_block_eyebrow, contact_block_title,
 *             contact_form_shortcode, contact_working_hours,
 *             contact_google_maps_url.
 *
 * ACF Options: xanh_hotline, xanh_email, xanh_address.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ACF data with fallbacks.
$eyebrow       = get_field( 'contact_block_eyebrow' ) ?: 'Liên Hệ Với Chúng Tôi';
$title          = get_field( 'contact_block_title' ) ?: 'Kể Cho Chúng Tôi Nghe<br>Về <em style="font-style:normal;color:var(--color-primary);">Bài Toán Của Bạn</em>';
$form_shortcode = get_field( 'contact_form_shortcode' ) ?: '';
$working_hours  = get_field( 'contact_working_hours' ) ?: 'Thứ 2 — Thứ 7: 08:00 – 17:30';
$maps_url       = get_field( 'contact_google_maps_url' ) ?: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3898.7895!2d109.1896!3d12.2388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTLCsDE0JzE5LjciTiAxMDnCsDExJzIyLjYiRQ!5e0!3m2!1svi!2s!4v1710000000000';

// Global Options.
$address = xanh_get_address() ?: '123 Nguyễn Tất Thành, Phường Phước Hải, TP. Nha Trang, Khánh Hòa';
$hotline = xanh_get_hotline() ?: '0258 388 8888';
$email   = xanh_get_email() ?: 'info@xanhdesign.vn';
?>

<section class="contact-block" id="contact-block">
	<div class="site-container">
		<!-- Section header -->
		<div class="section-header section-header--left" style="margin-bottom:var(--space-12);">
			<span class="section-eyebrow anim-fade-up"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 class="section-title anim-fade-up" style="margin-top:0.5rem;"><?php echo wp_kses_post( $title ); ?></h2>
		</div>

		<div class="contact-block__grid">
			<!-- ── Left Column: Form ── -->
			<div class="contact-form-wrap anim-fade-left" id="contact-form-section">
				<div class="contact-form__header">
					<h3 class="contact-form__title"><?php esc_html_e( 'Yêu Cầu Tư Vấn Miễn Phí', 'xanh' ); ?></h3>
					<p class="contact-form__subtitle"><?php esc_html_e( 'Kỹ sư trưởng sẽ liên hệ lại trong vòng 24 giờ.', 'xanh' ); ?></p>
				</div>

				<!-- Fluent Form -->
				<?php 
					$final_shortcode = $form_shortcode ?: '[fluentform id="4"]';
					echo do_shortcode( wp_kses_post( $final_shortcode ) ); 
				?>
			</div>

			<!-- ── Right Column: Info ── -->
			<div class="contact-info anim-fade-right">
				<!-- Address -->
				<div class="contact-info__item">
					<div class="contact-info__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
					</div>
					<div>
						<span class="contact-info__label"><?php esc_html_e( 'Trụ sở', 'xanh' ); ?></span>
						<p class="contact-info__value"><?php echo wp_kses_post( nl2br( $address ) ); ?></p>
					</div>
				</div>

				<!-- Phone -->
				<div class="contact-info__item">
					<div class="contact-info__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
					</div>
					<div>
						<span class="contact-info__label"><?php esc_html_e( 'Hotline Kỹ Thuật', 'xanh' ); ?></span>
						<p class="contact-info__value">
							<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $hotline ) ); ?>"><?php echo esc_html( $hotline ); ?></a>
						</p>
					</div>
				</div>

				<!-- Email -->
				<div class="contact-info__item">
					<div class="contact-info__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
					</div>
					<div>
						<span class="contact-info__label"><?php esc_html_e( 'Email', 'xanh' ); ?></span>
						<p class="contact-info__value">
							<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</p>
					</div>
				</div>

				<!-- Working hours -->
				<div class="contact-info__item">
					<div class="contact-info__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
					</div>
					<div>
						<span class="contact-info__label"><?php esc_html_e( 'Giờ Làm Việc', 'xanh' ); ?></span>
						<p class="contact-info__value"><?php echo esc_html( $working_hours ); ?></p>
					</div>
				</div>

				<!-- Google Maps -->
				<div class="contact-map">
					<iframe
						src="<?php echo esc_url( $maps_url ); ?>"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
						title="<?php esc_attr_e( 'Bản đồ XANH Design & Build - Nha Trang', 'xanh' ); ?>"
						allowfullscreen>
					</iframe>
				</div>
			</div>
		</div>
	</div>
</section>
