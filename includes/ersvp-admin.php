<?php
/** 
 * @since 1.0
 *
 * Setup setup menus
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register settings menu
 *
 * Appears as a sub-meu of the Event menu
 *
 * @return (void)
 */
function ersvp_settings_menu() {
	add_submenu_page( 
		'edit.php?post_type=ersvp-events', 
		'Settings', 
		'Settings', 
		'manage_options', 
		'ersvp-settings', 
		'ersvp_display_settings_menu' 
	);
}
add_action('admin_menu', 'ersvp_settings_menu' );

function ersvp_display_settings_menu() {
	?>
	<div class="wrap">'
		<h2 class="dashicons-before dashicons-admin-generic">Events Settings</h2>
			<form method="post" action="options.php">
				<?php do_settings_sections( 'ersvp-settings' ); ?>
				<?php settings_fields( 'ersvp-settings' ); ?>
				<?php submit_button(); ?>
			</form>
	<div class="wrap">
 	<?php 
}

add_action( 'admin_init', 'ersvp_initialize_settings_menu' );
function ersvp_initialize_settings_menu() {

	$ersvp_settings = wp_parse_args( get_option( 'ersvp-settings' ), ersvp_get_default_settings() );

	//Registration Settings
	add_settings_section( 
		'ersvp-settings-registration' , 
		'Registration Successful', 
		'ersvp_settings_registration_section_callback', 
		'ersvp-settings'
	);

	add_settings_field( 
		'ersvp-settings-registration-subject' , 
		'Subject', 
		'ersvp_settings_subject_callback', 
		'ersvp-settings',
		'ersvp-settings-registration',
		array(
			'ersvp-settings-registration-subject',
			$ersvp_settings['registration_subject'],
			'registration_subject'
		)
	);

	add_settings_field( 
		'ersvp-settings-registration-message' , 
		'Message', 
		'ersvp_settings_message_callback', 
		'ersvp-settings',
		'ersvp-settings-registration',
		array(
			'ersvp-settings-registration-message',
			$ersvp_settings['registration_message'],
			'registration_message'
		)
	);

	//Waiting List Settings
	add_settings_section( 
		'ersvp-settings-waiting-list' , 
		'Waiting List', 
		'ersvp_settings_waiting_list_section_callback', 
		'ersvp-settings'
	);

	add_settings_field( 
		'ersvp-settings-waiting-list-subject' , 
		'Subject', 
		'ersvp_settings_subject_callback', 
		'ersvp-settings',
		'ersvp-settings-waiting-list',
		array(
			'ersvp-settings-waiting-list-subject',
			$ersvp_settings['waiting_list_subject'],
			'waiting_list_subject'
		)
	);

	add_settings_field( 
		'ersvp-settings-waiting-list-message' , 
		'Message', 
		'ersvp_settings_message_callback', 
		'ersvp-settings',
		'ersvp-settings-waiting-list',
		array(
			'ersvp-settings-waiting-list-message',
			$ersvp_settings['waiting_list_message'],
			'waiting_list_message'
		)
	);

	register_setting(
		'ersvp-settings',
		'ersvp-settings',
    'ersvp_settings_callback'
	);
}

function ersvp_settings_registration_section_callback( $args ) {
	echo '<p>Email to be sent when users have successfully registered for an event</p>';
}

function ersvp_settings_waiting_list_section_callback( $args ) {
	echo '<p>Email to be sent when users are added on the waiting list (i.e #registered users > Event Max Registration setting</p>';
}

function ersvp_settings_subject_callback( $args ) {
	echo "<input type=\"text\" id=\"{$args[0]}\" class=\"regular-text\" name=\"ersvp-settings[{$args[2]}]\" value=\"{$args[1]}\" >";
}

function ersvp_settings_message_callback( $args ) {
	echo "<textarea rows=\"10\" cols=\"46\" id=\"{$args[0]}\" class=\"large-text-code\" name=\"ersvp-settings[{$args[2]}]\" >{$args[1]}</textarea>";
}

function ersvp_settings_callback( $options ) {
  	$sanitized_options = array();
  	foreach ( $options as $option_key => $option_val ) {
    	$sanitized_options[$option_key] = strip_tags( $option_val );
  	}
	return $sanitized_options;
}

/**
 * Create default settings
 *
 * @return (void)
 */
function ersvp_get_default_settings() {
	return array(
		'registration_subject' => 'Registration Subject',
		'registration_message' => 'Registration Message',
		'waiting_list_subject' => 'Waiting List Subject',
		'waiting_list_message' => 'Waiting List Message'
	);
}