<?php
// Facebook Custom Post Type With Class

if ( ! class_exists( 'Fb_Likebox_Shortcode_CPT' ) ) {

	class Fb_Likebox_Shortcode_CPT {
		
		protected $protected_plugin_api;
		protected $ajax_plugin_nonce;
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}
		
		protected function _constants() {
			//Plugin Version
			define( 'FLWS_PLUGIN_VER', '0.5.8' );
			
			//Plugin Text Domain
			define("FLB_TXTDM","facebook-likebox-widget-and-shortcode" );

			//Plugin Name
			define( 'FLWS_PLUGIN_NAME', __( 'Image Gallery', FLB_TXTDM ) );

			//Plugin Slug
			define( 'FLWS_PLUGIN_SLUG', 'fblb_shortcode_cpt' );

			//Plugin Directory Path
			define( 'FLWS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			//Plugin Directory URL
			define( 'FLWS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			
		} // end of constructor function		
		
		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, '_load_textdomain' ) );
			
			//add FBLB menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, '_fb_likebox_doc_menu' ), 101 );
			
			//Create FBLB Custom Post
			add_action( 'init', array( $this, '_facebook_likebox_widget_shortcode' ));
			
			//Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, '_admin_add_meta_box' ) );
			 
			//loaded during admin init 
			add_action( 'admin_init', array( $this, '_admin_add_meta_box' ) );

			// save post and meta settings
			add_action('save_post', array(&$this, '_fb_save_settings'));

			//Shortcode Compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');
			
			// add pfg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_fblb_shortcode_cpt_posts_columns', array(&$this, 'set_fblb_shortcode_cpt_shortcode_column_name') );
			
			// add pfg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_fblb_shortcode_cpt_posts_custom_column' , array(&$this, 'custom_fblb_shortcode_cpt_shodrcode_data'), 10, 2 );

		} // end of hook function	

		// Facebook Like Share table cpt shortcode column before date columns
		public function set_fblb_shortcode_cpt_shortcode_column_name($defaults) {
			$new = array();
			$shortcode = $columns['fblb_cpt_shortcode'];  // save the tags column
			unset($defaults['tags']);   // remove it from the columns list

			foreach($defaults as $key=>$value) {
				if($key=='date') {  // when we find the date column
				   $new['fblb_cpt_shortcode'] = __( 'Shortcode', FLB_TXTDM );  // put the tags column before it
				}    
				$new[$key] = $value;
			}
			return $new;  
		}
		
		// Facebook cpt shortcode column data
		public function custom_fblb_shortcode_cpt_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case 'fblb_cpt_shortcode' :
					echo "<input type='text' class='button button-primary' id='facebook-lightbox-shortcode-$post_id' value='[fblikebox id=$post_id]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return FBLBCopyShortcode$post_id();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-$post_id' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo "<script>
						function FBLBCopyShortcode$post_id() {
							var copyText = document.getElementById('facebook-lightbox-shortcode-$post_id');
							copyText.select();
							document.execCommand('copy');
							
							//fade in and out copied message
							jQuery('#copy-msg-$post_id').fadeIn('1000', 'linear');
							jQuery('#copy-msg-$post_id').fadeOut(2500,'swing');
						}
						</script>
					";
				break;
			}
		}		
		
		/**
		 * Loads the text domain.
		 * @return    void
		 * @access    private
		 */
		public function _load_textdomain() {
			load_plugin_textdomain( FLB_TXTDM, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}		
		
		/**
		 * Adds the Gallery menu item
		 * @access    private
		 * @return    void
		 */
		public function _fb_likebox_doc_menu() {
			$help_menu = add_submenu_page( 'edit.php?post_type='.FLWS_PLUGIN_SLUG, __( 'Docs', FLB_TXTDM ), __( 'Docs', FLB_TXTDM ), 'administrator', 'sr-doc-page', array( $this, '_fb_doc_page') );
			$featured_plugin_menu = add_submenu_page( 'edit.php?post_type='.FLWS_PLUGIN_SLUG, __( 'Free Plugins', FLB_TXTDM ), __( 'Free Plugins', FLB_TXTDM ), 'administrator', 'sr-featured-page', array( $this, '_fb_featured_page') );
			$theme_menu    = add_submenu_page( 'edit.php?post_type='.FLWS_PLUGIN_SLUG, __( 'Our Theme', FLB_TXTDM ), __( 'Our Theme', FLB_TXTDM ), 'administrator', 'sr-theme-page', array( $this, '_fb_theme_page') );
		}		
		
		/**
		 * Image Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		 * @access    private
		 * @return    void      Return custom post type.
		 */
		public function _facebook_likebox_widget_shortcode() {
			$labels = array(
				'name'                => _x( 'Facebook Likebox', 'Post Type General Name', FLB_TXTDM ),
				'singular_name'       => _x( 'Facebook Likebox', 'Post Type Singular Name', FLB_TXTDM ),
				'menu_name'           => __( 'Facebook Likebox', FLB_TXTDM ),
				'name_admin_bar'      => __( 'Facebook Likebox', FLB_TXTDM ),
				'parent_item_colon'   => __( 'Parent Item:', FLB_TXTDM ),
				'all_items'           => __( 'All Facebook Likebox', FLB_TXTDM ),
				'add_new_item'        => __( 'Add New Facebook Likebox', FLB_TXTDM ),
				'add_new'             => __( 'Add Facebook Likebox', FLB_TXTDM ),
				'new_item'            => __( 'New Facebook Likebox', FLB_TXTDM ),
				'edit_item'           => __( 'Edit Facebook Likebox', FLB_TXTDM ),
				'update_item'         => __( 'Update Facebook Likebox', FLB_TXTDM ),
				'search_items'        => __( 'Search Facebook Likebox', FLB_TXTDM ),
				'not_found'           => __( 'Facebook Likebox Not found', FLB_TXTDM ),
				'not_found_in_trash'  => __( 'Facebook Likebox Not found in Trash', FLB_TXTDM ),
			);
			$args = array(
				'label'               => __( 'Facebook Likebox', FLB_TXTDM ),
				'description'         => __( 'Custom Post Type For Facebook Likebox', FLB_TXTDM ),
				'labels'              => $labels,
				'supports'            => array( 'title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-facebook',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'fblb_shortcode_cpt', $args );
			
		} // end of post type function
		
		/**
		 * Adds Meta Boxes
		 * @access    private
		 * @return    void
		 */
		public function _admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '', __('Add Likebox', FLB_TXTDM), array(&$this, 'fb_upload_multiple_likebox'), 'fblb_shortcode_cpt', 'normal', 'default' );
		}
		
		public function fb_upload_multiple_likebox($post) { 
			?>
			<div style="clear:left;"></div>
			<br>
			<h1>Copy Facebook Likebox Shortcode</h1>
			<hr>
			<p class="input-text-wrap">
				<input type="text" name="shortcode" id="shortcode" value="<?php echo "[fblikebox id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 24px; width: 25%; border: 2px dashed;" onmouseover="return pulseOff();" onmouseout="return pulseStart();">
				<p><?php _e('Copy & Embed shortcode into any Page / Post to display on your site.', FLB_TXTDM); ?><br></p>
			</p>
			<br>
			
			<h1><?php _e('Facebook Likebox Settings', FLB_TXTDM); ?></h1>
			<hr>
			<?php
			//toggle button CSS
			wp_enqueue_style('awl-toggle-css', FLWS_PLUGIN_URL . 'css/toggle-button.css');
			
			require_once('facebook-likebox-meta-settings.php');
		} // end of upload multiple likebox
		
		
		public function _fb_save_settings($post_id) {
			if ( isset( $_POST['fb-settings'] ) == "fb-save-settings" ) {
				$awl_fb_shortcode_setting = "facebook_cpt_settings".$post_id;
				update_post_meta($post_id, $awl_fb_shortcode_setting, $_POST);
			}
		}// end save setting
		
		/**
		 * Image Gallery Docs Page
		 * Create doc page to help user to setup plugin
		 * @access    private
		 * @return    void.
		 */
		public function _fb_doc_page() {
			require_once('docs.php');
		}
		public function _fb_featured_page() {
			require_once('featured-plugins/featured-plugins.php');
		}
		
		// theme page
		public function _fb_theme_page() {
			require_once('our-theme/awp-theme.php');
		}
	} // end of class
} // end of class exist check

/**
 * Instantiates the Class
 * @global    object	$fb_likebox_object
 */
$fb_likebox_object = new Fb_Likebox_Shortcode_CPT();

?>