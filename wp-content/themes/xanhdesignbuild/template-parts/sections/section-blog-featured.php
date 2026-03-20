<?php
/**
 * Template Part: Section — Blog Featured Articles.
 *
 * Displays 3 featured posts: 1 large card + 2 small stacked cards.
 * Priority: sticky posts first, then fill remaining slots with latest posts.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build featured posts array (always 3 posts):
 * 1. Get sticky posts first.
 * 2. Fill remaining slots with latest non-sticky posts.
 */
$featured_ids = [];

// Step 1: Get sticky posts.
$sticky = get_option( 'sticky_posts' );
if ( ! empty( $sticky ) ) {
	$sticky_query = new WP_Query( [
		'post__in'            => $sticky,
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'fields'              => 'ids',
	] );
	$featured_ids = $sticky_query->posts;
	wp_reset_postdata();
}

// Step 2: Fill remaining slots with latest non-sticky posts.
$remaining = 3 - count( $featured_ids );
if ( $remaining > 0 ) {
	$fill_query = new WP_Query( [
		'posts_per_page' => $remaining,
		'post_status'    => 'publish',
		'post__not_in'   => ! empty( $featured_ids ) ? $featured_ids : [],
		'orderby'        => 'date',
		'order'          => 'DESC',
		'fields'         => 'ids',
	] );
	$featured_ids = array_merge( $featured_ids, $fill_query->posts );
	wp_reset_postdata();
}

if ( empty( $featured_ids ) ) {
	return;
}

// Query full post objects in the correct order.
$featured_query = new WP_Query( [
	'post__in'            => $featured_ids,
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'orderby'             => 'post__in',
	'ignore_sticky_posts' => true,
] );

if ( ! $featured_query->have_posts() ) {
	return;
}
?>

<section id="featured-articles" class="featured-articles bg-white py-12 md:py-16 lg:py-20">
	<div class="site-container">
		<!-- Section Header -->
		<div class="section-header section-header--center mb-8 md:mb-10">
			<span class="section-eyebrow text-primary/50">Nổi Bật</span>
			<h2 class="section-title text-primary mt-2">Bài Viết <em class="text-primary not-italic">Được Chọn Lọc</em></h2>
		</div>

		<!-- Featured Grid: 1 large + 2 stacked -->
		<div class="featured-grid" id="featured-grid">

			<?php
			$post_index = 0;
			while ( $featured_query->have_posts() ) :
				$featured_query->the_post();
				$post_index++;

				if ( 1 === $post_index ) :
					// Large card (left).
					get_template_part( 'template-parts/content/card-blog', 'featured', [ 'size' => 'large' ] );
				elseif ( 2 === $post_index ) :
					// Open right column.
					echo '<div class="featured-col-right">';
					get_template_part( 'template-parts/content/card-blog', 'featured', [ 'size' => 'small' ] );
				else :
					get_template_part( 'template-parts/content/card-blog', 'featured', [ 'size' => 'small' ] );
					// Close right column.
					echo '</div>';
				endif;
			endwhile;

			// If only 2 posts, close right column.
			if ( 2 === $post_index ) {
				echo '</div>';
			}

			wp_reset_postdata();
			?>

		</div><!-- /featured-grid -->
	</div>
</section>
