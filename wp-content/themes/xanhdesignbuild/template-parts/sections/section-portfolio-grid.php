<?php
/**
 * Template Part: Section — Portfolio Project Grid.
 *
 * WP Loop for xanh_project CPT with Load More button
 * and skeleton loading placeholder.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$per_page = apply_filters( 'xanh_portfolio_per_page', 9 );

$grid_args = [
	'post_type'      => 'xanh_project',
	'posts_per_page' => $per_page,
	'paged'          => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
];

// On taxonomy archive, filter by current term.
if ( is_tax( 'project_type' ) ) {
	$grid_args['tax_query'] = [
		[
			'taxonomy' => 'project_type',
			'field'    => 'term_id',
			'terms'    => get_queried_object_id(),
		],
	];
}

$portfolio_query = new WP_Query( $grid_args );

$total_pages = $portfolio_query->max_num_pages;
?>

<section id="portfolio-grid-section" class="bg-white relative w-full py-12 md:py-16 lg:py-20">
	<div class="site-container">
		<div id="portfolio-grid" class="portfolio-grid">

			<?php if ( $portfolio_query->have_posts() ) : ?>
				<?php while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post(); ?>
					<?php get_template_part( 'template-parts/content/card', 'project' ); ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="col-span-full text-center py-20">
					<p class="text-dark/50 text-lg">Chưa có dự án nào được đăng tải.</p>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( $total_pages > 1 ) : ?>
			<!-- Load More -->
			<div class="flex justify-center mt-12 md:mt-16">
				<button id="load-more-btn" class="btn btn--outline group">
					<span>Xem Thêm Dự Án</span>
					<i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"></i>
				</button>
			</div>

			<!-- Skeleton -->
			<div id="skeleton-loader" class="portfolio-grid mt-6 hidden">
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
			</div>
		<?php endif; ?>
	</div>
</section>
