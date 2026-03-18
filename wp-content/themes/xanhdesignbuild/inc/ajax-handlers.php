<?php
/**
 * AJAX Handlers — Filter, Load More, Search endpoints.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX: Filter projects by taxonomy.
 *
 * Expects POST params: type (taxonomy term slug), paged.
 * Returns JSON with 'html', 'total', 'pages'.
 *
 * @return void
 */
function xanh_ajax_filter_projects() {
	check_ajax_referer( 'xanh_filter_nonce', 'nonce' );

	$type  = sanitize_text_field( wp_unslash( $_POST['type'] ?? '' ) );
	$paged = absint( $_POST['paged'] ?? 1 );

	$per_page = apply_filters( 'xanh_portfolio_per_page', 9 );

	$args = [
		'post_type'      => 'xanh_project',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
	];

	if ( $type && 'all' !== $type ) {
		$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			[
				'taxonomy' => 'project_type',
				'field'    => 'slug',
				'terms'    => $type,
			],
		];
	}

	$query = new WP_Query( $args );
	$html  = '';

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content/card', 'project' );
		}
		$html = ob_get_clean();
		wp_reset_postdata();
	}

	wp_send_json_success( [
		'html'  => $html,
		'total' => $query->found_posts,
		'pages' => $query->max_num_pages,
	] );
}
add_action( 'wp_ajax_xanh_filter_projects', 'xanh_ajax_filter_projects' );
add_action( 'wp_ajax_nopriv_xanh_filter_projects', 'xanh_ajax_filter_projects' );
