<?php
/**
 * Admin Tweaks — Custom columns, dashboard modifications.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add "Hình Ảnh" (Thumbnail) column to specific post types.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function xanh_add_thumbnail_column( $columns ) {
	// Add the thumbnail column before the Title column.
	$new_columns = [];
	foreach ( $columns as $key => $title ) {
		if ( 'title' === $key ) {
			$new_columns['xanh_thumbnail'] = 'Hình Ảnh';
		}
		$new_columns[ $key ] = $title;
	}

	// Fallback if title column wasn't found.
	if ( ! isset( $new_columns['xanh_thumbnail'] ) ) {
		$new_columns['xanh_thumbnail'] = 'Hình Ảnh';
	}

	return $new_columns;
}
add_filter( 'manage_post_posts_columns', 'xanh_add_thumbnail_column' );

/**
 * Render the "Hình Ảnh" (Thumbnail) column content.
 *
 * @param string $column_name Current column name.
 * @param int    $post_id     Current post ID.
 */
function xanh_render_thumbnail_column( $column_name, $post_id ) {
	if ( 'xanh_thumbnail' === $column_name ) {
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, [ 80, 80 ], [
				'style' => 'width: 60px; height: 60px; object-fit: cover; border-radius: 4px; display: block;',
			] );
		} else {
			echo '<span style="color: #999; font-size: 11px;">—</span>';
		}
	}
}
add_action( 'manage_post_posts_custom_column', 'xanh_render_thumbnail_column', 10, 2 );

/**
 * Set custom width for the thumbnail column.
 */
function xanh_admin_style_thumbnail_column() {
	echo '<style>
		.column-xanh_thumbnail { width: 80px; text-align: center !important; }
		.column-xanh_thumbnail img { margin: 0 auto; }
	</style>';
}
add_action( 'admin_head', 'xanh_admin_style_thumbnail_column' );

/**
 * ====================================================================
 * Custom Admin Menu Order
 * ====================================================================
 * Group 1: Bài viết, Trang, Dự Án, Dịch Vụ
 * --- separator ---
 * Group 2: Media, Bình luận, Fluent Forms
 * --- separator ---
 * Remaining items follow their default order.
 */
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'xanh_custom_menu_order' );

/**
 * Reorder admin menu items.
 *
 * @param array $menu_order Default menu order slugs.
 * @return array Reordered menu slugs.
 */
function xanh_custom_menu_order( $menu_order ) {

	// ── Group 1: Content ──
	$group_content = [
		'edit.php',                          // Bài viết (Posts)
		'edit.php?post_type=page',           // Trang (Pages)
		'edit.php?post_type=xanh_project',   // Dự Án
		'edit.php?post_type=xanh_service',   // Dịch Vụ
	];

	// ── Group 2: Utility ──
	$group_utility = [
		'upload.php',                        // Media
		'edit-comments.php',                 // Bình luận
		'fluent_forms',                      // Fluent Forms
	];

	// Merge both groups for easy lookup.
	$prioritised = array_merge( $group_content, $group_utility );

	// Collect items that appear in $menu_order but NOT in our priority lists.
	$remaining = [];
	foreach ( $menu_order as $item ) {
		if (
			! in_array( $item, $prioritised, true )
			&& 'separator1' !== $item
			&& 'separator2' !== $item
			&& 'separator-content' !== $item
		) {
			$remaining[] = $item;
		}
	}

	// Build the final order.
	$new_order = [];

	// Dashboard always first (if present in remaining).
	if ( ( $key = array_search( 'index.php', $remaining, true ) ) !== false ) {
		$new_order[] = 'index.php';
		unset( $remaining[ $key ] );
		$remaining = array_values( $remaining );
		$new_order[] = 'separator1'; // separator after Dashboard
	}

	// Group 1 — Content items (only those that actually exist in menu).
	foreach ( $group_content as $slug ) {
		if ( in_array( $slug, $menu_order, true ) ) {
			$new_order[] = $slug;
		}
	}

	// Separator between content and utility.
	$new_order[] = 'separator-content';

	// Group 2 — Utility items.
	foreach ( $group_utility as $slug ) {
		if ( in_array( $slug, $menu_order, true ) ) {
			$new_order[] = $slug;
		}
	}

	// Separator before remaining items.
	$new_order[] = 'separator2';

	// Remaining items keep their relative order.
	foreach ( $remaining as $item ) {
		$new_order[] = $item;
	}

	return $new_order;
}

/**
 * Register a custom separator so WP renders the divider line between groups.
 */
function xanh_register_menu_separator() {
	global $menu;

	// Find a free position to insert the separator.
	// We use a fractional key to avoid colliding with existing entries.
	$position = 58.5;
	while ( isset( $menu[ (string) $position ] ) ) {
		$position += 0.1;
	}

	$menu[ $position ] = [ '', 'read', 'separator-content', '', 'wp-menu-separator' ];
}
add_action( 'admin_menu', 'xanh_register_menu_separator', 999 );
