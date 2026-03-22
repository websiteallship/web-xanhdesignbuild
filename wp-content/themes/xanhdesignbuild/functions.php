<?php
/**
 * XANH Design & Build — Theme Functions
 *
 * Constants, autoloader cho inc/ files, và global filters.
 *
 * @package XanhDesignBuild
 * @since   1.0.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme constants.
 */
define('XANH_THEME_VERSION', '1.0.0');
define('XANH_THEME_DIR', get_template_directory());
define('XANH_THEME_URI', get_template_directory_uri());

/**
 * Required inc/ files.
 *
 * Load order matters — theme-setup first (add_theme_support),
 * then CPTs (before ACF), then ACF, then helpers, then enqueue last.
 */
$xanh_inc_files = [
	'inc/class-xanh-nav-walker.php', // Custom Nav Walker (load before templates).
	'inc/theme-setup.php', // add_theme_support, menus, image sizes, WP bloat removal.
	'inc/cpt-registration.php', // Custom Post Types + Taxonomies.
	'inc/acf-fields.php', // ACF Options Page + field helpers.
	'inc/custom-functions.php', // xanh_get_*() data helpers.
	'inc/template-tags.php', // Reusable template render functions.
	'inc/ajax-handlers.php', // AJAX endpoints (filter, load more).
	'inc/enqueue.php', // Conditional CSS/JS loading.
	'inc/admin-tweaks.php', // Admin dashboard customizations (thumbnail columns).
];

foreach ($xanh_inc_files as $xanh_file) {
	$xanh_filepath = XANH_THEME_DIR . '/' . $xanh_file;
	if (file_exists($xanh_filepath)) {
		require_once $xanh_filepath;
	}
}

/**
 * Ẩn editor trên Trang chủ và trang About.
 *
 * Các trang này render nội dung hoàn toàn qua template parts / ACF,
 * nên editor chỉ gây rối mà không có tác dụng.
 */
add_action('admin_init', function () {
	// Lấy post ID đang edit trong admin.
	$post_id = isset($_GET['post']) ? (int)$_GET['post'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if (!$post_id) {
		return;
	}

	// Trang chủ (front page).
	$front_page_id = (int)get_option('page_on_front');

	if ($post_id === $front_page_id) {
		remove_post_type_support('page', 'editor');
		return;
	}

	// Trang About (template "Giới Thiệu" = page-about.php).
	$template = get_post_meta($post_id, '_wp_page_template', true);

	if ('page-about.php' === $template) {
		remove_post_type_support('page', 'editor');
	}
});

/**
 * Add body classes based on the presence of a hero banner.
 *
 * This helps style the transparent header properly on pages that don't have a hero image,
 * by forcing it to have a solid background (xanh-no-hero).
 */
add_filter('body_class', function ($classes) {
	$has_hero = false;

	// Check typical templates that include a hero banner.
	if (
	is_front_page() ||
	is_home() ||
	is_page_template('page-about.php') ||
	is_page_template('page-contact.php') ||
	is_post_type_archive('xanh_project') ||
	is_post_type_archive('xanh_service') ||
	is_singular('xanh_project') ||
	is_singular('xanh_service') ||
	is_tax('project_type')
	) {
		$has_hero = true;
	}

	if (!$has_hero) {
		$classes[] = 'xanh-no-hero';
	}
	else {
		$classes[] = 'xanh-has-hero';
	}

	return $classes;
});


// Hook vào wp_loaded hoặc init để đảm bảo plugin đã được load xong
add_action('wp_loaded', function () {
	// Chỉ chạy nếu có tham số ?test_xanh_api=1 trên URL
	if (!isset($_GET['test_xanh_api']) || !current_user_can('manage_options')) {
		return;
	}

	if (!class_exists('Xanh_AI_API')) {
		wp_die('Plugin XANH AI chưa được kích hoạt hoặc class chưa được load.');
	}

	$api = new Xanh_AI_API();

	// 1. Test Kết Nối (sử dụng key đã lưu trong Cài Đặt)
	// Bạn cũng có thể test với key cụ thể: $api->test_connection( 'YOUR_API_KEY' )
	$test_result = $api->test_connection();

	echo '<h2>1. Test trạng thái kết nối</h2>';
	if (is_wp_error($test_result)) {
		echo '<p style="color:red;">❌ Lỗi kết nối: ' . $test_result->get_error_message() . '</p>';
	}
	else {
		echo '<p style="color:green;">✅ Kết nối thành công! Đang sử dụng model: ' . $test_result['model'] . '</p>';
	}

	// 2. Test Tạo Nội Dung (Text Generate)
	echo '<h2>2. Test gửi prompt tạo nội dung</h2>';
	$prompt = 'Hãy viết 1 câu slogan ngắn cho công ty XANH Design & Build.';

	$generate_result = $api->generate_text($prompt);

	if (is_wp_error($generate_result)) {
		echo '<p style="color:red;">❌ Lỗi tạo nội dung: ' . $generate_result->get_error_message() . '</p>';
	}
	else {
		echo '<p style="color:green;">✅ Tạo nội dung thành công!</p>';
		echo '<ul>';
		echo '<li><strong>Số token sử dụng:</strong> ' . $generate_result['_tokens'] . '</li>';
		echo '<li><strong>Nội dung trả về:</strong><br><pre>' . print_r($generate_result, true) . '</pre></li>';
		echo '</ul>';
	}

	exit; // Dừng load trang để chỉ xem kết quả
});
