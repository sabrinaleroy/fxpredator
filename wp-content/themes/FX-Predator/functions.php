<?php


/**
** activation theme
**/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'font-style','https://fonts.googleapis.com/css?family=Raleway:200,300,400,600,700,800,900,700italic' );
	
	wp_enqueue_script ( 'map-js','https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array( 'jquery' ));
	wp_enqueue_script ( 'custom-js',get_stylesheet_directory_uri() . '/inc/js/script.js', array( 'jquery' ));
	wp_enqueue_script ( 'masonry-js',get_stylesheet_directory_uri() . '/inc/js/masonry.pkgd.min.js', array( 'jquery' ));

	wp_enqueue_style( 'fancybox-css',get_stylesheet_directory_uri() . '/inc/magnificpopup/magnificpopup.css');
		//wp_enqueue_style( 'fancybox-css',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox.css');
		//wp_enqueue_style( 'fancybox-css',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox-buttons.css');
		//wp_enqueue_style( 'fancybox-css',get_stylesheet_directory_uri() . '/inc/fancybox/jquery-thumbs.fancybox.css');
		wp_enqueue_script ( 'fancybox-js',get_stylesheet_directory_uri() . '/inc/magnificpopup/magnificpopup.js', array( 'jquery' ));
		//wp_enqueue_script ( 'fancybox-js',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox.pack.js', array( 'jquery' ));
		//wp_enqueue_script ( 'fancybox-js',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox-buttons.js', false);
		//wp_enqueue_script ( 'fancybox-js',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox-media.js', false);
		//wp_enqueue_script ( 'fancybox-js',get_stylesheet_directory_uri() . '/inc/fancybox/jquery.fancybox-thumbs.js', false);

}

add_action('transition_post_status', 'check_featured_image_size_after_save', 10, 3);

function admin_css() {

$admin_handle = 'admin_css';
$admin_stylesheet = get_stylesheet_directory_uri() . '/css/admin.css';

wp_enqueue_style( $admin_handle, $admin_stylesheet );
}
add_action('admin_print_styles', 'admin_css', 11 );


function check_featured_image_size_after_save($new_status, $old_status, $post){
  $run_on_statuses = array('publish', 'pending', 'future');
  if(!in_array($new_status, $run_on_statuses))
    return;

  $post_id = $post->ID;
  if ( wp_is_post_revision( $post_id ) )
    return; //not sure about this.. but apparently save is called twice when this happens

  $image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), "Full" );
  if(!$image_data)
    return; //separate message if no image at all. (I use a plugin for this)

  $image_width = $image_data[1];
  $image_height = $image_data[2];

  // replace with your requirements.
  $min_width = 800;
  $min_height = 400;
  if($image_width < $min_width || $image_height < $min_height){
    // Being safe, honestly $old_status shouldn't be in $run_on_statuses... it wouldn't save the first time!
    $reverted_status = in_array($old_status, $run_on_statuses) ? 'draft' : $old_status;
    wp_update_post(array(
      'ID' => $post_id,
      'post_status' => $reverted_status,
    ));
    $back_link = admin_url("post.php?post=$post_id&action=edit");
    wp_die("L'image à la une n'est pas assez grande, elle doit être d'au moins ${min_width}x$min_height pixels. Retourner à '$reverted_status'.<br><br><a href='$back_link'>Go Back</a>");
  }
}

add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
    add_meta_box( 'choisir_inscription', 'Inscriptions', 'cd_meta_box_cb', 'evenement', 'side', 'default' );
}
function cd_meta_box_cb( $post )
{
	global $wpdb;
	// Get existing event details
	$events = $wpdb->get_results("SELECT id, event_name, event_limit, event_expire, event_status FROM ".$wpdb->prefix."seatt_events ORDER BY id DESC");

	$values = get_post_custom( $post->ID );
	$selected = isset( $values['choisir_inscription_select'] ) ? esc_attr( $values['choisir_inscription_select'][0] ) : ”;
	// We'll use this nonce field later on when saving.
    wp_nonce_field( 'choisir_inscription_nonce', 'meta_box_nonce' );
	 ?>
     
    <p>
        <label for="choisir_inscription_select">Formulaire Inscriptions</label><br/>
        <select name="choisir_inscription_select" id="choisir_inscription_select">
        	<option value="0" <?php selected( $selected, $event->id ); ?>>À créer</option>
        <?php
		foreach ($events as $event) {
			$event_expire = ceil($event->event_expire - current_time('timestamp')) / 3600;
			if ($event_expire > 0) {
				?>
				<option value="<?php echo esc_html($event->id); ?>" <?php selected( $selected, $event->id ); ?>><?php echo esc_html($event->event_name); ?></option>
				<?php
			}
		}
		?>
        </select>
    </p>
    <?php    
    echo '<p class="howto" id="choisir_inscription-desc">Choisissez l\'inscription à l\'évènement correspondant</p>';   
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'choisir_inscription_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
     
    if( isset( $_POST['choisir_inscription_select'] ) ){
	    update_post_meta( $post_id, 'choisir_inscription_select', esc_attr( $_POST['choisir_inscription_select'] ) );
    }
        
         
}


/* Load slider */
require get_stylesheet_directory() . '/inc/functions/slider.php';


function custom_evenement_columns_type($columns) {
 $columns['type_evenement'] = 'Types d\'évènement';
 return $columns;
}
add_filter('manage_edit-evenement_columns', 'custom_evenement_columns_type');

function custom_evenement_columns_content($column) {
  global $post;

  switch ($column) {
    case 'type_evenement':
  the_terms ($post,'type_evenement');
    //echo count($cat);
    break;
  }
}
add_action('manage_evenement_posts_custom_column', 'custom_evenement_columns_content');

add_filter('manage_edit-evenement_columns', 'order_evenement_columns');

function order_evenement_columns($evenement_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';

    //$new_columns['id'] = __('ID');
    $new_columns['title'] = _x('Gallery Name', 'column name');

    $new_columns['type_evenement'] = __('Categories');
    $new_columns['tags'] = __('Tags');
    $new_columns['date'] = _x('Date', 'column name');
     $new_columns['wpseo-score'] = __('SEO');
   

    return $new_columns;
}
function sanitize_filename_on_upload($filename) {
$ext = end(explode('.',$filename));
// Replace all weird characters
$sanitized = preg_replace('/[^a-zA-Z0-9-_.]/','', substr($filename, 0, -(strlen($ext)+1)));
// Replace dots inside filename
$sanitized = str_replace('.','-', $sanitized);
return strtolower($sanitized.'.'.$ext);
}

add_filter('sanitize_file_name', 'sanitize_filename_on_upload', 10);


function awaken_widgets_init_override() {
	unregister_sidebar( 'sidebar-1' );
	unregister_sidebar( 'magazine-1' );
	unregister_sidebar( 'magazine-2' );
	register_sidebar( array(
		'name'          => __( 'SideBar Principale', 'fx-predator' ),
		'id'            => 'sidebar-fx',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="widget-title-container"><h2 class="widget-title">',
		'after_title'   => '</h2></div>',
	) );
}
add_action( 'widgets_init', 'awaken_widgets_init_override' );

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyCLA3-s9JVDbsJTCFpG-V_YW9owHPP5hgc');
}
add_action('acf/init', 'my_acf_init');
function my_acf_google_map_api( $api ){
	$api['key'] = 'AIzaSyCLA3-s9JVDbsJTCFpG-V_YW9owHPP5hgc';
	return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
?>
