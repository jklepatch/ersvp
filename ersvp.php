<?php
/*
Plugin Name: Events RSVP
Plugin URI: github.com/jklepatch/ersvp
Author: Julien Klepatch
Author URI: http://julienklepatch.com
Description: Easily create an RSVP form and manage registrations
Version: 1.0
*/

/*  Copyright 2015 Julien Klepatch.  (email : julien@julienklepatch.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( ERSVP_PLUGIN_PATH, __FILE__  );
define( ERSVP_PLUGIN_URL, plugin_dir_url( __FILE__  ) );

require_once 'includes/ersvp-helpers.php';
require_once 'includes/ersvp-custom-post-types.php';
require_once 'includes/ersvp-widgets.php';
if( is_admin() ) {
  	require_once 'includes/ersvp-admin.php';
  	require_once 'includes/ersvp-new-registration.php';
} else {
  	//require_once 'includes/ersvp-new-registration.php';
}


add_action( 'init', 'ersvp_create_event_post_type' ); //temorarily here for testing
add_action( 'init', 'ersvp_create_registration_post_type' ); //temorarily here for testing

register_activation_hook( __FILE__, 'ersvp_activate' );
function ersvp_activate() {
  //ersvp_create_event_post_type();
  //ersvp_create_registration_post_type();
  flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'ersvp_deactivate' );
function ersvp_deactivate() {
  flush_rewrite_rules();
}

