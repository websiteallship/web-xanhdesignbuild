<?php
/**
 * Template Part: Hero — Services Archive Page.
 *
 * Full-viewport hero with background image, headline, subtitle,
 * and counter strip (Dịch Vụ / Năm Kinh Nghiệm / Hài Lòng).
 * ACF Options fields: services_hero_* on ACF Options page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$hero_eyebrow = xanh_get_option( 'services_hero_eyebrow', 'Dịch Vụ — XANH Design & Build' );
$hero_title   = xanh_get_option( 'services_hero_title', 'Giải Pháp Kiến Trúc<br class="hidden sm:block" /> & Nội Thất Trọn Vẹn.' );
$hero_desc    = xanh_get_option( 'services_hero_subtitle', 'Từ thiết kế kiến trúc, nội thất đến thi công trọn gói — mỗi dịch vụ đều được kiến tạo với tinh thần minh bạch và cam kết chất lượng.' );
$hero_image   = xanh_get_option_image( 'services_hero_image' );

// Counter values from ACF Options.
$counter_services     = xanh_get_option( 'services_hero_counter_services', '' );
$counter_experience   = xanh_get_option( 'services_hero_counter_experience', '10' );
$counter_satisfaction = xanh_get_option( 'services_hero_counter_satisfaction', '98' );

// Dynamic service count: use ACF value or auto-count from CPT.
if ( empty( $counter_services ) ) {
	$service_counts   = wp_count_posts( 'xanh_service' );
	$counter_services = $service_counts->publish ?? 0;
}

// Hero background image URL.
$hero_img_url = '';
if ( $hero_image ) {
	$hero_img_url = $hero_image['url'];
} else {
	$hero_img_url = site_url( '/wp-content/uploads/2026/03/about-hero-bg.png' );
}
?>

<section id="services-hero" class="services-hero relative w-full overflow-hidden">
	<div class="services-hero__bg absolute inset-0 w-full h-full">
		<?php if ( $hero_image ) : ?>
			<?php
			echo wp_get_attachment_image( $hero_image['ID'], 'full', false, [
				'class'          => 'w-full h-full object-cover',
				'alt'            => esc_attr( $hero_image['alt'] ?? 'Dịch vụ kiến trúc & nội thất — XANH' ),
				'fetchpriority'  => 'high',
				'decoding'       => 'async',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Dịch vụ kiến trúc & nội thất — XANH" class="w-full h-full object-cover" width="1920" height="1080" fetchpriority="high" loading="eager" decoding="async" />
		<?php endif; ?>
	</div>
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/80 z-[1]"></div>
	<div class="relative z-10 flex flex-col justify-center items-center text-center h-full site-container px-6">
		<div class="max-w-3xl mx-auto">
			<span class="services-hero-el section-eyebrow text-white/60 block mb-5">
				<?php echo esc_html( $hero_eyebrow ); ?>
			</span>
			<h1 class="services-hero-el section-title section-title--light text-white font-bold tracking-[-0.02em] leading-tight mb-6">
				<?php echo wp_kses_post( $hero_title ); ?>
			</h1>
			<p class="services-hero-el text-white/70 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
				<?php echo esc_html( $hero_desc ); ?>
			</p>
		</div>
	</div>
	<div class="relative z-10 w-full border-t border-white/10">
		<div class="site-container">
			<div class="counter-strip flex items-center justify-center gap-5 sm:gap-8 md:gap-16 lg:gap-24 py-3 sm:py-6 md:py-8">
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_services ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">+</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Dịch Vụ</p>
				</div>
				<div class="counter-divider w-px h-10 bg-white/15"></div>
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_experience ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">+</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Năm Kinh Nghiệm</p>
				</div>
				<div class="counter-divider w-px h-10 bg-white/15"></div>
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_satisfaction ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">%</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Hài Lòng</p>
				</div>
			</div>
		</div>
	</div>
</section>
