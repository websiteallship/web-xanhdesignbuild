<?php
/**
 * Template Part: Hero — Blog Page.
 *
 * Full-viewport hero with background image, headline, subtitle,
 * and animated search bar with typing placeholder effect.
 * ACF Options fields: blog_hero_* on ACF Options page.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── ACF fields with fallbacks ──
$hero_eyebrow = xanh_get_option( 'blog_hero_eyebrow', 'Blog — Cẩm Nang & Tin Tức' );
$hero_title   = xanh_get_option( 'blog_hero_headline', 'Cẩm Nang Xây Dựng &<br class="hidden sm:block" /> Không Gian Sống Bền Vững.' );
$hero_desc    = xanh_get_option( 'blog_hero_subtitle', 'Trở thành chuyên gia cho chính ngôi nhà của bạn — kinh nghiệm thực chiến, vật liệu bền vững, xu hướng thiết kế mới nhất.' );
$hero_image   = xanh_get_option_image( 'blog_hero_image' );

// ── Category archive: override with category info ──
if ( is_category() ) {
	$cat_obj      = get_queried_object();
	$hero_eyebrow = __( 'Chuyên Mục', 'xanh' );
	$hero_title   = esc_html( $cat_obj->name );
	$cat_desc     = category_description( $cat_obj->term_id );
	$hero_desc    = $cat_desc
		? wp_strip_all_tags( $cat_desc )
		: sprintf(
			/* translators: %s: category name */
			__( 'Tổng hợp các bài viết chuyên mục %s — kiến thức thực chiến từ đội ngũ XANH.', 'xanh' ),
			esc_html( $cat_obj->name )
		);
}

// Hero background image URL.
$hero_img_url = '';
if ( $hero_image ) {
	$hero_img_url = $hero_image['url'];
} else {
	$hero_img_url = site_url( '/wp-content/uploads/2026/03/interior-living.png' );
}
?>

<section id="blog-hero" class="blog-hero relative w-full overflow-hidden">
	<!-- Background Image -->
	<div class="blog-hero__bg absolute inset-0 w-full h-full">
		<?php if ( $hero_image ) : ?>
			<?php
			echo wp_get_attachment_image( $hero_image['ID'], 'xanh-hero', false, [
				'class'         => 'w-full h-full object-cover',
				'alt'           => esc_attr( $hero_image['alt'] ?? 'Không gian sống bền vững — XANH Design & Build' ),
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'width'         => '1920',
				'height'        => '1080',
			] );
			?>
		<?php else : ?>
			<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="Không gian sống bền vững — XANH Design & Build" class="w-full h-full object-cover" width="1920" height="1080" fetchpriority="high" />
		<?php endif; ?>
	</div>
	<!-- Gradient overlay -->
	<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-primary/50 to-primary/85 z-[1]"></div>

	<!-- Content -->
	<div class="relative z-10 flex flex-col justify-center items-center text-center h-full site-container px-6">
		<div class="max-w-3xl mx-auto">
			<span class="hero-el--fast section-eyebrow text-white/60 block mb-5">
				<?php echo esc_html( $hero_eyebrow ); ?>
			</span>
			<h1 class="hero-el--fast section-title section-title--light text-white font-bold tracking-[-0.02em] leading-tight mb-6">
				<?php echo wp_kses_post( $hero_title ); ?>
			</h1>
			<p class="hero-el--fast text-white/70 text-base md:text-lg max-w-xl mx-auto leading-relaxed mb-10">
				<?php echo esc_html( $hero_desc ); ?>
			</p>

			<!-- Search Bar -->
			<div class="hero-el--fast blog-search-wrapper relative max-w-lg mx-auto">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="blog-search relative">
					<i data-lucide="search" class="blog-search__icon absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-white/40 z-10"></i>
					<input
						type="text"
						id="blog-search-input"
						name="s"
						class="blog-search__input w-full pl-14 pr-5 py-4 bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm placeholder-white/40 focus:outline-none focus:border-white/50 focus:bg-white/15 transition-all duration-300"
						placeholder=""
						autocomplete="off"
						value="<?php echo esc_attr( get_search_query() ); ?>"
						aria-label="<?php esc_attr_e( 'Tìm kiếm bài viết', 'xanh' ); ?>"
					/>
					<!-- Animated placeholder -->
					<span id="blog-search-placeholder" class="blog-search__placeholder absolute left-14 top-1/2 -translate-y-1/2 text-white/40 text-sm pointer-events-none"></span>
				</form>
				<!-- Autocomplete dropdown (hidden by default) -->
				<div id="blog-search-dropdown" class="blog-search__dropdown absolute top-full left-0 w-full bg-white/95 backdrop-blur-md border border-white/10 shadow-2xl mt-1 z-20 hidden">
					<?php
					// Show recent posts as search suggestions.
					$recent_posts = wp_get_recent_posts( [
						'numberposts' => 3,
						'post_status' => 'publish',
					] );
					foreach ( $recent_posts as $recent ) :
					?>
						<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="blog-search__dropdown-item px-5 py-3 text-dark/80 text-sm hover:bg-primary/5 cursor-pointer transition-colors block">
							<i data-lucide="file-text" class="w-4 h-4 inline-block mr-2 text-primary/40"></i>
							<?php echo esc_html( $recent['post_title'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

</section>
