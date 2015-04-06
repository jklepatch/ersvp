<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   jk-events
 * @author    Julien Klepatch
 * @license   GPL-2.0+
 * @link      http://julienklepatch.com
 * @copyright 2015 julienklepatch.com
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
}

// global $wpdb;
// $DUP_Settings = new DUP_Settings();

// $table_name = $wpdb->prefix . "duplicator_packages";
// $wpdb->query("DROP TABLE `{$table_name}`");

// delete_option('duplicator_version_plugin');

// //Remvoe entire wp-snapshots directory
// if (DUP_Settings::Get('uninstall_files')) {

// 	$ssdir = DUP_Util::SafePath(DUPLICATOR_SSDIR_PATH);
// 	$ssdir_tmp = DUP_Util::SafePath(DUPLICATOR_SSDIR_PATH_TMP);

// 	//PHP sanity check
// 	foreach (glob("{$ssdir}/*_database.sql") as $file) {
// 		if (strstr($file, '_database.sql'))
// 			@unlink("{$file}");
// 	}

// }

// //Remove all Settings
// if (DUP_Settings::Get('uninstall_settings')) {
// 	DUP_Settings::Delete();
// 	delete_option('duplicator_ui_view_state');
// 	delete_option('duplicator_package_active');
// }
// ?>