<?php

/**
 * Custom slider and the featured posts for the theme.
 */

if ( !function_exists( 'fxpredator_featured_posts' ) ) :

    function fxpredator_featured_posts() {

        $category = get_theme_mod( 'slider_category', '' );

        $slider_posts = new WP_Query( array(
                'posts_per_page' => 1,
                'cat'	=>	$category,
				'orderby' => 'modified',
            )
        ); 
        
        ?>

        <div class="awaken-featured-container">
            <div class="awaken-featured-slider">
                <section class="slider">
                    <div class="flexslider">
                        <ul class="slides">
                            <?php while( $slider_posts->have_posts() ) : $slider_posts->the_post(); ?>

                                <li>
                                    <div class="awaken-slider-container" style="<?php if ( has_post_thumbnail() ) { echo 'background-image: url(\'';the_post_thumbnail_url('featured-slider');echo '\')'; }?>">                                        <div class="info_line">
	                                         <?php the_category(); ?>
											 <?php the_author(); ?> 	<br/>										 
											 <?php the_modified_date(); ?> 
                                        </div>

                                         <a href="<?php the_permalink(); ?>" rel="bookmark" class="hrefHover">
                                            	
                                        </a>										
                                        <div class="awaken-slider-details-container">
                                            <a href="<?php the_permalink(); ?>" rel="bookmark"><span class="awaken-slider-title"><?php the_title(); ?></span></a>
                                        </div>
                                    </div>
                                </li>

                            <?php endwhile; ?>
                        </ul>
                    </div>
                </section>
            </div><!-- .awaken-slider -->
            <div class="awaken-featured-posts">
                <?php

                $method = get_theme_mod( 'fposts_display_method', 'category' );

                if ( $method == "sticky" ) {
                    
                    $args = array(
                        'posts_per_page'        => 2,
                        'post__in'              => get_option( 'sticky_posts' ),
                        'ignore_sticky_posts'   => 1,
						'orderby' => 'modified',
                    );

                } else {
                    
                    $fposts_category = get_theme_mod( 'featured_posts_category', '' );

                    $args = array(
                        'posts_per_page'        => 2,
                        'cat'                   => $fposts_category,
                        'ignore_sticky_posts'   => 1,
						'orderby' => 'modified',
                    );

                }

                $fposts = new WP_Query( $args );

                while( $fposts->have_posts() ) : $fposts->the_post(); ?>

                    <div class="afp">
                        <figure class="afp-thumbnail"  style="<?php if ( has_post_thumbnail() ) { echo 'background-image: url(\'';the_post_thumbnail_url('featured-slider');echo '\')'; }?>">
		                   
                        </figure>
                         <div class="info_line">
                             <?php the_category(); ?>
							 <?php the_author(); ?> 	<br/>										 
							 <?php the_modified_date(); ?> 
                        </div>
                        <a href="<?php the_permalink(); ?>" rel="bookmark" class="hrefHover">
                                            	
                        </a>	
                        <div class="afp-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </div>
                    </div>

                <?php endwhile; ?>

            </div>
        </div>
    <?php
    }

endif;