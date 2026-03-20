<?php
/**
 * Template Part: Section — Services CTA.
 *
 * Full-width CTA with background image overlay,
 * headline, subtitle, and dual action buttons.
 * ACF Options fields: services_cta_*.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$cta_title    = xanh_get_option( 'services_cta_title', 'Bạn Đã Sẵn Sàng Kiến Tạo<br class="hidden sm:block" /> Không Gian Sống Mơ Ước?' );
$cta_subtitle = xanh_get_option( 'services_cta_subtitle', 'Liên hệ ngay để được tư vấn miễn phí — đội ngũ XANH luôn sẵn sàng đồng hành cùng bạn.' );
$cta_bg_image = xanh_get_option_image( 'services_cta_bg_image' );

$btn1_text = xanh_get_option( 'services_cta_btn1_text', 'Nhận Tư Vấn Miễn Phí' );
$btn1_url  = xanh_get_option( 'services_cta_btn1_url', '' );
$btn2_text = xanh_get_option( 'services_cta_btn2_text', 'Xem Dự Án Thực Tế' );
$btn2_url  = xanh_get_option( 'services_cta_btn2_url', '' );

// CTA background image URL.
$cta_img_url = '';
if ( $cta_bg_image ) {
	$cta_img_url = $cta_bg_image['url'];
} else {
	$cta_img_url = site_url( '/wp-content/uploads/2026/03/project-after-1.png' );
}

// Fallback URLs.
if ( empty( $btn1_url ) ) {
	$btn1_url = home_url( '/lien-he/' );
}
if ( empty( $btn2_url ) ) {
	$btn2_url = home_url( '/du-an/' );
}
?>

<section id="services-cta" class="services-cta relative overflow-hidden">
	<div class="services-cta__bg absolute inset-0">
		<?php if ( $cta_bg_image ) : ?>
			<?php
			echo wp_get_attachment_image( $cta_bg_image['ID'], 'full', false, [
				'class'   => 'w-full h-full object-cover',
				'alt'     => esc_attr( $cta_bg_image['alt'] ?? 'Dịch vụ XANH Design & Build' ),
				'loading' => 'lazy',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $cta_img_url ); ?>" alt="Dịch vụ XANH Design & Build" class="w-full h-full object-cover" width="1920" height="600" loading="lazy" />
		<?php endif; ?>
	</div>
	<div class="absolute inset-0 bg-primary/85 z-[1]"></div>
	<div class="relative z-10 site-container text-center py-16 md:py-20 lg:py-28">
		<h2 class="section-title section-title--light text-white mb-4 md:mb-6 anim-fade-up">
			<?php echo wp_kses_post( $cta_title ); ?>
		</h2>
		<p class="section-subtitle text-white/70 max-w-2xl mx-auto mb-8 md:mb-10 anim-fade-up" style="transition-delay:.15s">
			<?php echo esc_html( $cta_subtitle ); ?>
		</p>
		<div class="flex flex-col sm:flex-row items-center justify-center gap-4 anim-fade-up" style="transition-delay:.3s">
			<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--primary group">
				<span><?php echo esc_html( $btn1_text ); ?></span>
				<i data-lucide="phone" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
			</a>
			<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--ghost">
				<span><?php echo esc_html( $btn2_text ); ?></span>
				<i data-lucide="briefcase" class="btn__icon w-5 h-5"></i>
			</a>
		</div>
	</div>
</section>
