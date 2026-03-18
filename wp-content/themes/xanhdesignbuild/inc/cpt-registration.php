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

	// ═══ xanh_testimonial — Chứng Thực ═══
	register_post_type( 'xanh_testimonial', [
		'labels'       => [
			'name'               => __( 'Chứng Thực', 'xanh' ),
			'singular_name'      => __( 'Chứng Thực', 'xanh' ),
			'add_new'            => __( 'Thêm Chứng Thực', 'xanh' ),
			'add_new_item'       => __( 'Thêm Chứng Thực Mới', 'xanh' ),
			'edit_item'          => __( 'Sửa Chứng Thực', 'xanh' ),
			'all_items'          => __( 'Tất cả Chứng Thực', 'xanh' ),
		],
		'public'       => true,
		'has_archive'  => false,
		'rewrite'      => [ 'slug' => 'chung-thuc', 'with_front' => false ],
		'menu_icon'    => 'dashicons-format-quote',
		'supports'     => [ 'title', 'thumbnail' ],
		'show_in_rest' => true,
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
