<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Awaken
 */

get_header(); ?>
<div class="row">
<?php is_rtl() ? $rtl = 'awaken-rtl' : $rtl = ''; ?>
<div class="col-xs-12 col-sm-12 col-md-8 <?php echo $rtl ?>">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="archive-page-header evenement">
				<h1 class="archive-page-title">
					Évènements
				</h1>
				
			</header><!-- .page-header -->
			<h2 class="subsection">Prochainement</h2>
			
			<div class="row masonry">
				<?php
				   $now = date('Ymd');
				  
					$arguments = array(
						'posts_per_page'	=> -1,
						'post_type' 		=> 'evenement',
						'meta_key'			=> 'date_apres',
						'orderby'			=> 'meta_value',
						'order'				=> 'DESC',
						'meta_query'		=> array(
							array(
								'key'		=> 'date_apres',
								'value'		=> $now,
								'type' => 'DATE',
								'compare'	=> '>'
						)
					)
				);
			
				$query = new WP_Query( $arguments );
				
				if ( $query->have_posts() )  : ?>
			
				<!-- pagination here -->
			
				<!-- the loop -->
				<?php while ( $query->have_posts() ) : $query->the_post(); 
					get_template_part( 'content' );
					 endwhile; ?>
				<!-- end of the loop -->
			
				<!-- pagination here -->
			
				<?php wp_reset_postdata(); ?>
			
				<?php else : ?>
				
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<p>Aucun Événement à Venir</p>
				</div>
				<?php endif; ?>  
				
			</div>
			
			<h2 class="subsection">Retour sur</h2>
			
			
			
			<div class="row masonry">
				<?php
			   $now = date('Ymd');
			  
				$arguments = array(
					'posts_per_page'	=> -1,
					'post_type' 		=> 'evenement',
					'meta_key'			=> 'date_apres',
					'orderby'			=> 'meta_value',
					'order'				=> 'DESC',
					'meta_query'		=> array(
						array(
							'key'		=> 'date_apres',
							'value'		=> $now,
							'type' => 'DATE',
							'compare'	=> '<'
						)
					)
				);
			
				$query = new WP_Query( $arguments );
				
				if ( $query->have_posts() )  : ?>
			
				<!-- pagination here -->
			
				<!-- the loop -->
				<?php while ( $query->have_posts() ) : $query->the_post(); 
					get_template_part( 'content' );
					 endwhile; ?>
				<!-- end of the loop -->
			
				<!-- pagination here -->
			
				<?php wp_reset_postdata(); ?>
			
				<?php else : ?>
				
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<p>Aucun Événement Passé</p>
				</div>
				
				<?php endif; ?>  
			</div>
			
			
			
		
		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

</div><!-- .bootstrap cols -->
<div class="col-xs-12 col-sm-6 col-md-4">
	<?php get_sidebar(); ?>
</div><!-- .bootstrap cols -->
</div><!-- .row -->
<?php get_footer(); ?>
