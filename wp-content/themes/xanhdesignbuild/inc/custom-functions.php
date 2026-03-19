<?php
/**
 * Custom Functions — xanh_get_*() data helpers.
 *
 * Business logic lives HERE, not in templates.
 * Templates call these helpers and render the returned data.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get company hotline from ACF Options.
 *
 * @return string Phone number or empty string.
 */
function xanh_get_hotline() {
	return xanh_get_option( 'xanh_hotline' );
}

/**
 * Get company email from ACF Options.
 *
 * @return string Email or empty string.
 */
function xanh_get_email() {
	return xanh_get_option( 'xanh_email' );
}

/**
 * Get company address from ACF Options.
 *
 * @return string Address or empty string.
 */
function xanh_get_address() {
	return xanh_get_option( 'xanh_address' );
}

/**
 * Get social media links from ACF Options.
 *
 * @return array Associative array of social links.
 */
function xanh_get_social_links() {
	return [
		'facebook'  => xanh_get_option( 'xanh_facebook' ),
		'instagram' => xanh_get_option( 'xanh_instagram' ),
		'youtube'   => xanh_get_option( 'xanh_youtube' ),
		'zalo'      => xanh_get_option( 'xanh_zalo_oa' ),
	];
}

/**
 * Get featured projects (Relationship field from Homepage).
 *
 * Uses transient caching to avoid repeated queries.
 *
 * @return array Array of WP_Post objects or empty array.
 */
function xanh_get_featured_projects() {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$cached = get_transient( 'xanh_featured_projects' );
	if ( false !== $cached ) {
		return $cached;
	}

	$front_page_id = (int) get_option( 'page_on_front' );
	$projects      = get_field( 'featured_projects', $front_page_id );

	if ( ! $projects ) {
		$projects = [];
	}

	set_transient( 'xanh_featured_projects', $projects, HOUR_IN_SECONDS );

	return $projects;
}

/**
 * Get latest blog posts.
 *
 * @param  int $count Number of posts to fetch.
 * @return WP_Post[] Array of post objects.
 */
function xanh_get_latest_posts( $count = 3 ) {
	return get_posts( [
		'post_type'      => 'post',
		'posts_per_page' => absint( $count ),
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );
}

/**
 * Clear featured projects transient when a project is saved.
 *
 * @param  int $post_id Post ID.
 * @return void
 */
function xanh_clear_projects_cache( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'xanh_project' === $type || 'page' === $type ) {
		delete_transient( 'xanh_featured_projects' );
	}
	// Clear blog transient when any post is saved.
	if ( 'post' === $type ) {
		delete_transient( 'xanh_home_blog_latest' );
	}
}
add_action( 'save_post', 'xanh_clear_projects_cache' );
