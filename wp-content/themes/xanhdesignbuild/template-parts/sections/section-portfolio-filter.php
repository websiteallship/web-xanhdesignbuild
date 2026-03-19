<?php
/**
 * Template Part: Section — Portfolio Filter Bar.
 *
 * Sticky glassmorphism filter bar with dynamic tabs
 * from project_type taxonomy.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Total published projects.
$total_projects = wp_count_posts( 'xanh_project' )->publish ?? 0;

// Get project_type taxonomy terms.
$project_types = get_terms( [
	'taxonomy'   => 'project_type',
	'hide_empty' => false,
	'orderby'    => 'count',
	'order'      => 'DESC',
] );

if ( is_wp_error( $project_types ) ) {
	$project_types = [];
}
?>

<div id="portfolio-filter-bar" class="filter-bar">
	<div class="site-container">
		<div class="filter-bar__inner" role="tablist" aria-label="Lọc dự án">
			<button class="filter-tab is-active" data-filter="all" role="tab" aria-selected="true">Tất Cả <span class="filter-tab__count"><?php echo esc_html( $total_projects ); ?></span></button>
			<?php foreach ( $project_types as $term ) : ?>
				<button class="filter-tab" data-filter="<?php echo esc_attr( $term->slug ); ?>" role="tab" aria-selected="false"><?php echo esc_html( $term->name ); ?> <span class="filter-tab__count"><?php echo esc_html( $term->count ); ?></span></button>
			<?php endforeach; ?>
		</div>
	</div>
</div>
