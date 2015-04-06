<?php
/** 
 * @since 1.0
 *
 * Set of helpers function used throughout the plugin
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * @since 1.0 
 * 
 * Says if an event is full
 *
 * Compares the total number of people registered with the max_registration value of the event 
 *
 * @param  (int)  $event_id ID of the event
 * @return (bool) true if event is full, false otherwise
 */
function ersvp_is_full( $event_id ) {
	$max_registrations = get_post_meta( $event_id, 'max_registrations', true );
  	$args = array( 
  		'post_type' => 'ersvp-registration', 
  		'status' => 'publish', 
  		'post_parent' => $event_id, 
  		'posts_per_page' => -1 
  	); 
	$registrations = get_posts( $args );

  	return ! ( count($registrations) < $max_registrations );
}

/**
 * @since 1.0
 *
 * Notify attendeed for each new update about their booking
 *
 * Is used to send an email to attendee upon:
 *   - successfull registration
 *   - successfull registration for waiting list
 *   - cancellation of existing registration
 *
 * @param  (string) $attendee_name   Name of attendee
 * @param  (string) $attendee_email  Email address of attendee
 * @param  (string) $notification    Message to be sent to attendee
 * @return (void)
 */
function ersvp_notify_attendee( $attendee_name, $attendee_email, $notification) {
  
  $ersvp_settings = get_option( 'ersvp-settings ' );
  if( $notification === 'registration' ) {
    $subject =  $jk_events_settings['registration_subject'];
    $message = "{$attendee_name}, \n {$ersvp_settings['registration_message']}";
  } else {
    $subject =  $jk_events_settings['waiting_list_subject'];
    $message = "{$attendee_name}, \n {$ersvp_settings['waiting_list_message']}";
  }

  wp_mail( $attendee_email, $subject, $message, '', '' );
}
