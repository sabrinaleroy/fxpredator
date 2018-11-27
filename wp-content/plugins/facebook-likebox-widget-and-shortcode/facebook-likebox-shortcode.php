<?php
// Facebook Shortcode

add_shortcode('fblikebox', 'awl_fb_shortcode');
function awl_fb_shortcode($post_id) {
	ob_start();
	//load shortcode setting
	$id = $post_id['id'];
	if($facebook_cpt_settings = get_post_meta( $post_id['id'], 'facebook_cpt_settings'.$post_id['id'], true)) {
		$w_title = get_the_title($post_id['id']);
		$app_id = $facebook_cpt_settings['app_id'];
		$fb_page_url = $facebook_cpt_settings['fb_page_url'];
		$w_width = $facebook_cpt_settings['w_width'];
		$w_height = $facebook_cpt_settings['w_height'];
		$w_auto_width = $facebook_cpt_settings['w_auto_width'];
		$cover_photo = $facebook_cpt_settings['cover_photo'];
		$header_size = $facebook_cpt_settings['header_size'];
		$show_post = $facebook_cpt_settings['show_post'];
		$show_fans = $facebook_cpt_settings['show_fans'];
		$language = $facebook_cpt_settings['language'];	
		$title = $facebook_cpt_settings['title'];	
		

		//if($w_title == 'true') { echo ''}	
		?>
		
		<h2><?php if($title == 'true')	echo $w_title; else ""; ?></h2>
		
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
	}
	return ob_get_clean();
}
?>