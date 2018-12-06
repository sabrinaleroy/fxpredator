<?php
/**
 * @package Awaken
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="single-entry-header">
		<?php the_title( '<h1 class="single-entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->
	<div class="row">
		<div class="col-xs-12 col-sm-8 col-md-8 <?php echo $rtl ?>">
			<?php awaken_featured_image(); ?>
			<div class="entry-content">
				<div class="single-entry-meta">
					<span class="entry-date">Créé le : <?php echo get_the_date(); ?></span> |
					<span class="entry-date">Dernière Modification : <?php echo get_the_modified_date(); ?></span> |
					<span class="entry-date">Auteur : <?php the_author_posts_link(); ?></span>
					<br/>
					<?php
						
						/* translators: used between list items, there is a space after the comma */
						$tag_list = get_the_tag_list( '', __( ' ', 'awaken' ) );
			
						
			
						if ( '' != $tag_list ) {
							echo '<div class="tagged-under">';
								_e( 'Étiquettes', 'awaken' );
							echo '</div>';
							echo '<div class="awaken-tag-list">' . $tag_list . '</div>';
							echo '<div class="clearfix"></div>';	
						}
					?>
				</div><!-- .entry-meta -->
				
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'awaken' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
		
			<footer class="single-entry-footer">
					<?php
						/* translators: used between list items, there is a space after the comma */
						$category_list = get_the_category_list( __( ' ', 'awaken' ) );
			
						/* translators: used between list items, there is a space after the comma */
						$tag_list = get_the_tag_list( '', __( ' ', 'awaken' ) );
			
						if ( awaken_categorized_blog() ) {
							echo '<div class="categorized-under">';
								_e( 'Catégorie', 'awaken' );
							echo '</div>';
							echo '<div class="awaken-category-list">' . $category_list . '</div>';
							echo '<div class="clearfix"></div>';
						}
			
						if ( '' != $tag_list ) {
							echo '<div class="tagged-under">';
								_e( 'Étiquettes', 'awaken' );
							echo '</div>';
							echo '<div class="awaken-tag-list">' . $tag_list . '</div>';
							echo '<div class="clearfix"></div>';	
						}
					?>
		
			</footer><!-- .entry-footer -->
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4">
			<div class="author">
				<div class="profile_picture">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avatar.jpg" alt="<?php echo get_the_author();?>">
					<?php 
					
					$author = get_the_author_ID();
			
					$imgURL = get_field('photo_de_profil',"user_".$author);
					if($imgURL){ ?>
						<div class="not_default"  style="background-image: url(<?php  echo $imgURL ?>);">
							
						</div>
					<?php 	
					}
					
					?> 
					
				</div>
					<div class="author_name">
						<?php the_author_posts_link(); ?>
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
			<?php get_sidebar(); ?>
		</div><!-- .bootstrap cols -->
	</div>
</article><!-- #post-## -->
