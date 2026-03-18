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
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme constants.
 */
define( 'XANH_THEME_VERSION', '1.0.0' );
define( 'XANH_THEME_DIR', get_template_directory() );
define( 'XANH_THEME_URI', get_template_directory_uri() );

/**
 * Required inc/ files.
 *
 * Load order matters — theme-setup first (add_theme_support),
 * then CPTs (before ACF), then ACF, then helpers, then enqueue last.
 */
$xanh_inc_files = [
	'inc/theme-setup.php',       // add_theme_support, menus, image sizes, WP bloat removal.
	'inc/cpt-registration.php',  // Custom Post Types + Taxonomies.
	'inc/acf-fields.php',        // ACF Options Page + field helpers.
	'inc/custom-functions.php',  // xanh_get_*() data helpers.
	'inc/template-tags.php',     // Reusable template render functions.
	'inc/ajax-handlers.php',     // AJAX endpoints (filter, load more).
	'inc/enqueue.php',           // Conditional CSS/JS loading.
];

foreach ( $xanh_inc_files as $xanh_file ) {
	$xanh_filepath = XANH_THEME_DIR . '/' . $xanh_file;
	if ( file_exists( $xanh_filepath ) ) {
		require_once $xanh_filepath;
	}
}
