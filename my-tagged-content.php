<?php 


require( dirname( __FILE__ ) . '/mtc-widget.php' );
require ( dirname( __FILE__ ) . '/mtc-classes.php' );


function mtc_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->mtc->id = 'mtc';

	//$bp->example->table_name = $wpdb->base_prefix . 'bp_example';
	//$bp->mtc->format_notification_function = 'bp_mtc_format_notifications';
	$bp->mtc->slug = 'mtc';

	/* Register this in the active components array */
	$bp->active_components[$bp->mtc->slug] = $bp->mtc->id;
}

//add_action( 'bp_setup_globals', 'bp_mtc_setup_globals' );
 
add_action( 'wp', 'mtc_setup_globals', 2 );
add_action( 'admin_menu', 'mtc_setup_globals', 2 );

################## create the Admin dashboard settings ############
/** create WP admin settings ***/
function bp_mtc_menu() {
	global $bp;

	if ( !$bp->loggedin_user->is_super_admin )
		return false;

    require_once( dirname( __FILE__ ) . '/admin/mtc-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'MTC setup', 'bp-mtc' ), __( 'MTC Setup', 'bp-mtc' ), 'manage_options', 'bp-mtc-settings', 'mtc_admin' );
}
add_action( 'admin_menu', 'bp_mtc_menu' );

################### create the navigation ##########################
function mtc_setup_nav() {
	global $bp;

	/* Add 'My Content' to the main user profile navigation */
	bp_core_new_nav_item( array(
		'name' => __( 'My Content', 'bp-mtc' ),
		'slug' => $bp->mtc->slug,
		'position' => 80,
		'screen_function' => 'bp_mtc_screen_one',
		'default_subnav_slug' => 'Content'
	) );

	$mtc_link = $bp->loggedin_user->domain . $bp->mtc->slug . '/';

	/* Create two sub nav items for this component */
	bp_core_new_subnav_item( array(
		'name' => __( 'Content', 'bp-mtc' ),
		'slug' => 'my-content',
		'parent_slug' => $bp->mtc->slug,
		'parent_url' => $mtc_link,
		'screen_function' => 'bp_mtc_screen_one',
		'position' => 10
	) );

	bp_core_new_subnav_item( array(
		'name' => __( 'Select Tags', 'bp-mtc' ),
		'slug' => 'select-tags',
		'parent_slug' => $bp->mtc->slug,
		'parent_url' => $mtc_link,
		'screen_function' => 'bp_mtc_screen_two',
		'position' => 20,
		'user_has_access' => bp_is_my_profile() // Only the logged in user can access this on his/her profile
	) );

	/* Add a nav item for this component under the settings nav item. See bp_example_screen_settings_menu() for more info */
/*	bp_core_new_subnav_item( array(
		'name' => __( 'Example', 'mtc' ),
		'slug' => 'example-admin',
		'parent_slug' => $bp->settings->slug,
		'parent_url' => $bp->loggedin_user->domain . $bp->settings->slug . '/',
		'screen_function' => 'bp_example_screen_settings_menu',
		'position' => 40,
		'user_has_access' => bp_is_my_profile() // Only the logged in user can access this on his/her profile
	) );*/
}

/***
 * In versions of BuddyPress 1.2.2 and newer you will be able to use:
 * add_action( 'bp_setup_nav', 'bp_example_setup_nav' );
 */
//add_action( 'bp_setup_nav', 'mtc_setup_nav' ); 
add_action( 'wp', 'mtc_setup_nav', 2 );
add_action( 'admin_menu', 'mtc_setup_nav', 2 );

########## Setup page templates loading and language files ###########

function bp_mtc_load_template_filter( $found_template, $templates ) {
	global $bp;
 
	if ( $bp->current_component != $bp->mtc->slug )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	return apply_filters( 'bp_mtc_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'bp_mtc_load_template_filter', 10, 2 );

/** Language files setup **/

################## Create screens and content ##########################
function bp_mtc_screen_one() {
do_action( 'bp_mtc_screen_one' );

/*		if ( bp_is_my_profile() ) {
			// Don't let users high five themselves 
			bp_core_add_message( __( 'No self-fives! :)', 'bp-mtc' ), 'error' );
      }
*/

add_action( 'bp_template_title', 'bp_mtc_screen_one_title' );
add_action( 'bp_template_content', 'bp_mtc_screen_one_content' );
bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	
  function bp_mtc_screen_one_title() { ?>
	<?php	_e( 'My tagged content', 'bp-mtc' ); ?>
  <?php
	}

	function bp_mtc_screen_one_content() {
		global $bp, $group, $profile_template, $wpdb ;?>
  <p>The most recent site posts / pages </p>
          
  <!-- RELATED POSTS BY USER PROFILE INTEREST AREAS -->
 <?php if (is_user_logged_in() && xprofile_get_field_data('tagged content' , bp_loggedin_user_id()) ) { ?>
	<?php  //for use in the loop, list 5 post titles related to user profile interest areas
	$backup = $post;  // backup the current object
	//Fetch profile tags from custom field 'Areas of Interest'
	$profileTagFull = xprofile_get_field_data( '11' , bp_loggedin_user_id() );//Fetch the text for the tags
  print_r($profiletagFul);
	$profileargs=array(
		'tag_slug__in' => $profileTagFull,
		'post__not_in' => array($post->ID),
		'showposts'=>5,
		'orderby' => rand,
		'order' => DESC,
		'caller_get_posts'=>1
	);
?>

	
  
		<div class="comma-list">
    <p>Follwing tags:</p>
      		<ul>
				<?php while (list(, $value) = each($profileTagFull)): ?>
        			<li><a href="/tag/<?php echo $value ?>"><?php echo $value ?></a></li>
      			<?php endwhile ?>
     		</ul>
		</div>
    
<div class="widget-wrapper">
	<ul>
  <h4 style="display:run-in;">Content of Interest</h4>
	<?php
	$profile_query = new WP_Query($profileargs);
		if( $profile_query->have_posts() ) {
			while ($profile_query->have_posts()) : $profile_query->the_post(); ?>

				<li><h5><?php $category = get_the_category(); echo $category[0]->cat_name . ': '; ?><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
        <p><?php the_content() ?></p></li>
			<?php endwhile;
		} else { ?>
			<li><a>No related content</a></li>
		<?php }
    $post = $orig_post;
    wp_reset_query();
	?>
	</ul>
</div>

<?php } else { ?>
  
  <p>You have set no content of interest, if you would like to please visit your  <a href="<?php  echo bp_loggedin_user_domain() ?>profile/edit/group/3">Content Interest settings</a>in your profile</p>
 <?php } ?>
  <?php     
    
  }
}

function bp_mtc_screen_two() {

add_action( 'bp_template_title', 'bp_mtc_screen_two_title' );
add_action( 'bp_template_content', 'bp_mtc_screen_two_content' );
add_action( 'bp_template_content_header', 'bp_mtc_screen_settings_menu_header' );
bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );

  function bp_mtc_screen_two_title() { ?>
	<?php	_e( 'Select tagged content to follow', 'bp-mtc' ); ?>
  <?php
	}  
	function bp_mtc_screen_two_content() {
		global $bp, $wpdb, $creds, $profile_template, $groups; 
   ?>
    <p>Please select any tags that you would like to include in your followed content.</p>

<?php if ( bp_has_profile( )) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
<?php if ( 'mtc' == bp_get_the_profile_group_name() ) : ?>
<form action="<?php bp_the_profile_group_edit_form_action() ?>" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug() ?>">


	   <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

			<fieldset<?php bp_field_css_class( 'editfield' ) ?>>

        

					<div class="checkbox">
						<span class="label"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></span>

						<?php bp_the_profile_field_options() ?>
					</div>

				



				<?php do_action( 'bp_custom_profile_edit_fields' ) ?>

				<p class="description"><?php bp_the_profile_field_description() ?></p>
			</fieldset>

		 <?php endwhile; ?>



	<div class="submit">
		<input type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php _e( 'Save selection', 'buddypress' ) ?> " />
	</div>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_group_field_ids() ?>" />
	<?php wp_nonce_field( 'bp_xprofile_edit' ) ?>

</form>
<?php endif; ?>
<?php endwhile; endif; ?>


  <?php /* 
   if ( function_exists('xprofile_get_profile') ) : 
	  if ( bp_has_profile() ) : 

		  while ( bp_profile_groups() ) : bp_the_profile_group(); 
       print_r(bp_get_the_profile_group_name()); 

     endwhile; 
    endif; 
   
  endif; */ ?>
  
  <?php     
    
  }
  function bp_mtc_screen_settings_menu_header() {
  ?><p>this is the header somewhere</p><?php
  }
}
?>