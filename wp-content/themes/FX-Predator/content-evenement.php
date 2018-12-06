<?php
/**
 * @package Awaken
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="single-entry-header">
		<h1 class="single-entry-title">
		<?php 
			$apres=strtotime(get_field('date_apres'));
			$now = time();
			if($apres<$now){
				echo "<span class=\"retour\">Retour sur : </span>";
			}
			the_title(); ?>
		</h1>
	</header><!-- .entry-header -->
	
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4">
			
			<?php 
				if(get_field('logo')){
					$logo=get_field('logo'); 
					echo '<img src="'.$logo['sizes']['medium_large'].'" alt="'.$logo['alt'].'">';
				}else{
					the_post_thumbnail( 'original' );
				}
			
				
			?>
			
			<?php
		
			if(get_field('lien_vers_le_site_de_levenement')){?>
								
							
				<div class="linktoevent">
					<a href="<?php echo get_field('lien_vers_le_site_de_levenement')?>" class="lien_evenement" target="_blank" title="<?php the_title(); ?>">
						Site de l'Évènement 
					</a>
				</div>
			<?php 
			} 
			?>
			
			
			
			<div class="encart ">
				<h3 class="subsection">Horaires</h3>
				<div class="date_evenement"><?php the_field('date_et_heure'); ?></div>
			</div>
		
			
			
			
			
			<?php if(get_field('plan_dacces_au_stand')){?>
				<div class="encart gris">
					<h3 class="subsection">Plan</h3>
					<div class="plan_dacces_au_stand">
						<a href="<?php echo get_field('plan_dacces_au_stand')['url'];?>" target="_blank" title="Access au stand - <?php the_title(); ?>">Accès au stand / Plan de l'évènement</a>
					</div>
				</div>
			<?php }?>
			<div class="single-entry-meta">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$category_list = get_the_term_list( get_the_ID(), "type_evenement",  '' ,"," ,' ');
	
	
				if ( awaken_categorized_blog() ) {
					echo '<div class="categorized-under">Type d\'évènement:';
					echo '</div>';
					echo '<div class="awaken-category-list">' . $category_list . '</div>';
				}
	
				
				?>
			</div><!-- .entry-meta -->
			
		</div>
		
		<div class="col-xs-12 col-sm-8 col-md-8">
			<?php 
				
				
				if($apres>$now){
					if(get_field('courte_description_avant')){
						
						echo '<div class="encart grand">
							<h2 class="subsection">Petit mot</h2>
							<div class="courte_description_avant">'.get_field('courte_description_avant').'</div>
						</div>';
					}
				}
				
				if($apres<$now){
					if(get_field('courte_description_apres')){
						echo '<div class="encart grand">
								<h2 class="subsection">Petit mot</h2>
								<div class="courte_description_apres">'.get_field('courte_description_apres').'</div>
							</div>';
					}
				}
			?>
			<?php 
				if($apres<$now){
			?>		
					<div class="encart grand">
						<h2 class="subsection">Gallerie</h2>
			<?php 
				    echo   do_shortcode('[gallery media_category="'.get_field('categorie_de_lalbum').'" type="masonry" size="full" link="file"]');
		
			
				
			?>	
					</div>
			<?php
					}
					
					
				$location = get_field('google_maps');
				if( !empty($location) ):
			?>	
				
				<div class="encart grand">
					<h2 class="subsection"><i class="fa fa-map-signs" aria-hidden="true"></i> Itinéraire</h2>
					<div class="acf-map ">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
					</div>
				</div>
			<?php 
				endif; 
						
				if(current_user_can( 'read_private_posts' )){
					?>
					<div class="encart grand infos_complementaires">
						<h2 class="subsection">Info Membres</h2>
						<div class="col-xs-6 col-sm-6 col-md-6 display_table">
							<?php 
								$responsable = get_field('responsable');
							
							?>
							<h3>Responsable</h3>
								<div class=" display_table">
								<?php 
								echo '
									<div class=" display_table_cell">
										<div class="avatar">
											'.$responsable['user_avatar'].'
										</div>
									</div>
									<div class="display_table_cell">
										<span class="nickname">'.$responsable['nickname'].'</span>
										<span class="telephone">'.get_field('telephone', "user_".$responsable['ID']).'</span>
									</div>';
										
									
								?>
								</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<h3>Horaires Exposants</h3>
							
							<div class="date_evenement">
							<?php the_field('date_heure_et_infos_complementaires_membres'); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="contenu"><?php the_field('infos_complementaires'); ?></div>
						</div>
					</div>
					<?php
				}
				 ?>
					
		
			</div>
		</div>
		<?php
			if($apres>$now&&current_user_can( 'read_private_posts' )){
		     echo   do_shortcode('[seatt-form event_id='.get_post_meta(get_the_ID(),'choisir_inscription_select')[0].']');
			}
		?>
	</div>
	
	
	
	</div><!-- .entry-content -->
	

	<div class="clear"></div>
</article><!-- #post-## -->
