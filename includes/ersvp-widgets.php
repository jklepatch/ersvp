<?php
/** 
 * @since 1.0
 *
 * Register widgets
 */

/**
 * Register the ersvp-widget
 *
 * @return (void)
 */
function ersvp_register_widgets() {
    register_widget( 'ersvp_widget' );
}
add_action( 'widgets_init', 'ersvp_register_widgets' );

/**
 * @since 1.0 
 *
 * Widget Class for displaying front-end registration form
 */
class ersvp_widget extends WP_Widget {

    //called when instantiated
    function ersvp_widget() {
        $widget_ops = array(
            'classname' => 'ersvp_widget_class',
            'description' => 'Register for an event.'
        );
        $this->WP_Widget( 'ersvp_widget', 'Events Registration', $widget_ops );

        if ( ! is_admin() && is_active_widget(false, false, $this->id_base) ) {
            add_action( 'wp_enqueue_scripts', array(&$this, 'ersvp_registration_form_style') );
        }
    }

    function ersvp_registration_form_style() {
    	wp_enqueue_style( 'ersvp-widget-style', ERSVP_PLUGIN_URL . '/css/ersvp-registration-form-style.css' );
    }

    //display form in widget menu
    function form( $instance ) {
        $defaults = array(
            'title' => 'Event Registration',
            'event_id' => 0,
        );
        $instance = wp_parse_args( (array) $instance, $defaults);

        $args     = array( 
        	'post_type' => 'ersvp-events', 
        	'status' => 'publish' 
        );
		$events = get_posts( $args ); 
		?>

        <p>Title: 
            <input class="" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
    		Event:
            <select id="event_id" name="<?php echo $this->get_field_name( 'event_id' ); ?>">
	            <?php foreach( $events as $event ) : ?>
	            	<option value="<?php echo $event->ID; ?>" <?php selected( $instance['event_id'], $event->ID );?>><?php echo $event->post_title; ?></option>
	            <?php endforeach; ?>
           </select>
        </p>

        <?php
    }
    
    //sanitize and save the widget settings
    function update( $new_instance, $old_instance ) {
        $instance             = $old_instance;
        $instance['title']    = strip_tags( $new_instance['title'] );
        $instance['event_id'] = strip_tags( $new_instance['event_id'] );

        return $instance;
    }

    //display the widget
    function widget($args, $instance) {
        extract($args);
        extract($instance);

        echo $before_widget;

        //load the widget settings
        $title = apply_filters( 'widget_title', $instance['title'] );
        if ( !empty( $title ) ) { 
            echo $before_title . $title . $after_title; 
        };

        //Display form or feedback message
        if ( $event_id ) {	
	    	//Sanitize and retrieve feedback information if any
			$registration_result = isset( $_GET['registration_result'] ) ? strip_tags( $_GET['registration_result'] ): false; 
	    	$registration_name   = isset( $_GET['registration_name'] )   ? strip_tags( $_GET['registration_name'] ) : ''; 

	    	?>

	    	<?php if( 'done' === $registration_result ) : ?>
	        	<div class="ersvp-alert ersvp-alert-success">
	        		<p><?php echo esc_html( $registration_name ); ?>, you have successfully registered to this event</p>
				</div>

	        <?php elseif( 'waiting_list' === $registration_result ) : ?>
	            <div class="ersvp-alert ersvp-alert-info">
	        		<p><?php echo esc_html( $registration_name ); ?>, you have successfully registered to the waiting list of this event</p>
	            </div>

	        <?php elseif( false === $registration_result ) : ?>
	    		<?php if( ersvp_is_full( $event_id ) ) : ?>
	         		<div class="ersvp-alert ersvp-alert-info"> 
	        			<p>The event is full, but you can still register for the the waiting list</p>
	          		</div>
	          	<?php endif; ?>
	    		<form id="ersvp-events-registration-form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
	            	<input type="hidden" name="event_id" value="<?php echo $event_id; ?>" >
	            	<input type="hidden" name="action" value="ersvp_submit_registration">
	          		<?php wp_nonce_field( 'ersvp_submit_registration', 'ersvp_submit_registration_nonce' ); ?>
	            	<div>
	            		<input type="text" name="name" placeholder="Your Name*" required>
	            	</div>
	            	<div>
	            		<input type="email" name="email" placeholder="Your Email*" required >
	            	</div>
	            	<div>
	            		<input type="number" name="phone" placeholder="Your Phone*" required >
	            	</div>
	            	<div>
		            	<textarea rows="10" name="additional_info" placeholder="Additional Information (Optional)"></textarea>
		            </div>
	            	<div>
	            		<input type="checkbox" name="rules" style="display: inline-block;" required>
	            		<div style="display: inline-block;">
	            			I agree with the rules of the event
	            		</div>
	            	</div>
	            	<div>
	            		<input type="submit" name="submit_registation" value="Submit Registration">
	            	</div>
	            	<p><small>*Required Fields</small></p>
	        	</form>
			<?php endif; ?>
			<?php
        }
        echo $after_widget;
    }
}