<?php
/**
 * @since 1.0
 *
 * Handles registration request coming from front-end form
 *
 * Currently the plugin only support POST Request, but a
 * future release might support AJAX request as well
 *
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.0 
 *
 * Handles registration request coming from front-end form
 *
 * If the event is not full, create a new registration
 * custom post type whose parent is the event custom post type
 * ($event_id passed as a hidden field by the form)
 *
 * Redirect the user to the pages that originated the request with
 * feedback information
 *
 * @return  (void)
 *  */
function ersvp_submit_registration() {
	
	//If the right $_POST['action'] is not setup or if the nounce is inexistent or wrong, exit
 	if( ! isset( $_POST['action'] ) || $_POST['action'] <> 'ersvp_submit_registration' ) {
 	  return;
 	}	
 	if( ! isset( $_POST['ersvp_submit_registration_nonce'] ) || ! wp_verify_nonce( $_POST['ersvp_submit_registration_nonce'], 'ersvp_submit_registration' ) ) {
 	  return;
 	}

 	//Sanitize form inputs
  	$name            = isset( $_POST['name'] )            ? sanitize_text_field( ( $_POST['name'] ) )            : '';
  	$email           = isset( $_POST['email'] )           ? sanitize_email( ( $_POST['email'] ) )                : '';
  	$phone           = isset( $_POST['phone'] )           ? sanitize_text_field( ( $_POST['phone'] ) )           : '';
  	$additional_info = isset( $_POST['additional_info'] ) ? sanitize_text_field( ( $_POST['additional_info'] ) ) : '';
  	$event_id        = isset( $_POST['event_id'] )        ? abs( ( $_POST['event_id'] ) )                        : '';

    //Check if event is not full
	if( ! ersvp_is_full( $event_id ) ) {
		$waiting_list = 'no';
		$result       = 'done';
		ersvp_notify_attendee(  $name, $email, 'registration' );
	} else {
		$waiting_list = 'yes';
		$result       = 'waiting_list';
		ersvp_notify_attendee(  $name, $email, 'waiting_list' );
	}

	//Create a new registration (can be normal registration or on the waiting list)
   	$post = array(
	    'post_title'     => 'ersvp-registration-' . sha1( $name ), //Create unique title. is it necessary ?
	    'post_status'    => 'publish',
	    'post_type'      => 'ersvp-registration',
	    'post_parent'    => $event_id, 
	    'post_date'      => date( 'Y-m-d H:i:s', time() )
	);  
	$post_id = wp_insert_post( $post );

	//Attach additional information to new registration with custom fields
	add_post_meta( $post_id, 'email'           , $email );
	add_post_meta( $post_id, 'name'            , $name );
	add_post_meta( $post_id, 'phone'           , $phone );
	add_post_meta( $post_id, 'additional_info' , $additional_info );
	add_post_meta( $post_id, 'waiting_list'    , $waiting_list );

	//Redirect the user to the home page. use wp_safe_redirect() in case a malicious script set HTTP_REFERER to a dangerous location
    wp_safe_redirect( $_SERVER['HTTP_REFERER'] . "?registration_name={$name}&registration_result={$result}", 302 );
	exit;
}
add_action( 'init', 'ersvp_submit_registration' );