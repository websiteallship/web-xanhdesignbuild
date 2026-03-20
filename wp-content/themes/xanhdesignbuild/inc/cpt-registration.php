<?php
/**
 * Custom Post Type & Taxonomy Registration.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Types.
 *
 * @return void
 */
function xanh_register_post_types() {

	// ═══ xanh_project — Dự Án ═══
	register_post_type( 'xanh_project', [
		'labels'       => [
			'name'               => __( 'Dự Án', 'xanh' ),
			'singular_name'      => __( 'Dự Án', 'xanh' ),
			'add_new'            => __( 'Thêm Dự Án', 'xanh' ),
			'add_new_item'       => __( 'Thêm Dự Án Mới', 'xanh' ),
			'edit_item'          => __( 'Sửa Dự Án', 'xanh' ),
			'new_item'           => __( 'Dự Án Mới', 'xanh' ),
			'view_item'          => __( 'Xem Dự Án', 'xanh' ),
			'search_items'       => __( 'Tìm Dự Án', 'xanh' ),
			'not_found'          => __( 'Không tìm thấy dự án', 'xanh' ),
			'not_found_in_trash' => __( 'Không có dự án trong thùng rác', 'xanh' ),
			'all_items'          => __( 'Tất cả Dự Án', 'xanh' ),
			'archives'           => __( 'Kho Dự Án', 'xanh' ),
		],
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => [ 'slug' => 'du-an', 'with_front' => false ],
		'menu_icon'    => 'dashicons-portfolio',
		'menu_position' => 5,
		'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'show_in_rest' => true,
	] );

	// ═══ xanh_service — Dịch Vụ ═══
	register_post_type( 'xanh_service', [
		'labels'        => [
			'name'               => __( 'Dịch Vụ', 'xanh' ),
			'singular_name'      => __( 'Dịch Vụ', 'xanh' ),
			'add_new'            => __( 'Thêm Dịch Vụ', 'xanh' ),
			'add_new_item'       => __( 'Thêm Dịch Vụ Mới', 'xanh' ),
			'edit_item'          => __( 'Sửa Dịch Vụ', 'xanh' ),
			'new_item'           => __( 'Dịch Vụ Mới', 'xanh' ),
			'view_item'          => __( 'Xem Dịch Vụ', 'xanh' ),
			'search_items'       => __( 'Tìm Dịch Vụ', 'xanh' ),
			'not_found'          => __( 'Không tìm thấy dịch vụ', 'xanh' ),
			'not_found_in_trash' => __( 'Không có dịch vụ trong thùng rác', 'xanh' ),
			'all_items'          => __( 'Tất cả Dịch Vụ', 'xanh' ),
			'archives'           => __( 'Danh Sách Dịch Vụ', 'xanh' ),
		],
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => [ 'slug' => 'dich-vu', 'with_front' => false ],
		'menu_icon'     => 'dashicons-hammer',
		'menu_position' => 6,
		'supports'      => [ 'title', 'thumbnail', 'excerpt' ],
		'show_in_rest'  => true,
	] );
}
add_action( 'init', 'xanh_register_post_types' );

/**
 * Register Custom Taxonomies.
 *
 * @return void
 */
function xanh_register_taxonomies() {

	// ═══ project_type — Loại Dự Án ═══
	register_taxonomy( 'project_type', 'xanh_project', [
		'labels'       => [
			'name'              => __( 'Loại Dự Án', 'xanh' ),
			'singular_name'     => __( 'Loại Dự Án', 'xanh' ),
			'search_items'      => __( 'Tìm Loại Dự Án', 'xanh' ),
			'all_items'         => __( 'Tất cả Loại', 'xanh' ),
			'edit_item'         => __( 'Sửa Loại Dự Án', 'xanh' ),
			'add_new_item'      => __( 'Thêm Loại Mới', 'xanh' ),
			'new_item_name'     => __( 'Tên Loại Mới', 'xanh' ),
		],
		'hierarchical' => true,
		'public'       => true,
		'rewrite'      => [ 'slug' => 'loai-du-an', 'with_front' => false ],
		'show_in_rest' => true,
	] );

	// ═══ project_status — Trạng Thái Dự Án ═══
	register_taxonomy( 'project_status', 'xanh_project', [
		'labels'       => [
			'name'              => __( 'Trạng Thái', 'xanh' ),
			'singular_name'     => __( 'Trạng Thái', 'xanh' ),
			'search_items'      => __( 'Tìm Trạng Thái', 'xanh' ),
			'all_items'         => __( 'Tất cả Trạng Thái', 'xanh' ),
			'edit_item'         => __( 'Sửa Trạng Thái', 'xanh' ),
			'add_new_item'      => __( 'Thêm Trạng Thái Mới', 'xanh' ),
		],
		'hierarchical' => true,
		'public'       => true,
		'rewrite'      => [ 'slug' => 'trang-thai-du-an', 'with_front' => false ],
		'show_in_rest' => true,
	] );
}
add_action( 'init', 'xanh_register_taxonomies' );

/**
 * ====================================================================
 * Admin Columns Customization for 'xanh_project'
 * ====================================================================
 */

/**
 * Define the columns for the project list table.
 */
function xanh_project_columns( $columns ) {
	$new_columns = [];
	$new_columns['cb'] = $columns['cb']; // Checkbox

	// Add Thumbnail column right after Checkbox
	$new_columns['thumbnail'] = __( 'Hình Ảnh', 'xanh' );

	$new_columns['title'] = $columns['title'];

	// Add Taxonomy & ACF columns
	$new_columns['project_type']   = __( 'Loại Hình', 'xanh' );
	$new_columns['project_status'] = __( 'Trạng Thái', 'xanh' );
	$new_columns['project_location'] = __( 'Vị Trí', 'xanh' );

	$new_columns['date'] = $columns['date'];

	return $new_columns;
}
add_filter( 'manage_xanh_project_posts_columns', 'xanh_project_columns' );

/**
 * Output the content for the custom columns.
 */
function xanh_project_custom_column( $column, $post_id ) {
	switch ( $column ) {
		case 'thumbnail':
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, [60, 60], ['style' => 'width: 60px; height: auto; border-radius: 4px; object-fit: cover;'] );
			} else {
				echo '<span style="color: #999;">' . __( 'Chưa có', 'xanh' ) . '</span>';
			}
			break;

		case 'project_type':
			$terms = get_the_terms( $post_id, 'project_type' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$term_names = wp_list_pluck( $terms, 'name' );
				echo esc_html( implode( ', ', $term_names ) );
			} else {
				echo '—';
			}
			break;

		case 'project_status':
			$terms = get_the_terms( $post_id, 'project_status' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$term_names = wp_list_pluck( $terms, 'name' );
				echo esc_html( implode( ', ', $term_names ) );
			} else {
				echo '—';
			}
			break;

		case 'project_location':
			$location = get_field( 'project_location', $post_id );
			if ( $location ) {
				echo esc_html( $location );
			} else {
				echo '—';
			}
			break;
	}
}
add_action( 'manage_xanh_project_posts_custom_column', 'xanh_project_custom_column', 10, 2 );

/**
 * ====================================================================
 * Admin Columns Customization for 'xanh_service'
 * ====================================================================
 */

/**
 * Define the columns for the service list table.
 */
function xanh_service_columns( $columns ) {
	$new_columns = [];
	$new_columns['cb']        = $columns['cb'];
	$new_columns['thumbnail'] = __( 'Hình Ảnh', 'xanh' );
	$new_columns['title']     = $columns['title'];
	$new_columns['sv_slug']   = __( 'Slug', 'xanh' );
	$new_columns['sv_hero']   = __( 'Hero Title', 'xanh' );
	$new_columns['date']      = $columns['date'];

	return $new_columns;
}
add_filter( 'manage_xanh_service_posts_columns', 'xanh_service_columns' );

/**
 * Output the content for the service custom columns.
 */
function xanh_service_custom_column( $column, $post_id ) {
	switch ( $column ) {
		case 'thumbnail':
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, [ 60, 60 ], [
					'style' => 'width: 60px; height: auto; border-radius: 4px; object-fit: cover;',
				] );
			} else {
				$hero_img = function_exists( 'get_field' ) ? get_field( 'sv_hero_image', $post_id ) : null;
				if ( $hero_img && isset( $hero_img['ID'] ) ) {
					echo wp_get_attachment_image( $hero_img['ID'], [ 60, 60 ], false, [
						'style' => 'width: 60px; height: auto; border-radius: 4px; object-fit: cover;',
					] );
				} else {
					echo '<span style="color: #999;">' . __( 'Chưa có', 'xanh' ) . '</span>';
				}
			}
			break;

		case 'sv_slug':
			$post_obj = get_post( $post_id );
			if ( $post_obj ) {
				echo '<code>' . esc_html( $post_obj->post_name ) . '</code>';
			}
			break;

		case 'sv_hero':
			$hero_title = function_exists( 'get_field' ) ? get_field( 'sv_hero_title', $post_id ) : '';
			if ( $hero_title ) {
				echo esc_html( wp_strip_all_tags( $hero_title ) );
			} else {
				echo '—';
			}
			break;
	}
}
add_action( 'manage_xanh_service_posts_custom_column', 'xanh_service_custom_column', 10, 2 );

/**
 * Adjust column widths with inline CSS for both CPTs.
 */
function xanh_cpt_admin_styles() {
	echo '<style>
		.column-thumbnail { width: 80px; text-align: center; }
		.column-project_type { width: 15%; }
		.column-project_status { width: 15%; }
		.column-project_location { width: 20%; }
		.column-sv_slug { width: 15%; }
		.column-sv_hero { width: 25%; }
	</style>';
}
add_action( 'admin_head-edit.php', 'xanh_cpt_admin_styles' );
