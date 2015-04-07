<?php
/**
 * @since 1.0
 *
 * Register all custom post types
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.0 
 *
 * Create the event post type
 *
 * An event post type is created after an admin create a new event
 * 
 * @return (void)
 */
function ersvp_create_event_post_type() {

	// Set UI labels for Custom Post Type
	$labels = array(
	    'name'                => 'Events',
	    'singular_name'       => 'Event',
	    'menu_name'           => 'Events',
	    'all_items'           => 'All Events',
	    'view_item'           => 'View Event', 
	    'add_new_item'        => 'Add New Event',
	    'add_new'             => 'Add New',
	    'edit_item'           => 'Edit Event',
	    'update_item'         => 'Update Event',
	    'search_items'        => 'Search Event',
	    'not_found'           => 'Not Found', 
	    'not_found_in_trash'  => 'Not found in Trash'
	);
  
	// Set other options for Custom Post Type
	$args = array(
	    'label'               => 'ersvp-events',
	    'description'         => 'Events',
	    'labels'              => $labels,
	    'supports'            => array( 'title' ),
	    'hierarchical'        => false,
	    'public'              => true,
	    'show_in_menu'        => true,
	    'menu_position'       => 5,
	    'rewrite'             => array( 'slug' => 'events' ),
	    'has_archive'         => false,
	    'capability_type'     => 'post',
	    'menu_icon'           => 'dashicons-calendar-alt',
	    'register_meta_box_cb' => 'ersvp_add_metaboxes'
	);
  
	// Registering your Custom Post Type
	register_post_type( 'ersvp-events', $args );
}

/**
 * @since 1.0
 * 
 * Register all metaboxes for `events` custom post type
 *
 * @return (void)
 */
function ersvp_add_metaboxes(){
	 add_meta_box(
	 	'ersvp-events-dates-metabox', 
	 	'Date', 
	 	'ersvp_add_dates_metabox', 
	 	'ersvp-events', 
	 	'side', 
	 	'high'
	 );

	 add_meta_box(
	 	'ersvp-events-max-registratons-metabox', 
	 	'Max Registrations', 
	 	'ersvp_add_max_registrations_metabox', 
	 	'ersvp-events', 
	 	'side', 
	 	'high'
	 );

	 add_meta_box(
	 	'ersvp-events-registrations-metabox', 
	 	'Registrations', 
	 	'ersvp_add_registrations_metabox', 
	 	'ersvp-events', 
	 	'normal', 
	 	'high'
	 );
}

/**
 * @since 1.0
 * 
 * Display date metabox for `events` custom post type
 *
 * @return (void)
 */
function ersvp_add_dates_metabox() {
	global $post;
	$date = ( get_post_meta($post->ID, 'date', true ) ) ? get_post_meta($post->ID, 'date', true ) : '';
	echo "<input name=\"date\" type=\"date\" value=\"{$date}\">";
}

/**
 * @since 1.0
 * 
 * Display max registration metabox for `events` custom post type
 *
 * @return (void)
 */
function ersvp_add_max_registrations_metabox() {
	global $post;
	$max_registrations = ( get_post_meta( $post->ID, 'max_registrations', true ) ) ? get_post_meta( $post->ID, 'max_registrations', true ) : '';
	echo "<input name=\"max_registrations\" type=\"number\" value=\"{$max_registrations}\">";
}

/**
 * @since 1.0
 * 
 * Display registration metabox for `events` custom post type
 *
 * This is the most important metabox.
 * It shows a list of all the registration attached to the event.
 * Waiting list registration can be seen with a gray background
 *
 * @return (void)
 */
function ersvp_add_registrations_metabox() {

	//Get all registrations attached to the event
	global $post;
	$args = array( 
		'post_type'      => 'ersvp-registration', 
		'status'         => 'publish', 
		'post_parent'    => $post->ID, 
		'posts_per_page' => -1 
	); 
	$registrations = get_posts( $args );

  	//Display some styling for the below table
	echo '<style>.ersvp-waiting-list{ background-color: #C4C4C4; } thead th.ersvp-th{ padding-left: 10px; } .form-table td {word-break: break-all; }</style>';

	//Display the list of registration attached to the event in a table
	echo '<table class="form-table widefat">';
	echo '<thead><tr><th scope="col" class="ersvp-th">Delete</th><th scope="col" class="ersvp-th">Name</th><th scope="col" class="ersvp-th">Email</th><th scope="col" class="ersvp-th">Phone</th><th scope="col" class="ersvp-th">Additional info</th><th scope="col" class="ersvp-th">Date</th><th scope="col" class="ersvp-th">Waiting List</th></tr></thead>';
	echo '<tbody>';
		$i = 1;
		$date_format = get_option('date_format');
		foreach( $registrations as $registration ) {

      		//Get the data for each registration
      		$post_meta = get_post_meta( $registration->ID );

      		//prepare classes and some html fragments
			$waiting_list_class  = ( 'yes' === $post_meta['waiting_list'][0] ) ? 'ersvp-waiting-list' : '';
			$alternate_class     = ( $i % 2 === 0 ) ? '' : 'alternate';
      		$checkbox            = "<input id=\"cb_{$registration->ID}\" type=\"checkbox\" name=\"cb_{$registration->ID}\" value=\"cb_{$registration->ID}\">";

      		//Display the row will registration information
			printf("<tr class=\"%s\"><th scope=\"row\" class=\"check-column\">%s</th><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", esc_attr( $alternate_class . ' ' . $waiting_list_class ), $checkbox, esc_html( $post_meta['name'][0] ), esc_html( $post_meta['email'][0] ), esc_html( $post_meta['phone'][0] ), esc_html( $post_meta['additional_info'][0] ),  esc_html( mysql2date( $date_format, $registration->post_date ) ),  esc_html( $post_meta['waiting_list'][0] ) );
			++$i;
		}

	echo '</tbody></table>';
}

/**
 * @since 1.0
 *
 * Update or save a ersvp custom post type 
 * 
 * @param  (int) $post_id ID of the post to be saved/updated
 * @return (void)
 */
function ersvp_save( $post_id ) {

	// If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) )
		return;

	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	//Get new data
  	$date                  = isset( $_POST['date'] )              ? sanitize_text_field( $_POST['date'] )  : '';
  	$max_registrations     = isset( $_POST['max_registrations'] ) ? abs( $_POST['max_registrations'] )     : '';

  	//Get old max registration
  	$old_max_registrations = get_post_meta( $post_id, 'max_registrations', true );
	
  	//Get registrations attached to this post
  	$args = array( 
  		'post_type' => 'ersvp-registration', 
  		'status'    => 'publish', 'post_parent' => $post_id, 
  		'order'     => 'ASC', 'posts_per_page' => -1  
  	); 
  	$registrations = get_posts( $args );
	
  	//Delete post checked for deletion
  	$was_a_deletion = false;
  	foreach( $registrations as $key => $registration ) {
  	  	$registration_ID = $registration->ID;
  	  	if( true == $_POST['cb_' . $registration_ID] ) {
  	  	  	wp_delete_post( $registration_ID, true );
  	  	  	unset( $registrations[ $key ] );
  	  	  	$was_a_deletion = true;
  	  	}
 	}

  	//Update waiting_list field (if registration data changed, need to update it) and notify status change to attendee
	if( ersvp_has_registration_data_changed( $was_a_deletion, $old_max_registrations, $max_registrations ) ) {
		$i = 0;
		foreach( $registrations as $registration ) {
      		$waiting_list = get_post_meta( $registration->ID, 'waiting_list', true );

      		//There is still room and attendee is on the waiting list, need to switch to registered for the event
      		if( $i < $max_registrations && $waiting_list === 'yes' ) {
  		    	$name  = get_post_meta( $registration->ID, 'name', true );
  		    	$email = get_post_meta( $registration->ID, 'email', true );
				update_post_meta( $registration->ID, 'waiting_list', 'no' );
  		    	ersvp_notify_attendee( $name, $email, 'registration' );

  		    //Event is full and attendee is registered, need to switch him/her to waiting list
			} elseif( $i >= $max_registrations && $waiting_list === 'no' ) {
  		    	$name  = get_post_meta( $registration->ID, 'name', true );
  		    	$email = get_post_meta( $registration->ID, 'email', true );
  		    	update_post_meta( $registration->ID, 'waiting_list', 'yes' );
  		    	ersvp_notify_attendee( $name, $email, 'waiting_list' );
			}
			$i = $i + 1;
		}
	}

	update_post_meta($post_id, 'date', $date );
	update_post_meta($post_id, 'max_registrations', $max_registrations );
}
add_action( 'save_post', 'ersvp_save' );

/**
 * @since 1.0
 *
 * Helper function to determine if a registration status has changed
 *
 * If it returns true, other functions use this information to
 * send a notification email to the attendee and to re-calculate
 * the waiting list (e.g if someone cancelled its registration there
 * is a seat available for the next person on the waiting list) 
 *
 * @param  (bool) $was_a_deletion         Whether someone cancelled its registration
 * @param  (int)  $old_max_registrations  old value of max registration
 * @param  (int) $max_registrations       current value of max registration (will be same as above if no change)
 * @return (bool)                         true if registration status hasn't change, false otherwise
 */
function ersvp_has_registration_data_changed( $was_a_deletion, $old_max_registrations, $max_registrations ) {
  	if( $was_a_deletion === true ) {
    	return true;
  	}
  	if( $max_registrations <> '' && (bool) $old_max_registrations == true &&  $max_registrations <> $old_max_registrations ) {
    	return true;
  	}

  	return false;
}


/**
 * @since 1.0 
 *
 * Create the custom Event Registration custom post type
 *
 * Each time a user register for an event, it creates
 * one a new registration. 
 * Each Registration belongs to an Event. The link
 * is done with the event id
 *
 * @return (void)
 */
function ersvp_create_registration_post_type() {
  
  // Set other options for Custom Post Type
  $args = array(
      'description'         => 'Event registration',
      'hierarchical'        => false, 
      'capability_type'     => 'post',
      'show_in_menu'        => false
  );
  
  // Registering your Custom Post Type
  register_post_type( 'ersvp-registration', $args );
}