<?php
/**
 * @package Awaken
 */
$now = time ();
$apres = get_field('date_apres');

 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'genaral-post-item' ); ?>   >
	 <a href="<?php the_permalink(); ?>" rel="bookmark">
		<div class="hover">
			<span>+</span>
			d'infos
		</div>
		<div class="table">
			<div class="thumbnail_sidebar" style="<?php if ( has_post_thumbnail() ) { echo 'background-image: url(\'';the_post_thumbnail_url('medium');echo '\')'; }?>">
				
			</div>
			
		   
		    <div class="info">
			    <div class="title">
			       <?php the_title(); ?>
			    </div>
			    <div class="line">		
					<?php echo get_field('date_et_heure'); ?> 
			    </div>	
		    </div>
		</div>
	</a>
    
</article><!-- #post-## -->