<?php
/**
 * Template Part: Section — Blog Detail Related Articles.
 *
 * Displays up to 3 related posts from the same category,
 * excluding the current post. Uses card-blog template part.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Get related posts from same category ──
$categories = get_the_category();
$current_id = get_the_ID();

if ( empty( $categories ) ) {
	return;
}

$related_query = new WP_Query( [
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'post__not_in'   => [ $current_id ],
	'category__in'   => [ $categories[0]->term_id ],
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true, // Performance: skip pagination count.
] );

// Fallback: if no posts in same category, get latest posts instead.
if ( 0 === $related_query->post_count ) {
	wp_reset_postdata();

	$related_query = new WP_Query( [
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'post__not_in'   => [ $current_id ],
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	] );
}

if ( ! $related_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<section class="related-articles py-16 md:py-24 bg-light mt-12 md:mt-24" style="contain: layout style;">
	<div class="site-container">
		<!-- Section Header -->
		<div class="section-header section-header--left mb-10">
			<h2 class="section-title text-primary">Bài Viết <em class="text-primary not-italic">Liên Quan</em></h2>
		</div>

		<!-- 3 Columns Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 gap-y-10">
			<?php
			$card_index = 0;
			while ( $related_query->have_posts() ) :
				$related_query->the_post();

				// Hide 3rd card on mobile, show on md+.
				$extra_class = ( 2 === $card_index ) ? 'hidden md:flex lg:flex md:col-span-2 lg:col-span-1' : '';

				// Pass extra class to card via set_query_var.
				set_query_var( 'card_extra_class', $extra_class );
				get_template_part( 'template-parts/content/card', 'blog' );

				++$card_index;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
