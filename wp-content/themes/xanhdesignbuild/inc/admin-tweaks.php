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
