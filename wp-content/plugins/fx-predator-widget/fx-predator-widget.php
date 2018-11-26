<?php

/*
Plugin Name:  FX Predator Widgets
Version: 1.4.0
Plugin URI: http://sabrina-leroy.fr
Author: Sabrina
Author URI: http://sabrina-leroy.fr
Description: Plugin pour les widgets du site FX Predator
*/

class evenementAVenir extends WP_Widget {
 
    function evenementAVenir()
    {
        // Constructeur
        parent::WP_Widget(false, $name = 'Évènements à Venir', array("description" => 'Liste des évènements a venir'));
    }
 
    function widget($args, $instance)
    {
        // Contenu du widget à afficher
        // Extraction des paramètres du widget
       global $wpdb;
    extract( $args );
 
    // Récupération de chaque paramètre
    $title = apply_filters('widget_title', $instance['title']);
    $nb_display = $instance['nb_display'];
 
   
 
    /* Début de notre script
    /* Nous allons ici récupérer un webservice de Flickr, un fichier XML
    /* Puis le parcourir
    /* Et afficher un nombre défini de photos */
    ?>
    
    		 <?php
	    
	    $now = date('Ymd');
	  
		$arguments = array(
			'posts_per_page'	=> $nb_display,
			'post_type' 		=> 'evenement',
			'meta_key'			=> 'date_apres',
			'orderby'			=> 'meta_value_num',
			'order'				=> 'ASC',
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
	
	if ( $query->have_posts() )  : 
	 // Voir le détail sur ces variables plus bas
    echo $before_widget;
 
    // On affiche un titre si le paramètre est rempli
    if($title)
        echo $before_title . $title . $after_title;
        ?>
	<ul>
	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $query->have_posts() ) : $query->the_post(); 
		get_template_part( 'content-sidebarEvenement' );
		 endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>
	</ul>
    <div class="clear"></div>
    
    <?php echo $after_widget;
	    
	 else :
		
	endif;   
     
 
 
    
    }
 
    function update($new_instance, $old_instance)
    {
        // Modification des paramètres du widget
            $instance = $old_instance;
 
	    /* Récupération des paramètres envoyés */
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['nb_display'] = $new_instance['nb_display'];
	 
    return $instance;
    }
 
    function form($instance)
    {
        // Affichage des paramètres du widget dans l'admin
        $title = esc_attr($instance['title']);
		$nb_display = esc_attr($instance['nb_display']);
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Titre:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nb_display'); ?>">
                <?php _e('Nombre d\'articles:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('nb_display'); ?>" name="<?php echo $this->get_field_name('nb_display'); ?>" type="text" value="<?php echo $nb_display; ?>" />
            </label>
        </p>
    <?php
    }
 
}
add_action('widgets_init', create_function('', 'return register_widget("evenementAVenir");'));




class evenementPasse extends WP_Widget {
 
    function evenementPasse()
    {
        // Constructeur
        parent::WP_Widget(false, $name = 'Évènements Passés', array("description" => 'Liste des évènements Passés'));
    }
 
    function widget($args, $instance)
    {
        // Contenu du widget à afficher
        // Extraction des paramètres du widget
       global $wpdb;
	    extract( $args );
	 
	    // Récupération de chaque paramètre
	    $title = apply_filters('widget_title', $instance['title']);
	    $nb_display = $instance['nb_display'];
	 
	   
	 
	    /* Début de notre script
	    /* Nous allons ici récupérer un webservice de Flickr, un fichier XML
	    /* Puis le parcourir
	    /* Et afficher un nombre défini de photos */
	    ?>
	   
	    		 <?php
		    $now = date('Ymd');
		  
			$arguments = array(
				'posts_per_page'	=> $nb_display,
				'post_type' 		=> 'evenement',
				'meta_key'			=> 'date_apres',
				'orderby'			=> 'meta_value_num',
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
		
		if ( $query->have_posts() )  : 
		
		// Voir le détail sur ces variables plus bas
	    echo $before_widget;
	 
	    // On affiche un titre si le paramètre est rempli
	    if($title)
	        echo $before_title . $title . $after_title;
		
		?>
		 <ul>
		<!-- pagination here -->
	
		<!-- the loop -->
		<?php while ( $query->have_posts() ) : $query->the_post(); 
			get_template_part( 'content-sidebarEvenement' );
			 endwhile; ?>
		<!-- end of the loop -->
	
		<!-- pagination here -->
	
		<?php wp_reset_postdata(); ?>
		 </ul>
	    <div class="clear"></div>
	     <?php echo $after_widget;
		 else : 
			
		 endif; 
	    
	 
	 
	   
    }
 
    function update($new_instance, $old_instance)
    {
        // Modification des paramètres du widget
            $instance = $old_instance;
 
	    /* Récupération des paramètres envoyés */
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['nb_display'] = $new_instance['nb_display'];
	 
		return $instance;
    }
 
    function form($instance)
    {
        // Affichage des paramètres du widget dans l'admin
        $title = esc_attr($instance['title']);
		$nb_display = esc_attr($instance['nb_display']);
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Titre:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nb_display'); ?>">
                <?php _e('Nombre d\'articles:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('nb_display'); ?>" name="<?php echo $this->get_field_name('nb_display'); ?>" type="text" value="<?php echo $nb_display; ?>" />
            </label>
        </p>
    <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("evenementPasse");'));


?>
