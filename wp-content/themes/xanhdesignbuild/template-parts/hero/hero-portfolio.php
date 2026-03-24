<?php
/**
 * Template Part: Hero — Portfolio Page.
 *
 * Full-viewport hero with background image, headline, subtitle,
 * and counter strip (Projects / Sát 3D / Phát Sinh).
 * ACF Options fields: portfolio_hero_* on ACF Options page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$hero_eyebrow = xanh_get_option( 'portfolio_hero_eyebrow', 'Portfolio — Tác Phẩm Thực Tế' );
$hero_title   = xanh_get_option( 'portfolio_hero_title', 'Tác Phẩm Thực Tế.<br class="hidden sm:block" /> Giá Trị Khởi Nguồn Từ Sự Thật.' );
$hero_desc    = xanh_get_option( 'portfolio_hero_subtitle', 'Mỗi công trình là một hành trình — từ bản vẽ 3D đến không gian sống thực tế, minh bạch từng chi tiết, trọn vẹn từng kỳ vọng.' );
$hero_image   = xanh_get_option_image( 'portfolio_hero_image' );

// ── Taxonomy archive: override with term info ──
if ( is_tax( 'project_type' ) ) {
	$term_obj     = get_queried_object();
	$hero_eyebrow = __( 'Loại Dự Án', 'xanh' );
	$hero_title   = esc_html( $term_obj->name );
	$term_desc    = term_description( $term_obj->term_id );
	$hero_desc    = $term_desc
		? wp_strip_all_tags( $term_desc )
		: sprintf(
			/* translators: %s: project type name */
			__( 'Mỗi công trình %s là một tác phẩm mang đậm dấu ấn riêng — minh bạch từ bản vẽ đầu tiên đến khi bàn giao trọn vẹn.', 'xanh' ),
			esc_html( $term_obj->name )
		);
}

// Counter values from ACF Options.
$counter_projects = xanh_get_option( 'portfolio_counter_projects', '' );
$counter_3d       = xanh_get_option( 'portfolio_counter_3d', '98' );
$counter_overrun  = xanh_get_option( 'portfolio_counter_overrun', '0' );

// Dynamic project count: use ACF value or auto-count from CPT.
if ( empty( $counter_projects ) ) {
	$project_counts  = wp_count_posts( 'xanh_project' );
	$counter_projects = $project_counts->publish ?? 0;
}

// Hero background image URL.
$hero_img_url = '';
if ( $hero_image ) {
	$hero_img_url = $hero_image['url'];
} else {
	$hero_img_url = site_url( '/wp-content/uploads/2026/03/about-hero-bg.png' );
}
?>

<section id="portfolio-hero" class="portfolio-hero relative w-full overflow-hidden">
	<div class="portfolio-hero__bg absolute inset-0 w-full h-full">
		<?php if ( $hero_image ) : ?>
			<?php
			echo wp_get_attachment_image( $hero_image['ID'], 'full', false, [
				'class'          => 'w-full h-full object-cover',
				'alt'            => esc_attr( $hero_image['alt'] ?? 'Dự án nội thất — XANH' ),
				'fetchpriority'  => 'high',
				'decoding'       => 'async',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Dự án nội thất — XANH" class="w-full h-full object-cover" width="1920" height="1080" fetchpriority="high" loading="eager" decoding="async" />
		<?php endif; ?>
	</div>
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/80 z-[1]"></div>
	<div class="relative z-10 flex flex-col justify-center items-center text-center h-full site-container px-6">
		<div class="max-w-3xl mx-auto">
			<span class="hero-el--fast section-eyebrow text-white/60 block mb-5">
				<?php echo esc_html( $hero_eyebrow ); ?>
			</span>
			<h1 class="hero-el--fast section-title section-title--light text-white font-bold tracking-[-0.02em] leading-tight mb-6">
				<?php echo wp_kses_post( $hero_title ); ?>
			</h1>
			<p class="hero-el--fast text-white/70 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
				<?php echo esc_html( $hero_desc ); ?>
			</p>
		</div>
	</div>
	<div class="relative z-10 w-full border-t border-white/10">
		<div class="site-container">
			<div class="counter-strip flex items-center justify-center gap-5 sm:gap-8 md:gap-16 lg:gap-24 py-3 sm:py-6 md:py-8">
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_projects ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">+</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Dự Án</p>
				</div>
				<div class="counter-divider w-px h-10 bg-white/15"></div>
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_3d ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">%</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Sát 3D</p>
				</div>
				<div class="counter-divider w-px h-10 bg-white/15"></div>
				<div class="counter-item text-center">
					<span class="counter-number text-white font-bold text-2xl md:text-3xl lg:text-4xl" data-target="<?php echo esc_attr( $counter_overrun ); ?>">0</span><span class="text-accent font-bold text-2xl md:text-3xl lg:text-4xl">%</span>
					<p class="text-white/50 text-xs md:text-sm mt-1 uppercase tracking-wider">Phát Sinh</p>
				</div>
			</div>
		</div>
	</div>
</section>
