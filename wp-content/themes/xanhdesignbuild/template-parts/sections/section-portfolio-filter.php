<?php
/**
 * Template Part: Section — Portfolio Filter Bar.
 *
 * Sticky glassmorphism filter bar with dynamic tabs
 * from project_type taxonomy.
 * On taxonomy archives: renders as links with active state.
 * On portfolio archive: renders as AJAX filter buttons.
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

// Determine if we're on a taxonomy archive.
$is_tax_archive = is_tax( 'project_type' );
$current_term   = $is_tax_archive ? get_queried_object() : null;
?>

<div id="portfolio-filter-bar" class="filter-bar">
	<div class="site-container">
		<div class="filter-bar__inner" role="tablist" aria-label="Lọc dự án">
			<?php if ( $is_tax_archive ) : ?>
				<?php // Link mode: for taxonomy archives ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'xanh_project' ) ); ?>"
				   class="filter-tab"
				   role="tab" aria-selected="false"><?php esc_html_e( 'Tất Cả', 'xanh' ); ?> <span class="filter-tab__count"><?php echo esc_html( $total_projects ); ?></span></a>
				<?php foreach ( $project_types as $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
					   class="filter-tab<?php echo ( $current_term && $current_term->term_id === $term->term_id ) ? ' is-active' : ''; ?>"
					   role="tab"
					   aria-selected="<?php echo ( $current_term && $current_term->term_id === $term->term_id ) ? 'true' : 'false'; ?>"><?php echo esc_html( $term->name ); ?> <span class="filter-tab__count"><?php echo esc_html( $term->count ); ?></span></a>
				<?php endforeach; ?>
			<?php else : ?>
				<?php // Button mode: for main portfolio archive (AJAX filtering) ?>
				<button class="filter-tab is-active" data-filter="all" role="tab" aria-selected="true">Tất Cả <span class="filter-tab__count"><?php echo esc_html( $total_projects ); ?></span></button>
				<?php foreach ( $project_types as $term ) : ?>
					<button class="filter-tab" data-filter="<?php echo esc_attr( $term->slug ); ?>" role="tab" aria-selected="false"><?php echo esc_html( $term->name ); ?> <span class="filter-tab__count"><?php echo esc_html( $term->count ); ?></span></button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>

