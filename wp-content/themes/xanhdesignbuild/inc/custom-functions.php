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
	// Clear popup count transient when popup is saved/updated.
	if ( 'xanh_popup' === $type ) {
		delete_transient( 'xanh_popup_count' );
	}
}
add_action( 'save_post', 'xanh_clear_projects_cache' );

/**
 * Auto-assign default placeholder thumbnail on save.
 *
 * When a post, page, or CPT is saved/published without a featured image,
 * this function automatically sets placeholder-project.png as the thumbnail.
 * The image is uploaded to the Media Library only once and reused via option.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function xanh_auto_assign_default_thumbnail( $post_id, $post, $update ) {
	// Skip autosave, revisions, and non-public post types.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	// Only apply to published posts.
	if ( 'publish' !== $post->post_status ) {
		return;
	}

	// Skip post types that don't support thumbnails.
	if ( ! post_type_supports( $post->post_type, 'thumbnail' ) ) {
		return;
	}

	// Skip popups — they don't need thumbnails.
	if ( 'xanh_popup' === $post->post_type ) {
		return;
	}

	// Already has a thumbnail — do nothing.
	if ( has_post_thumbnail( $post_id ) ) {
		return;
	}

	// Get or create the placeholder attachment.
	$placeholder_id = xanh_get_placeholder_attachment_id();

	if ( $placeholder_id ) {
		set_post_thumbnail( $post_id, $placeholder_id );
	}
}
add_action( 'save_post', 'xanh_auto_assign_default_thumbnail', 20, 3 );

/**
 * Get (or create) the Media Library attachment ID for the placeholder image.
 *
 * Uploads placeholder-project.png to the Media Library once,
 * stores the resulting attachment ID in wp_options for reuse.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function xanh_get_placeholder_attachment_id() {
	$option_key = 'xanh_placeholder_attachment_id';
	$att_id     = (int) get_option( $option_key, 0 );

	// Verify the attachment still exists.
	if ( $att_id && get_post( $att_id ) ) {
		return $att_id;
	}

	// Source file in the theme.
	$source = XANH_THEME_DIR . '/assets/images/placeholder-project.png';

	if ( ! file_exists( $source ) ) {
		return 0;
	}

	// Copy to uploads directory.
	$upload_dir = wp_upload_dir();
	$filename   = 'placeholder-project.png';
	$dest       = $upload_dir['path'] . '/' . $filename;

	// Avoid duplicate uploads — check if file already exists in uploads.
	if ( ! file_exists( $dest ) ) {
		copy( $source, $dest );
	}

	// Require media handling functions.
	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$filetype = wp_check_filetype( $filename, null );

	$attachment = [
		'guid'           => $upload_dir['url'] . '/' . $filename,
		'post_mime_type' => $filetype['type'],
		'post_title'     => 'XANH Placeholder',
		'post_content'   => '',
		'post_status'    => 'inherit',
	];

	$att_id = wp_insert_attachment( $attachment, $dest );

	if ( ! is_wp_error( $att_id ) && $att_id ) {
		$metadata = wp_generate_attachment_metadata( $att_id, $dest );
		wp_update_attachment_metadata( $att_id, $metadata );
		update_option( $option_key, $att_id );
	}

	return (int) $att_id;
}

