<?php
/**
 * Template Part: Section — Portfolio CTA.
 *
 * Full-width CTA with background image overlay,
 * headline, subtitle, and dual action buttons.
 * ACF Options fields: portfolio_cta_*.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$cta_title    = xanh_get_option( 'portfolio_cta_title', 'Bạn Cũng Muốn Một Không Gian Sống<br class="hidden sm:block" /> Trọn Vẹn Và Minh Bạch?' );
$cta_subtitle = xanh_get_option( 'portfolio_cta_subtitle', 'Hãy bắt đầu hành trình kiến tạo tổ ấm cùng đội ngũ XANH — minh bạch từ dự toán đến bàn giao.' );
$cta_bg_image = xanh_get_option_image( 'portfolio_cta_bg_image' );

$btn1_text = xanh_get_option( 'portfolio_cta_btn1_text', 'Khám Phá Dự Toán Của Bạn' );
$btn1_url  = xanh_get_option( 'portfolio_cta_btn1_url', '' );
$btn2_text = xanh_get_option( 'portfolio_cta_btn2_text', 'Chat Với Kỹ Sư Trưởng' );
$btn2_url  = xanh_get_option( 'portfolio_cta_btn2_url', '' );

// CTA background image URL.
$cta_img_url = '';
if ( $cta_bg_image ) {
	$cta_img_url = $cta_bg_image['url'];
} else {
	$cta_img_url = site_url( '/wp-content/uploads/2026/03/project-after-1.png' );
}

// Fallback URLs.
if ( empty( $btn1_url ) ) {
	$btn1_url = home_url( '/du-toan/' );
}
if ( empty( $btn2_url ) ) {
	$btn2_url = '#';
}
?>

<section id="portfolio-cta" class="portfolio-cta relative overflow-hidden">
	<div class="portfolio-cta__bg absolute inset-0">
		<?php if ( $cta_bg_image ) : ?>
			<?php
			echo wp_get_attachment_image( $cta_bg_image['ID'], 'full', false, [
				'class'   => 'w-full h-full object-cover',
				'alt'     => esc_attr( $cta_bg_image['alt'] ?? 'Dự án XANH Design & Build' ),
				'loading' => 'lazy',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $cta_img_url ); ?>" alt="Dự án XANH Design & Build" class="w-full h-full object-cover" width="1920" height="600" loading="lazy" />
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
				<i data-lucide="calculator" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5"></i>
			</a>
			<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--ghost">
				<span><?php echo esc_html( $btn2_text ); ?></span>
				<i data-lucide="message-circle" class="btn__icon w-5 h-5"></i>
			</a>
		</div>
	</div>
</section>
