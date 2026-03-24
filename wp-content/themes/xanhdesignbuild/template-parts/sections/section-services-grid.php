<?php
/**
 * Template Part: Section — Services Grid.
 *
 * WP Loop for xanh_service CPT with Load More button
 * and skeleton loading placeholder.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$per_page = apply_filters( 'xanh_services_per_page', 6 );

$grid_args = [
	'post_type'      => 'xanh_service',
	'posts_per_page' => $per_page,
	'paged'          => 1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
];

$services_query = new WP_Query( $grid_args );
$total_pages    = $services_query->max_num_pages;
?>

<section id="services-grid-section" class="bg-white relative w-full py-12 md:py-16 lg:py-20">
	<div class="site-container">

		<?php // Section heading ?>
		<div class="text-center mb-10 md:mb-14">
			<span class="section-eyebrow text-primary/50 block mb-4 anim-fade-up">Dịch Vụ Của Chúng Tôi</span>
			<h2 class="section-title anim-fade-up delay-100">Giải Pháp Trọn Vẹn<br class="hidden sm:block" /> Cho Không Gian Sống</h2>
		</div>

		<div id="services-grid" class="services-grid">

			<?php if ( $services_query->have_posts() ) : ?>
				<?php while ( $services_query->have_posts() ) : $services_query->the_post(); ?>
					<?php get_template_part( 'template-parts/content/card', 'service' ); ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="col-span-full text-center py-20">
					<p class="text-dark/50 text-lg">Chưa có dịch vụ nào được đăng tải.</p>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( $total_pages > 1 ) : ?>
			<!-- Load More -->
			<div class="flex justify-center mt-12 md:mt-16">
				<button id="services-load-more-btn" class="btn btn--outline group">
					<span>Xem Thêm Dịch Vụ</span>
					<i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"></i>
				</button>
			</div>

			<!-- Skeleton -->
			<div id="services-skeleton-loader" class="services-grid mt-6 hidden">
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
				<div class="skeleton-card"><div class="skeleton-shimmer"></div></div>
			</div>
		<?php endif; ?>
	</div>
</section>
