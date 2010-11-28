<?php
/*
Plugin Name: Tag my favourit content
Description: Select your favourite content to follow via post tags
Author: Hugo - aka hnla
Author URI: http://buddypress.org/developers/hnla
Plugin URI: http://buddypress.org/groups/tag-my-favourit-content
Version: 1.0

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/

*/


/* Only load the plugin if BP is loaded and initialized. */
function bp_tag_my_fav_content_init() {
	require( dirname( __FILE__ ) . '/my-tagged-content.php' );
}
add_action( 'bp_init', 'bp_tag_my_fav_content_init' );
?>