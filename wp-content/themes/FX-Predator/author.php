<?php
/**
 * The template for displaying author pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Awaken
 */

get_header(); ?>
<div class="row">
<?php is_rtl() ? $rtl = 'awaken-rtl' : $rtl = ''; ?>
<div class=" <?php echo $rtl ?>">
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

	
			<header class="archive-page-header author">
				<h1 class="archive-page-title ">
					<?php
						$user_info = get_userdata( $author );
						
						echo $user_info->nickname;
							
					?>
				</h1>
			</header><!-- .page-header -->
			
			<div class="col-xs-12 col-sm-12 col-md-4 <?php echo $rtl ?>">
				<div class="profile_picture">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar.jpg" alt="<?php echo get_the_author();?>">
				<?php 
					
					$imgURL = get_cupp_meta($author, 'large');
					if($imgURL){ ?>
						<div class="not_default"  style="background-image: url(<?php  echo $imgURL ?>);">
							
						</div>
				<?php 	
					}
				?> 
				 
				</div>
				<?php 
					/**
					if($user_info->roles[0]=="editor"){
						echo '<div class="role">Modérateur</div>';
					}
					**/
					if(count_user_posts($author)>0){
						
						?>
							<div class="nb_articles">Nombre d'articles : <?php echo count_user_posts($author); ?></div>
						
						<?php 
	
					}
					
					
					if(get_field("reseaux", "user_".$author)){
						echo "<ul class=\"reseaux\">";
						
						
						
							// loop through the rows of data
						    while ( have_rows('reseaux', "user_".$author) ) : the_row();
						    ?>
						    
								<li><a href="<?php the_sub_field('lien'); ?>"><i class="fa fa-<?php the_sub_field('icone'); ?>" aria-hidden="true"></i></a></li>
						        
						       <?php  
								
						    endwhile;
						echo "</ul>";
					}
				
				?> 
				<div class="description">
				<?php 
						the_author_meta('description',$author);
						if(count_user_posts($author)>0){
						
	
					}
				?>
				</div>
				
			</div>
			<div class="col-xs-12 col-sm-12 col-md-8 <?php echo $rtl ?>">
				<?php 
				
				$images = get_field('gallerie',"user_".$author);
				
				
				if( $images ): ?>
					<div class="author_section">
						<h2 class="title_author">Ma Gallerie</h2>
						
						
						<div class="row masonry">
					   
					        <?php foreach( $images as $image ): ?>
					        
					        	<div class="col-xs-12 col-sm-4 col-md-4 grid-item gallery-item-container">
					        		<a href="<?php echo $image['url'] ?>" target="_self" class="gallery-item">
							    		<?php echo wp_get_attachment_image( $image['ID'], "large" ); ?>
					        		</a>
							    </div>
					           
					        <?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="author_section">
					<h2 class="title_author">Mes Articles</h2>
					<div class="row masonry">
						<?php while ( have_posts() ) : the_post(); ?>
			
							<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'content');
			
							?>
						<?php endwhile; ?>
					
					
						<div class="col-xs-12 col-sm-12 col-md-12">
							<?php awaken_paging_nav(); ?>
						</div>
					</div><!-- .row -->
				</div>
			</div>
			



		</main><!-- #main -->
	</section><!-- #primary -->

</div><!-- .bootstrap cols -->
</div><!-- .row -->
<?php get_footer(); ?>
