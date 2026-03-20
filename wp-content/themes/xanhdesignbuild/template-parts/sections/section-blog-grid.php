<?php
/**
 * Template Part: Section — Blog Article Grid.
 *
 * Displays all blog posts in a responsive 3-column grid.
 * AJAX Load More loads additional posts via xanh_blog_load_more handler.
 * Excludes the same 3 posts shown in the Featured section.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculate exclude IDs — must match section-blog-featured.php logic.
 * Step 1: sticky posts, Step 2: fill with latest.
 */
$exclude_ids = [];
$sticky      = get_option( 'sticky_posts' );

if ( ! empty( $sticky ) ) {
	$sticky_q    = get_posts( [
		'post__in'            => $sticky,
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'fields'              => 'ids',
	] );
	$exclude_ids = $sticky_q;
}

$remaining = 3 - count( $exclude_ids );
if ( $remaining > 0 ) {
	$fill_q      = get_posts( [
		'posts_per_page' => $remaining,
		'post_status'    => 'publish',
		'post__not_in'   => ! empty( $exclude_ids ) ? $exclude_ids : [],
		'fields'         => 'ids',
	] );
	$exclude_ids = array_merge( $exclude_ids, $fill_q );
}

// Query grid posts (exclude featured).
$paged     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$per_page  = 6;
$grid_args = [
	'post_type'      => 'post',
	'posts_per_page' => $per_page,
	'post_status'    => 'publish',
	'paged'          => $paged,
	'post__not_in'   => $exclude_ids,
];

// If on a category archive, add category filter.
if ( is_category() ) {
	$grid_args['cat'] = get_queried_object_id();
}

$grid_query = new WP_Query( $grid_args );

if ( ! $grid_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<section id="article-grid" class="article-grid-section py-12 md:py-16 lg:py-20">
	<div class="site-container">

		<!-- Section Header -->
		<div class="section-header section-header--center mb-8 md:mb-10 anim-fade-up">
			<span class="section-eyebrow text-primary/50">Tất Cả Bài Viết</span>
			<h2 class="section-title text-primary mt-2">Kho <em class="text-primary not-italic">Kiến Thức</em> Xanh</h2>
		</div>

		<!-- Article Grid -->
		<div class="article-grid" id="article-grid-container">
			<?php
			while ( $grid_query->have_posts() ) :
				$grid_query->the_post();
				get_template_part( 'template-parts/content/card', 'blog' );
			endwhile;
			?>
		</div><!-- /article-grid -->

		<!-- Load More -->
		<?php if ( $grid_query->max_num_pages > 1 ) : ?>
			<div class="article-grid__footer anim-fade-up">
				<button class="article-grid__load-more group" id="load-more-btn" type="button"
					data-page="1"
					data-max="<?php echo esc_attr( $grid_query->max_num_pages ); ?>"
					data-exclude="<?php echo esc_attr( implode( ',', $exclude_ids ) ); ?>"
					<?php if ( is_category() ) : ?>
						data-category="<?php echo esc_attr( get_queried_object_id() ); ?>"
					<?php endif; ?>>
					<span>Xem Thêm Bài Viết</span>
					<i data-lucide="chevron-down" class="w-5 h-5"></i>
				</button>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php wp_reset_postdata(); ?>
