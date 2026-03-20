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

/**
 * AJAX: Load more blog posts.
 *
 * Expects POST params: paged, exclude (comma-separated IDs), category (optional).
 * Returns JSON with 'html'.
 *
 * @return void
 */
function xanh_ajax_blog_load_more() {
	check_ajax_referer( 'xanh_blog_nonce', 'nonce' );

	$paged    = absint( $_POST['paged'] ?? 1 );
	$exclude  = sanitize_text_field( wp_unslash( $_POST['exclude'] ?? '' ) );
	$category = absint( $_POST['category'] ?? 0 );
	$per_page = 6;

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
	];

	if ( $exclude ) {
		$args['post__not_in'] = array_map( 'absint', explode( ',', $exclude ) );
	}

	if ( $category ) {
		$args['cat'] = $category;
	}

	$query = new WP_Query( $args );
	$html  = '';

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content/card', 'blog' );
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
add_action( 'wp_ajax_xanh_blog_load_more', 'xanh_ajax_blog_load_more' );
add_action( 'wp_ajax_nopriv_xanh_blog_load_more', 'xanh_ajax_blog_load_more' );

/**
 * AJAX: Load more services.
 *
 * Expects POST params: paged.
 * Returns JSON with 'html', 'total', 'pages'.
 *
 * @return void
 */
function xanh_ajax_load_more_services() {
	check_ajax_referer( 'xanh_services_nonce', 'nonce' );

	$paged    = absint( $_POST['paged'] ?? 1 );
	$per_page = apply_filters( 'xanh_services_per_page', 6 );

	$args = [
		'post_type'      => 'xanh_service',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	];

	$query = new WP_Query( $args );
	$html  = '';

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content/card', 'service' );
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
add_action( 'wp_ajax_xanh_load_more_services', 'xanh_ajax_load_more_services' );
add_action( 'wp_ajax_nopriv_xanh_load_more_services', 'xanh_ajax_load_more_services' );
