<?php
/**
 * Template Part: Section — Blog Detail End-of-Article CTA.
 *
 * Renders a full-width CTA banner at the end of the article
 * with a contact form for free consultation requests.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$cta_eyebrow    = xanh_get_option( 'blog_detail_cta_eyebrow', 'Tư Vấn Miễn Phí' );
$cta_headline   = xanh_get_option( 'blog_detail_cta_headline', 'Bạn Đang Lên Kế Hoạch Xây Nhà?' );
$cta_desc       = xanh_get_option( 'blog_detail_cta_description', 'Để lại thông tin, chuyên gia XANH sẽ liên hệ tư vấn và lập <strong class="text-white/90">bảng khái toán chi tiết</strong> cho dự án của bạn — hoàn toàn miễn phí.' );
$cta_trust_text = xanh_get_option( 'blog_detail_cta_trust', 'Bảo mật thông tin. XANH cam kết không spam.' );
?>

<!-- End-of-Article CTA Banner -->
<div class="end-cta my-14 bg-primary relative overflow-hidden p-8 md:p-12">
	<!-- Decorative bg -->
	<div class="absolute inset-0 opacity-[0.04]">
		<svg class="w-full h-full" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="350" cy="50" r="120" stroke="white" stroke-width="1"/><circle cx="50" cy="350" r="80" stroke="white" stroke-width="1"/></svg>
	</div>
	<div class="relative z-10 max-w-2xl mx-auto text-center">
		<span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4">
			<?php echo esc_html( $cta_eyebrow ); ?>
		</span>
		<h3 class="font-heading text-2xl md:text-3xl font-bold text-white leading-tight mb-3">
			<?php echo esc_html( $cta_headline ); ?>
		</h3>
		<p class="text-white/65 text-sm md:text-base mb-8 leading-relaxed">
			<?php echo wp_kses_post( $cta_desc ); ?>
		</p>
		<div class="end-cta__form max-w-3xl mx-auto">
			<?php echo do_shortcode( '[fluentform id="7"]' ); ?>
		</div>
		<p class="text-white/30 text-xs mt-4 flex items-center justify-center gap-1.5">
			<i data-lucide="shield-check" class="w-3.5 h-3.5 text-accent"></i>
			<?php echo esc_html( $cta_trust_text ); ?>
		</p>
	</div>
</div>
