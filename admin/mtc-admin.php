<?php
function mtc_admin() {
	global $bp, $wpdb;

require_once('./admin.php');

wp_reset_vars( array('action', 'tag', 'taxonomy', 'post_type') );

if ( empty($taxonomy) )
	$taxonomy = 'post_tag';

if ( !taxonomy_exists($taxonomy) )
	wp_die(__('Invalid taxonomy'));

$tax = get_taxonomy($taxonomy);

if ( ! current_user_can($tax->cap->manage_terms) )
	wp_die(__('Cheatin&#8217; uh?'));

$title = $tax->labels->name;

if ( empty($post_type) || !in_array( $post_type, get_post_types( array('public' => true) ) ) )
	$post_type = 'post';




  
  
	/* If the form has been submitted and the admin referrer checks out, save the settings */
	if ( isset( $_POST['submit'] ) && check_admin_referer('example-settings') ) {
		update_option( 'example-setting-one', $_POST['example-setting-one'] );
		update_option( 'example-setting-two', $_POST['example-setting-two'] );

		$updated = true;
	}

	$setting_one = get_option( 'example-setting-one' );
	$setting_two = get_option( 'example-setting-two' );
?>
<div class="wrap">
  <div class="container">
  <h2><?php _e( 'The MTC Setup page', 'bp-mtc' ) ?></h2>
  <p>There are no configuration options at this time</p>
        <?php $allTags = get_tags(); 
        print_r($allTags); ?>
        <ul>
         <?php foreach($allTags as $tag) { ?>
         <li><?php print $tag->name ?></li>
         <?php } ?>
        </ul>
        <form id="posts-filter" action="" method="get">
        <input type="hidden" name="taxonomy" value="<?php echo esc_attr($taxonomy); ?>" />
        <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" />
        <div class="tablenav">
        <?php
        $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 0;
        if ( empty($pagenum) )
        	$pagenum = 1;
        
        $tags_per_page = (int) get_user_option( 'edit_' .  $taxonomy . '_per_page' );
        
        if ( empty($tags_per_page) || $tags_per_page < 1 )
        	$tags_per_page = 60;
        
        if ( 'post_tag' == $taxonomy ) {
        	$tags_per_page = apply_filters( 'edit_tags_per_page', $tags_per_page );
        	$tags_per_page = apply_filters( 'tagsperpage', $tags_per_page ); // Old filter
        } elseif ( 'category' == $taxonomy ) {
        	$tags_per_page = apply_filters( 'edit_categories_per_page', $tags_per_page ); // Old filter
        } else {
        	$tags_per_page = apply_filters( 'edit_' . $taxonomy . '_per_page', $tags_per_page );
        }
        
        
        
        $page_links = paginate_links( array(
        	'base' => add_query_arg( 'pagenum', '%#%' ),
        	'format' => '',
        	'prev_text' => __('&laquo;'),
        	'next_text' => __('&raquo;'),
        	'total' => ceil(wp_count_terms($taxonomy, array('search' => $searchterms)) / $tags_per_page),
        	'current' => $pagenum
        ));
        
        if ( $page_links )
        	echo "<div class='tablenav-pages'>$page_links</div>";
        ?>
        
        
        
        
        </div>
        
       
        <table class="widefat tag fixed" cellspacing="0">
        	<thead>
        	<tr>
        <?php print_column_headers($current_screen); ?>
        	</tr>
        	</thead>
        
        	<tfoot>
        	<tr>
        <?php print_column_headers($current_screen, false); ?>
        	</tr>
        	</tfoot>
        
        	<tbody id="the-list" class="list:tag"><tr><td>sgsfsfsfsf</td></tr>
        <?php// tag_rows( $pagenum, $tags_per_page, $searchterms, $taxonomy ); ?>
        	</tbody>
        </table>
        
        <div class="tablenav">
        <?php
        if ( $page_links )
        	echo "<div class='tablenav-pages'>$page_links</div>";
        ?>
        
        
        
        
       </div>
        
        
     </form>

 </div>
</div>
<?php
}
?>