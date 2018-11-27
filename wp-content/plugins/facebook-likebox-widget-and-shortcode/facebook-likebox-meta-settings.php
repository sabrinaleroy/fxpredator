<?php
//toggle button CSS
wp_enqueue_style('awl-em-event_monster-css', FLWS_PLUGIN_URL . 'css/toogle-button.css');

//load settings
$facebook_cpt_settings = get_post_meta( $post->ID, 'facebook_cpt_settings'.$post->ID, true);
//print_r($facebook_cpt_settings);
?>

<div class="nig_settings">
	<p class="bg-title"><?php _e('1. Facebook Application ID', FLB_TXTDM); ?></p></br>
	<?php if(isset($facebook_cpt_settings['app_id'])) $app_id = $facebook_cpt_settings['app_id']; else $app_id = "846720078759202"; ?>
	<input class="input-width" id="app_id" name="app_id" type="text" value="<?php echo $app_id; ?>" placeholder="Type Facebook Application ID"> ( <a href="http://awplife.com/create-facebook-application-id/" target="_new">Create New Own Facebook Application ID</a> )<br>
	<h4><?php _e('You Should Type Facebook Application Id Here', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>


<div class="nig_settings">
	<p class="bg-title"><?php _e('2. Your Facebook Page URL', FLB_TXTDM); ?></p></br>
	<?php if(isset($facebook_cpt_settings['fb_page_url'])) $fb_page_url = $facebook_cpt_settings['fb_page_url']; else $fb_page_url = "https://www.facebook.com/A-WordPress-Life-1020764421361036/"; ?>
	<input class="input-width" id="fb_page_url" name="fb_page_url" type="text" value="<?php echo $fb_page_url; ?>" placeholder="Enter Facebook Application ID Here"><br>
	<h4><?php _e('You Should Type Your facebook Page URL In This Setting Feild ', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>



<div class="nig_settings">
	<p class="bg-title"><?php _e('3. Title Show/Hide Setting', FLB_TXTDM); ?></p><br>
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['title'])) $title = $facebook_cpt_settings['title']; else $title = "true"; ?>
		<input type="radio" name="title" id="w_title1" value="true" <?php if($title == "true") echo "checked=checked"; ?>>
		<label for="w_title1"><?php _e('Show', FLB_TXTDM); ?></label>
		<input type="radio" name="title" id="w_title2" value="false" <?php if($title == "false") echo "checked=checked"; ?>>
		<label for="w_title2"><?php _e('Hide', FLB_TXTDM); ?></label><br><br>
	</p>
	<h4><?php _e('You Can Show & Hide Your Likebox Title', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>



<div class="nig_settings">
	<p class="bg-title"><?php _e('4. Auto Likebox Width', FLB_TXTDM); ?></p><br>
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['w_auto_width'])) $w_auto_width = $facebook_cpt_settings['w_auto_width']; else $w_auto_width = "false"; ?>
		<input type="radio" name="w_auto_width" id="w_auto_width_1" value="true" <?php if($w_auto_width == "true") echo "checked=checked"; ?>>
		<label for="w_auto_width_1"><?php _e('Yes', FLB_TXTDM); ?></label>
		<input type="radio" name="w_auto_width" id="w_auto_width_2" value="false" <?php if($w_auto_width == "false") echo "checked=checked"; ?>>
		<label for="w_auto_width_2"><?php _e('No', FLB_TXTDM); ?></label><br><br>
	</p>
	<h4><?php _e('You Can Manage Facebook Likebox Width', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>



	
<div class="nig_settings">
	<p class="bg-title"><?php _e('5. Custom Likebox Width', FLB_TXTDM); ?></p></br>
	<p class="range-slider">
		<?php if(isset($facebook_cpt_settings['w_width'])) $w_width = $facebook_cpt_settings['w_width']; else $w_width = ""; ?>
		<input id="w_width" name="w_width" class="range-slider__range" type="range" value="<?php echo $w_width; ?>" min="180" max="500">
		<span class="range-slider__value">0</span>
		<h4><?php _e('You Can Set Your Custom Likebox Width In This Feild By Using This Range Bar', FLB_TXTDM); ?></h4>
	</p>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>




<div class="nig_settings">
	<p class="bg-title"><?php _e('6. Custom Likebox Height', FLB_TXTDM); ?></p></br>
	<p class="range-slider">
		<?php if(isset($facebook_cpt_settings['w_height'])) $w_height = $facebook_cpt_settings['w_height']; else $w_height = ""; ?>
		<input id="w_height" name="w_height" class="range-slider__range" type="range" value="<?php echo $w_height; ?>" min="70" max="1000">
		<span class="range-slider__value">0</span><br><br>
		<h4><?php _e('You Can Set Your Custom Likebox Height In This Feild By Using This Range Bar', FLB_TXTDM); ?></h4>
	</p>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>


<div class="nig_settings">
	<p class="bg-title"><?php _e('7. Show Cover Photo', FLB_TXTDM); ?></p></br>
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['cover_photo'])) $cover_photo = $facebook_cpt_settings['cover_photo']; else $cover_photo = "true"; ?>
		<input type="radio" name="cover_photo" id="cover_photo1" value="false" <?php if($cover_photo == "false") echo "checked=checked"; ?>>
		<label for="cover_photo1"><?php _e('Yes', FLB_TXTDM); ?></label>
		<input type="radio" name="cover_photo" id="cover_photo2" value="true" <?php if($cover_photo == "true") echo "checked=checked"; ?>>
		<label for="cover_photo2"><?php _e('No', FLB_TXTDM); ?></label>
	</p>
	<h4><?php _e('Set Your facebook Cover Photo Setting By Using This Setting Feild ', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>

	
<div class="nig_settings">
	<p class="bg-title"><?php _e('8. Widget Header Size', FLB_TXTDM); ?></p></br>	
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['header_size'])) $header_size = $facebook_cpt_settings['header_size']; else $header_size = "true"; ?>
		<input type="radio" name="header_size" id="header_size1" value="false" <?php if($header_size == "false") echo "checked=checked"; ?>>
		<label for="header_size1"><?php _e('Large', FLB_TXTDM); ?></label>
		<input type="radio" name="header_size" id="header_size2" value="true" <?php if($header_size == "true") echo "checked=checked"; ?>>
		<label for="header_size2"><?php _e('Small', FLB_TXTDM); ?></label><br>
	</p>
	<h4><?php _e('You Can Set Your facebook Likebox Widget Header Size By Using This Setting Feild', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>

	
<div class="nig_settings">
	<p class="bg-title"><?php _e('9. Show Friends', FLB_TXTDM); ?></p></br>
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['show_fans'])) $show_fans = $facebook_cpt_settings['show_fans']; else $show_fans = "true"; ?>
		<input type="radio" name="show_fans" id="show_fans1" value="true" <?php if($show_fans == "true") echo "checked=checked"; ?>>
		<label for="show_fans1"><?php _e('Yes', FLB_TXTDM); ?></label>
		<input type="radio" name="show_fans" id="show_fans2" value="false" <?php if($show_fans == "false") echo "checked=checked"; ?>>
		<label for="show_fans2"><?php _e('No', FLB_TXTDM); ?></label><br>
	</p>
	<h4><?php _e('You Can Show / Hide Your Facebook Friends Into Likebox By Using This Setting Feild', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>

<div class="nig_settings">
	<p class="bg-title"><?php _e('10. Show Page Posts', FLB_TXTDM); ?></p></br>
	<p class=" switch-field em_size_field">
		<?php if(isset($facebook_cpt_settings['show_post'])) $show_post = $facebook_cpt_settings['show_post']; else $show_post = "true"; ?>
		<input type="radio" name="show_post" id="show_post1" value="true" <?php if($show_post == "true") echo "checked=checked"; ?>>
		<label for="show_post1"><?php _e('Yes', FLB_TXTDM); ?></label>
		<input type="radio" name="show_post" id="show_post2" value="false" <?php if($show_post == "false") echo "checked=checked"; ?>>
		<label for="show_post2"><?php _e('No', FLB_TXTDM); ?></label><br>
	</p>
	<h4><?php _e('You Can Show / Hide Your Posts & Pages Into Facebook Likebox By Using This Setting Feild', FLB_TXTDM); ?></h4>
	<a class="be-right1 button button-primary" href="#">Go To Top</a>
</div>


	<div class="nig_settings">
		<p class="bg-title"><?php _e('11. Widget Defalut Language', FLB_TXTDM); ?></p></br>
		<?php if(isset($facebook_cpt_settings['language'])) $language = $facebook_cpt_settings['language']; else $language = ""; ?>

		<select class="" name="language" id="language">
			<option value="en_US" <?php if($language == "en_US") echo "selected=selected"; ?>><?php _e('English (US)'); ?></option>
			<option value="en_GB" <?php if($language == "en_GB") echo "selected=selected"; ?>><?php _e('English (UK)'); ?></option>
			<option value="af_ZA" <?php if($language == "af_ZA") echo "selected=selected"; ?>><?php _e('Afrikaans'); ?></option>	
			<option value="ar_AR" <?php if($language == "ar_AR") echo "selected=selected"; ?>><?php _e('Arabic'); ?></option>	
			<option value="hy_AM" <?php if($language == "hy_AM") echo "selected=selected"; ?>><?php _e('Armenian'); ?></option>
			<option value="bg_BG" <?php if($language == "bg_BG") echo "selected=selected"; ?>><?php _e('Bulgarian'); ?></option>
			<option value="br_FR" <?php if($language == "br_FR") echo "selected=selected"; ?>><?php _e('Breton'); ?></option>
			<option value="cs_CZ" <?php if($language == "cs_CZ") echo "selected=selected"; ?>><?php _e('Czech'); ?></option>
			<option value="zh_CN" <?php if($language == "zh_CN") echo "selected=selected"; ?>><?php _e('Chinese (Simplified China)'); ?></option>
			<option value="zh_HK" <?php if($language == "zh_HK") echo "selected=selected"; ?>><?php _e('Chinese (Traditional Hong Kong)'); ?></option>
			<option value="zh_TW" <?php if($language == "zh_TW") echo "selected=selected"; ?>><?php _e('Chinese (Traditional Taiwan)'); ?></option>
			<option value="da_DK" <?php if($language == "da_DK") echo "selected=selected"; ?>><?php _e('Danish'); ?></option>
			<option value="nl_NL" <?php if($language == "nl_NL") echo "selected=selected"; ?>><?php _e('Dutch'); ?></option>
			<option value="fr_FR" <?php if($language == "fr_FR") echo "selected=selected"; ?>><?php _e('French (France)'); ?></option>
			<option value="fr_CA" <?php if($language == "fr_CA") echo "selected=selected"; ?>><?php _e('French (Canada)'); ?></option>
			<option value="de_DE" <?php if($language == "de_DE") echo "selected=selected"; ?>><?php _e('German'); ?></option>
			<option value="he_IL" <?php if($language == "he_IL") echo "selected=selected"; ?>><?php _e('Hebrew'); ?></option>
			<option value="hi_IN" <?php if($language == "hi_IN") echo "selected=selected"; ?>><?php _e('Hindi'); ?></option>
			<option value="hu_HU" <?php if($language == "hu_HU") echo "selected=selected"; ?>><?php _e('Hungarian'); ?></option>
			<option value="ga_IE" <?php if($language == "ga_IE") echo "selected=selected"; ?>><?php _e('Irish'); ?></option>
			<option value="id_ID" <?php if($language == "id_ID") echo "selected=selected"; ?>><?php _e('Indonesian'); ?></option>
			<option value="it_IT" <?php if($language == "it_IT") echo "selected=selected"; ?>><?php _e('Italian'); ?></option>
			<option value="ja_JP" <?php if($language == "ja_JP") echo "selected=selected"; ?>><?php _e('Japanese'); ?></option>
			<option value="kk_KZ" <?php if($language == "kk_KZ") echo "selected=selected"; ?>><?php _e('Kazakh'); ?></option>
			<option value="ko_KR" <?php if($language == "ko_KR") echo "selected=selected"; ?>><?php _e('Korean'); ?></option>
			<option value="la_VA" <?php if($language == "la_VA") echo "selected=selected"; ?>><?php _e('Latin'); ?></option>
			<option value="ne_NP" <?php if($language == "ne_NP") echo "selected=selected"; ?>><?php _e('Nepali'); ?></option>
			<option value="fa_IR" <?php if($language == "fa_IR") echo "selected=selected"; ?>><?php _e('Persian'); ?></option>
			<option value="pl_PL" <?php if($language == "pl_PL") echo "selected=selected"; ?>><?php _e('Polish'); ?></option>
			<option value="pt_PT" <?php if($language == "pt_PT") echo "selected=selected"; ?>><?php _e('Portuguese'); ?></option>
			<option value="ro_RO" <?php if($language == "ro_RO") echo "selected=selected"; ?>><?php _e('Romanian'); ?></option>
			<option value="ru_RU" <?php if($language == "ru_RU") echo "selected=selected"; ?>><?php _e('Russian'); ?></option>
			<option value="es_LA" <?php if($language == "es_LA") echo "selected=selected"; ?>><?php _e('Spanish'); ?></option>
			<option value="es_CL" <?php if($language == "es_CL") echo "selected=selected"; ?>><?php _e('Spanish (Chile)'); ?></option>
			<option value="es_CO" <?php if($language == "es_CO") echo "selected=selected"; ?>><?php _e('Spanish (Colombia)'); ?></option>
			<option value="es_ES" <?php if($language == "es_ES") echo "selected=selected"; ?>><?php _e('Spanish (Spain)'); ?></option>
			<option value="es_MX" <?php if($language == "es_MX") echo "selected=selected"; ?>><?php _e('Spanish (Mexico)'); ?></option>
			<option value="es_VE" <?php if($language == "es_VE") echo "selected=selected"; ?>><?php _e('Spanish (Venezuela)'); ?></option>
			<option value="sr_RS" <?php if($language == "sr_RS") echo "selected=selected"; ?>><?php _e('Serbian'); ?></option>
			<option value="sv_SE" <?php if($language == "sv_SE") echo "selected=selected"; ?>><?php _e('Swedish'); ?></option>			
			<option value="th_TH" <?php if($language == "th_TH") echo "selected=selected"; ?>><?php _e('Thai'); ?></option>		
			<option value="tr_TR" <?php if($language == "tr_TR") echo "selected=selected"; ?>><?php _e('Turkish'); ?></option>			
			<option value="ur_PK" <?php if($language == "ur_PK") echo "selected=selected"; ?>><?php _e('Urdu'); ?></option>
		</select><br>
		<h4 ><?php _e('Set Your Countary Language Into Your Facebook Likebox', FLB_TXTDM); ?></h4>
	</div>
	

	
<input type="hidden" name="fb-settings" id="fb-settings" value="fb-save-settings">
<style>
.nig_settings {
	padding: 8px 0px 8px 8px !important;
	margin: 10px 10px 4px 0px !important;
	
}

.nig_settings label {
	font-size: 16px !important;
}
.be-right {
	float: right;
	text-align: right;
	text-decoration: none;
}

.be-right1 {
	float: right;
	text-align: right;
	text-decoration: none;
	padding-right: 10px;
}

.input-width {
	width: 25%;
}
</style>
<script>
	// start pulse on page load
	function pulseEff() {
	   jQuery('#shortcode').fadeOut(600).fadeIn(600);
	};
	var Interval;
	Interval = setInterval(pulseEff,1500);

	// stop pulse
	function pulseOff() {
		clearInterval(Interval);
	}
	// start pulse
	function pulseStart() {
		Interval = setInterval(pulseEff,2000);
	}
	
//range slider
	var rangeSlider = function(){
	  var slider = jQuery('.range-slider'),
		  range = jQuery('.range-slider__range'),
		  value = jQuery('.range-slider__value');
		
	  slider.each(function(){

		value.each(function(){
		  var value = jQuery(this).prev().attr('value');
		  jQuery(this).html(value);
		});

		range.on('input', function(){
		  jQuery(this).next(value).html(this.value);
		});
	  });
	};
	rangeSlider();	
</script>
<hr>
<style>
	.awp_bale_offer {
		background-image: url("<?php echo FLWS_PLUGIN_URL ?>/img/awp-bale.jpg");
		background-repeat:no-repeat;
		padding:30px;
	}
	.awp_bale_offer h1 {
		font-size:35px;
		color:#FFFFFF;
	}
	.awp_bale_offer h3 {
		font-size:25px;
		color:#FFFFFF;
	}
</style>
<div class="row awp_bale_offer">
	<div class="">
		<h1>Plugin's Bale Offer</h1>
		<h3>Get All Premium Plugin - 18+ Premium Plugins ( Personal Licence ) in just $149 </h3>
		<h4> 10+ gallery plugins, 3+ Slider Plugin ,pricing table , Event , Testimonial , Contact Form, Social media, Popup Box, Weather Effect, Social share , customizer login page</h4>
		<h3><strike>$399</strike> For $149 Only</h3>
	</div>
	<div class="">
		<a href="http://awplife.com/account/signup/all-premium-plugins" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">BUY NOW</a>
	</div>
</div>
<hr>
<p class="">
	<h1><strong>Our Top famous Plugins:</strong></h1>
	<br>
	<a href="https://wordpress.org/plugins/portfolio-filter-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Portfolio Filter Gallery</a>
	<a href="https://wordpress.org/plugins/blog-filter/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Blog Filter Gallery</a>
	<a href="https://wordpress.org/plugins/wp-flickr-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Flickr Gallery</a>
	<a href="https://wordpress.org/plugins/new-social-media-widget/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Social Media</a>
	<a href="https://wordpress.org/plugins/new-image-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Image Gallery</a>
	<a href="https://wordpress.org/plugins/new-photo-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Photo Gallery</a>
	<a href="https://wordpress.org/plugins/slider-responsive-slideshow/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Slider Responsive Slideshow</a>
	<a href="https://wordpress.org/plugins/new-video-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Video Gallery</a><br><br>
	<a href="https://wordpress.org/plugins/media-slider/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Media Slider</a>
</p>
<p class="">
	<h1><strong>Try Our Other Top Free Plugins:</strong></h1>
	<br>
	<a href="https://wordpress.org/plugins/portfolio-filter-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Portfolio Filter Gallery</a>
	<a href="https://wordpress.org/plugins/blog-filter/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Blog Filter Gallery</a>
	<a href="https://wordpress.org/plugins/wp-flickr-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Flickr Gallery</a>
	<a href="https://wordpress.org/plugins/new-grid-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Grid Gallery</a>
	<a href="https://wordpress.org/plugins/new-social-media-widget/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Social Media</a>
	<a href="https://wordpress.org/plugins/new-image-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Image Gallery</a>
	<a href="https://wordpress.org/plugins/new-photo-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Photo Gallery</a>
	<a href="https://wordpress.org/plugins/new-contact-form-widget/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Contact Form Widget</a><br><br>
	<a href="https://wordpress.org/plugins/facebook-likebox-widget-and-shortcode/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Facebook Likebox Plugin</a>
	<a href="https://wordpress.org/plugins/new-facebook-like-share-follow-button/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Facebook Like Share Follow Button</a>
	<a href="https://wordpress.org/plugins/new-google-plus-badge/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Google Plus Badge</a>
	<a href="https://wordpress.org/plugins/weather-effect/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Weather Effect</a>
	<a href="https://wordpress.org/plugins/insta-type-gallery/" target="_blank" class="button button-primary load-customize hide-if-no-customize">Instagram Type Gallery</a>
</p>

