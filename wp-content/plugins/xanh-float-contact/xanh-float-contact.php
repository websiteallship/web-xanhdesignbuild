<?php
/**
 * Plugin Name: XANH Float Contact
 * Plugin URI:  https://xanhdesignbuild.vn
 * Description: Floating contact button (FAB) — hiển thị nút liên hệ nhanh (Hotline, Zalo, Messenger, Email) cố định góc phải dưới mọi trang.
 * Version:     1.0.0
 * Author:      XANH Design & Build
 * Author URI:  https://xanhdesignbuild.vn
 * Text Domain: xanh-float-contact
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License:     GPL-2.0-or-later
 *
 * @package XanhFloatContact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Constants ── */
define( 'XFC_VERSION', '1.0.0' );
define( 'XFC_PLUGIN_FILE', __FILE__ );
define( 'XFC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'XFC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* ── Includes ── */
require_once XFC_PLUGIN_DIR . 'includes/class-settings.php';
require_once XFC_PLUGIN_DIR . 'includes/class-frontend.php';

/* ── Init ── */
add_action( 'plugins_loaded', 'xfc_init' );

/**
 * Bootstrap the plugin.
 */
function xfc_init() {
	// Admin settings page.
	if ( is_admin() ) {
		new XFC_Settings();
	}

	// Frontend widget.
	new XFC_Frontend();
}

/* ── Activation — set defaults ── */
register_activation_hook( __FILE__, 'xfc_activate' );

function xfc_activate() {
	$defaults = [
		'xfc_enabled'       => '1',
		'xfc_phone'         => '',
		'xfc_zalo'          => '',
		'xfc_messenger'     => '',
		'xfc_email'         => '',
		'xfc_position'      => 'bottom-right',
		'xfc_color'         => '#FF8A00',
		'xfc_phone_label'   => 'Gọi ngay',
		'xfc_zalo_label'    => 'Chat Zalo',
		'xfc_messenger_label' => 'Messenger',
		'xfc_email_label'   => 'Gửi Email',
	];

	foreach ( $defaults as $key => $value ) {
		if ( false === get_option( $key ) ) {
			add_option( $key, $value );
		}
	}
}

/* ── Deactivation ── */
register_deactivation_hook( __FILE__, 'xfc_deactivate' );

function xfc_deactivate() {
	// Nothing to do — keep settings for reactivation.
}
