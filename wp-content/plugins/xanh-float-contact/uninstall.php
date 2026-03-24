<?php
/**
 * Uninstall — Remove all plugin options on deletion.
 *
 * @package XanhFloatContact
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$xfc_options = [
	'xfc_enabled',
	'xfc_phone',
	'xfc_phone_label',
	'xfc_zalo',
	'xfc_zalo_label',
	'xfc_messenger',
	'xfc_messenger_label',
	'xfc_email',
	'xfc_email_label',
	'xfc_position',
	'xfc_color',
];

foreach ( $xfc_options as $option ) {
	delete_option( $option );
}
