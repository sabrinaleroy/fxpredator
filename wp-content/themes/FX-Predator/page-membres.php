<?php
/*
Template Name: Membres
*/



get_header(); ?>
<div class="row">
<?php is_rtl() ? $rtl = 'awaken-rtl' : $rtl = ''; ?>
<div class="col-xs-12 col-sm-12 col-md-8 <?php echo $rtl ?>">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					$args = array(
						'role__in'     => array("administrator","editor","author"),
						'orderby'      => 'display_name',
						'order'        => 'ASC',
					 );
					$user_query = get_users($args);
						
						// User Loop
						if ( ! empty( $user_query ) ) {
							foreach ( $user_query as $user ) {
								
								$imgURL = get_field('photo_de_profil',"user_".$user->id);
								$background = "";$role = "";
								if($imgURL){
									$background = 'style="background-image: url(\''.$imgURL.'\');"';
								}
								/**
								if($user->roles[0]=="editor"){
									$role = "<span>Modérateur</span>";
								}*/
								$html_li .='<li class="team_player" '.$background.'>
									<a href="'.get_author_posts_url( $user->ID ).'"><h3>'.$user->display_name.$role.'</h3></a>
								</li>';
								
							}
							echo '<ul class="list_membre">'.$html_li.'</ul><div class="clear"></div>';
						} else {
							echo 'No users found.';
						}

				?>

			<?php endwhile; // end of the loop. ?>
			
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .bootstrap cols -->
<div class="col-xs-12 col-sm-6 col-md-4">
	<?php get_sidebar(); ?>
</div><!-- .bootstrap cols -->
</div><!-- .row -->
<?php get_footer(); ?>
