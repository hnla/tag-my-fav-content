<?php


function bp_register_mtc_widget() {
	add_action('widgets_init', create_function('', 'return register_widget("BP_mtc_Widget");') );
}
add_action( 'plugins_loaded', 'bp_register_mtc_widget' );

class BP_mtc_Widget extends WP_Widget {

	function bp_mtc_widget() {
		parent::WP_Widget( false, $name = __( 'MTC Widget', 'buddypress' ) );
	}

	function widget( $args, $instance ) {
		global $bp;

		extract( $args );

		echo $before_widget;
		echo $before_title .
			 $widget_name .
		     $after_title; ?>
<p>MTC widget display</p>
	<?php  
   
	/***
	 * This is where you add your HTML and render what you want your widget to display.
	 */

	?>

	<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* This is where you update options for this widget */

		$instance['max_items'] = strip_tags( $new_instance['max_items'] );
		$instance['per_page'] = strip_tags( $new_instance['per_page'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'max_items' => 200, 'per_page' => 25 ) );
		$per_page = strip_tags( $instance['per_page'] );
		$max_items = strip_tags( $instance['max_items'] );
		?>

		<p><label for="bp-mtc-per-page"><?php _e( 'Number of Items Per Page:', 'bp-mtc' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'per_page' ); ?>" name="<?php echo $this->get_field_name( 'per_page' ); ?>" type="text" value="<?php echo attribute_escape( $per_page ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-mtc-max"><?php _e( 'Max items to show:', 'bp-mtc' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="text" value="<?php echo attribute_escape( $max_items ); ?>" style="width: 30%" /></label></p>
	<?php
	}
}

?>