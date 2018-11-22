<?php
/**
 * @package Awaken
 */
?>
<div class="col-xs-12 col-sm-4 col-md-4 grid-item">
<article id="post-<?php the_ID(); ?>" <?php post_class( 'genaral-post-item' ); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<figure class="genpost-featured-image">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'original' ); ?></a>
		</figure>
	<?php } else { ?>
		<figure class="genpost-featured-image">
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo get_template_directory_uri() . '/images/thumbnail-default.jpg'; ?>" />
			</a>
		</figure>
	<?php } ?>
	
	<div class="genpost-entry-content">
		<header class="genpost-entry-header">
			<?php the_title( sprintf( '<h2 class="genpost-entry-title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	
			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="genpost-entry-meta">
					<span><?php the_author_posts_link(); ?></span><?php the_modified_date(); ?> 
					<?php the_category(); ?>
		 
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<a href="<?php the_permalink();?>" class="lire">Lire</a>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
</div>