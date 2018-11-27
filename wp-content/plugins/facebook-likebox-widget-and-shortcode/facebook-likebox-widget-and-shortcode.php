<?php
/**
 * @package Facebook Likebox Widget & Shortcode
 */
/*
Plugin Name: Facebook Likebox Widget & Shortcode
Plugin URI: https://awplife.com/
Description: A WordPress Social Media Plugin To Show Facebook Likebox
Version: 0.5.8
Author: A WP Life
Author URI: https://awplife.com/
License: GPLv2 or later
Text Domain: facebook-likebox-widget-and-shortcode

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Initialize the widget with codex hook
 */
add_action( 'widgets_init', function(){
    register_widget( 'AWPLIFW_FB_Widget_Shotcode' );
});

class AWPLIFW_FB_Widget_Shotcode extends WP_Widget {

	/**
	 * Sets up the widgets base id, name, description
	 */
	public function __construct() {
		$this->_hooks();
		// widget actual processes
		parent::__construct(
			'fblikebox_widget_shortcode', // Base ID
			__( 'facebook-likebox-widget-and-shortcode', FLB_TXTDM ), // Name
			array( 'description' => __( 'A Social Widget For Facebook Likebox', FLB_TXTDM ), ) // Args
			
			
		);
	}
		
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			
			

		} // end of hook function
		
		
			public function load_textdomain() {
			load_plugin_textdomain( FLB_TXTDM, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
	
	/**
	 * Widget Outputs
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];
		$w_title = $instance['w_title'];
		$app_id = $instance['app_id'];
		$fb_page_url = $instance['fb_page_url'];
		$w_width = $instance['w_width'];
		$w_height = $instance['w_height'];
		$w_auto_width = $instance['w_auto_width'];
		$cover_photo = $instance['cover_photo'];
		$header_size = $instance['header_size'];
		$show_post = $instance['show_post'];
		$show_fans = $instance['show_fans'];
		$language = $instance['language'];
		
		echo $args['before_title'] . apply_filters( 'widget_title', $instance['w_title'] ). $args['after_title'];
		?>
		<div id="fb-root"></div>
		<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '<?php echo $app_id; ?>',
			  xfbml      : true,
			  version    : 'v2.4'
			});
		  };
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/<?php echo $language; ?>/sdk.js#xfbml=1&version=v2.4&appId=<?php echo $app_id; ?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>

		<div class="fb-page" data-href="<?php echo $fb_page_url; ?>" data-width="<?php echo $w_width; ?>" data-height="<?php echo $w_height; ?>" data-small-header="<?php echo $header_size; ?>" data-adapt-container-width="<?php echo $w_auto_width; ?>" data-hide-cover="<?php echo $cover_photo; ?>" data-show-facepile="<?php echo $show_fans; ?>" data-show-posts="<?php echo $show_post; ?>"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Widget Setting Options Form for Admin
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$w_title = ! empty( $instance['w_title'] ) 			? $instance['w_title'] : '';
		$app_id = ! empty( $instance['app_id'] ) 			? $instance['app_id'] : '846720078759202';
		$fb_page_url = ! empty( $instance['fb_page_url'] ) 	? $instance['fb_page_url'] : 'https://www.facebook.com/awplife';
		$w_width = ! empty( $instance['w_width'] ) 			? $instance['w_width'] : '250';
		$w_height = ! empty( $instance['w_height'] ) 		? $instance['w_height'] : '';
		$w_auto_width = ! empty( $instance['w_auto_width'] )? $instance['w_auto_width'] : 'false';
		$cover_photo = ! empty( $instance['cover_photo'] ) 	? $instance['cover_photo'] : 'false';
		$header_size = ! empty( $instance['header_size'] )	? $instance['header_size'] : 'false';
		$show_post = ! empty( $instance['show_post'] ) 		? $instance['show_post'] : 'true';
		$show_fans = ! empty( $instance['show_fans'] ) 		? $instance['show_fans'] : 'true';
		$language = ! empty( $instance['language'] ) 		? $instance['language'] : 'en_US';
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'w_title' ); ?>"><?php _e( 'Widget Title' , FLB_TXTDM ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'w_title' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'w_title' , FLB_TXTDM ); ?>" type="text" value="<?php echo esc_attr( $w_title , FLB_TXTDM ); ?>" placeholder="Type Widget Title">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'app_id' , FLB_TXTDM ); ?>"><?php _e( 'Facebook Application ID' , FLB_TXTDM ); ?></label> &nbsp;  (<a href="http://awplife.com/create-facebook-application-id/" target="_new">Create New Own Application ID</a>)
		<input class="widefat" id="<?php echo $this->get_field_id( 'app_id' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'app_id' , FLB_TXTDM ); ?>" type="text" value="<?php echo esc_attr( $app_id , FLB_TXTDM ); ?>" placeholder="Enter Facebook Application ID Here">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'fb_page_url' , FLB_TXTDM ); ?>"><?php _e( 'Your FaceBook Page URL' , FLB_TXTDM ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'fb_page_url' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'fb_page_url' , FLB_TXTDM ); ?>" type="text" value="<?php echo esc_attr( $fb_page_url , FLB_TXTDM ); ?>" placeholder="Enter FaceBook Application ID Here">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'w_auto_width' , FLB_TXTDM ); ?>"><?php _e( 'Auto Widget Width' , FLB_TXTDM ); ?></label><br>
		<input class="widefat" id="<?php echo $this->get_field_id( 'w_auto_width' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'w_auto_width' , FLB_TXTDM ); ?>" <?php if($w_auto_width == 'true') echo "checked=checked" ?> type="radio" value="true"> Yes &nbsp;&nbsp;
		<input class="widefat" id="<?php echo $this->get_field_id( 'w_auto_width' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'w_auto_width' , FLB_TXTDM ); ?>" <?php if($w_auto_width == 'false') echo "checked=checked" ?> type="radio" value="false"> No
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'w_width' , FLB_TXTDM ); ?>"><?php _e( 'Custom Widget Width' , FLB_TXTDM ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'w_width' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'w_width' , FLB_TXTDM ); ?>" type="text" value="<?php echo esc_attr( $w_width , FLB_TXTDM ); ?>" placeholder="(Min Width: 180 - Max Width: 500)">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'w_height' , FLB_TXTDM ); ?>"><?php _e( 'Custom Widget Height' , FLB_TXTDM ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'w_height' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'w_height' , FLB_TXTDM ); ?>" type="text" value="<?php echo esc_attr( $w_height , FLB_TXTDM ); ?>" placeholder="(Minimium Height: 70)">
		</p>
				
		<p>
		<label for="<?php echo $this->get_field_id( 'cover_photo' , FLB_TXTDM ); ?>"><?php _e( 'Show Cover Photo' , FLB_TXTDM ); ?></label><br>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cover_photo' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'cover_photo' , FLB_TXTDM ); ?>" <?php if($cover_photo == 'false') echo "checked=checked" ?> type="radio" value="false"> Yes &nbsp;&nbsp;
		<input class="widefat" id="<?php echo $this->get_field_id( 'cover_photo' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'cover_photo' , FLB_TXTDM ); ?>" <?php if($cover_photo == 'true') echo "checked=checked" ?> type="radio" value="true"> No
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'header_size' , FLB_TXTDM ); ?>"><?php _e( 'Widget Header Size' , FLB_TXTDM ); ?></label><br>
		<input class="widefat" id="<?php echo $this->get_field_id( 'header_size' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'header_size' , FLB_TXTDM ); ?>" <?php if($header_size == 'true') echo "checked=checked" ?> type="radio" value="true"> Small &nbsp;&nbsp;
		<input class="widefat" id="<?php echo $this->get_field_id( 'header_size' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'header_size' , FLB_TXTDM ); ?>" <?php if($header_size == 'false') echo "checked=checked" ?> type="radio" value="false"> Large
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'show_fans' , FLB_TXTDM ); ?>"><?php _e( 'Show Friends' , FLB_TXTDM ); ?></label><br>
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_fans' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'show_fans' , FLB_TXTDM ); ?>" <?php if($show_fans == 'true') echo "checked=checked" ?> type="radio" value="true"> Yes &nbsp;&nbsp;
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_fans' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'show_fans' , FLB_TXTDM ); ?>" <?php if($show_fans == 'false') echo "checked=checked" ?> type="radio" value="false"> No
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'show_post' , FLB_TXTDM ); ?>"><?php _e( 'Show Page Posts' , FLB_TXTDM ); ?></label><br>
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_post' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'show_post' , FLB_TXTDM ); ?>" <?php if($show_post == 'true') echo "checked=checked" ?> type="radio" value="true"> Yes &nbsp;&nbsp;
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_post' , FLB_TXTDM ); ?>" name="<?php echo $this->get_field_name( 'show_post' ); ?>" <?php if($show_post == 'false') echo "checked=checked" ?> type="radio" value="false"> No
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'language' ); ?>"><?php _e( 'Widget Defalut Language' , FLB_TXTDM ); ?></label><br>
		<select id="<?php echo $this->get_field_id( 'language' ); ?>" name="<?php echo $this->get_field_name( 'language' , FLB_TXTDM ); ?>">
			<option value="en_US" <?php if ($language == 'en_US') echo ' selected="selected"'; ?>>English (US)</option>
			<option value="en_GB" <?php if ($language == 'en_GB') echo ' selected="selected"'; ?>>English (UK)</option>
			<option value="af_ZA" <?php if ($language == 'af_ZA') echo ' selected="selected"'; ?>>Afrikaans</option>
			<option value="ar_AR" <?php if ($language == 'ar_AR') echo ' selected="selected"'; ?>>Arabic</option>
			<option value="hy_AM" <?php if ($language == 'hy_AM') echo ' selected="selected"'; ?>>Armenian</option>
			<option value="bg_BG" <?php if ($language == 'bg_BG') echo ' selected="selected"'; ?>>Bulgarian</option>
			<option value="br_FR" <?php if ($language == 'br_FR') echo ' selected="selected"'; ?>>Breton</option>
			<option value="cs_CZ" <?php if ($language == 'cs_CZ') echo ' selected="selected"'; ?>>Czech</option>
			<option value="zh_CN" <?php if ($language == 'zh_CN') echo ' selected="selected"'; ?>>Chinese (Simplified China)</option>
			<option value="zh_HK" <?php if ($language == 'zh_HK') echo ' selected="selected"'; ?>>Chinese (Traditional Hong Kong)</option>
			<option value="zh_TW" <?php if ($language == 'zh_TW') echo ' selected="selected"'; ?>>Chinese (Traditional Taiwan)</option>
			<option value="da_DK" <?php if ($language == 'da_DK') echo ' selected="selected"'; ?>>Danish</option>
			<option value="nl_NL" <?php if ($language == 'nl_NL') echo ' selected="selected"'; ?>>Dutch</option>
			<option value="fr_FR" <?php if ($language == 'fr_FR') echo ' selected="selected"'; ?>>French (France)</option>
			<option value="fr_CA" <?php if ($language == 'fr_CA') echo ' selected="selected"'; ?>>French (Canada)</option>
			<option value="de_DE" <?php if ($language == 'de_DE') echo ' selected="selected"'; ?>>German</option>
			<option value="he_IL" <?php if ($language == 'he_IL') echo ' selected="selected"'; ?>>Hebrew</option>
			<option value="hi_IN" <?php if ($language == 'hi_IN') echo ' selected="selected"'; ?>>Hindi</option>
			<option value="hu_HU" <?php if ($language == 'hu_HU') echo ' selected="selected"'; ?>>Hungarian</option>
			<option value="ga_IE" <?php if ($language == 'ga_IE') echo ' selected="selected"'; ?>>Irish</option>
			<option value="id_ID" <?php if ($language == 'id_ID') echo ' selected="selected"'; ?>>Indonesian</option>
			<option value="it_IT" <?php if ($language == 'it_IT') echo ' selected="selected"'; ?>>Italian</option>
			<option value="ja_JP" <?php if ($language == 'ja_JP') echo ' selected="selected"'; ?>>Japanese</option>
			<option value="kk_KZ" <?php if ($language == 'kk_KZ') echo ' selected="selected"'; ?>>Kazakh</option>
			<option value="ko_KR" <?php if ($language == 'ko_KR') echo ' selected="selected"'; ?>>Korean</option>
			<option value="la_VA" <?php if ($language == 'la_VA') echo ' selected="selected"'; ?>>Latin</option>
			<option value="ne_NP" <?php if ($language == 'ne_NP') echo ' selected="selected"'; ?>>Nepali</option>
			<option value="fa_IR" <?php if ($language == 'fa_IR') echo ' selected="selected"'; ?>>Persian</option>			
			<option value="pl_PL" <?php if ($language == 'pl_PL') echo ' selected="selected"'; ?>>Polish</option>
			<option value="pt_PT" <?php if ($language == 'pt_PT') echo ' selected="selected"'; ?>>Portuguese </option>
			<option value="ro_RO" <?php if ($language == 'ro_RO') echo ' selected="selected"'; ?>>Romanian</option>
			<option value="ru_RU" <?php if ($language == 'ru_RU') echo ' selected="selected"'; ?>>Russian</option>
			<option value="es_LA" <?php if ($language == 'es_LA') echo ' selected="selected"'; ?>>Spanish</option>
			<option value="es_CL" <?php if ($language == 'es_CL') echo ' selected="selected"'; ?>>Spanish (Chile)</option>
			<option value="es_CO" <?php if ($language == 'es_CO') echo ' selected="selected"'; ?>>Spanish (Colombia)</option>
			<option value="es_ES" <?php if ($language == 'es_ES') echo ' selected="selected"'; ?>>Spanish (Spain)</option>
			<option value="es_MX" <?php if ($language == 'es_MX') echo ' selected="selected"'; ?>>Spanish (Mexico)</option>
			<option value="es_VE" <?php if ($language == 'es_VE') echo ' selected="selected"'; ?>>Spanish (Venezuela)</option>
			<option value="sr_RS" <?php if ($language == 'sr_RS') echo ' selected="selected"'; ?>>Serbian</option>
			<option value="sv_SE" <?php if ($language == 'sv_SE') echo ' selected="selected"'; ?>>Swedish</option>
			<option value="th_TH" <?php if ($language == 'th_TH') echo ' selected="selected"'; ?>>Thai</option>
			<option value="tr_TR" <?php if ($language == 'tr_TR') echo ' selected="selected"'; ?>>Turkish</option>
			<option value="ur_PK" <?php if ($language == 'ur_PK') echo ' selected="selected"'; ?>>Urdu</option>
		</select>
		</p>
		<?php 
	}

	/**
	 * Widget Setting Options Saving Process
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['w_title']	= ( ! empty( $new_instance['w_title'] ) ) ? strip_tags( $new_instance['w_title'] ) : '';
		$instance['app_id']		= ( ! empty( $new_instance['app_id'] ) ) ? strip_tags( $new_instance['app_id'] ) : '846720078759202';
		$instance['fb_page_url']= ( ! empty( $new_instance['fb_page_url'] ) ) ? strip_tags( $new_instance['fb_page_url'] ) : 'https://www.facebook.com/awplife';
		$instance['w_width']	= ( ! empty( $new_instance['w_width'] ) ) ? strip_tags( $new_instance['w_width'] ) : '250';
		$instance['w_height'] = ( ! empty( $new_instance['w_height'] ) ) ? strip_tags( $new_instance['w_height'] ) : '';
		$instance['w_auto_width'] = ( ! empty( $new_instance['w_auto_width'] ) ) ? strip_tags( $new_instance['w_auto_width'] ) : 'false';
		$instance['cover_photo'] = ( ! empty( $new_instance['cover_photo'] ) ) ? strip_tags( $new_instance['cover_photo'] ) : 'false';
		$instance['header_size'] = ( ! empty( $new_instance['header_size'] ) ) ? strip_tags( $new_instance['header_size'] ) : 'false';
		$instance['show_fans'] = ( ! empty( $new_instance['show_fans'] ) ) ? strip_tags( $new_instance['show_fans'] ) : 'true';
		$instance['show_post'] = ( ! empty( $new_instance['show_post'] ) ) ? strip_tags( $new_instance['show_post'] ) : 'true';
		$instance['language'] = ( ! empty( $new_instance['language'] ) ) ? strip_tags( $new_instance['language'] ) : 'en_US';
		return $instance;
	}
}

// Facebook Custom Post Type
require('facebook-likebox-cpt.php');

// Facebook Shortcode
require('facebook-likebox-shortcode.php');
?>