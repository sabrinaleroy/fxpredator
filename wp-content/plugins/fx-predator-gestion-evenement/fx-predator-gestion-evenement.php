<?php
/*
Plugin Name: FX Predator Gestion des Évenements
Version: 1.0
Plugin URI: http://sabrina-leroy.fr
Author: Sabrina
Author URI: http://sabrina-leroy.fr
Description: Gestion des évèments pour FX Predator, basé sur simple-event-attendance
*/
global $seatt_db_version;
$seatt_db_version = "1.1.2";
include('seatt_events_include.php');

function seatt_install() {
	global $wpdb;
	global $seatt_db_version;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   
	// Install tables
	$sql = "CREATE TABLE " . $wpdb->prefix . "seatt_events (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		event_name text NOT NULL,
		event_desc text NOT NULL,
		event_limit mediumint(9) NOT NULL,
		event_reserves mediumint(9) NOT NULL,
		event_start int NOT NULL,
		event_expire int NOT NULL,
		event_status mediumint(1) NOT NULL,
		event_resto mediumint(1) NOT NULL,
	    event_resto_date text,
	    event_hotel mediumint(1) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		CREATE TABLE " . $wpdb->prefix . "seatt_attendees (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			event_id mediumint(9) NOT NULL,
			user_id int(9) DEFAULT NULL,
			user_comment text NOT NULL,
			resto int(9) DEFAULT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		CREATE TABLE IF NOT EXISTS fx_wp_seatt_days (
		  id mediumint(9) NOT NULL,
		  event_id mediumint(9) NOT NULL,
		  days_type text,
		  days_date text,
		  UNIQUE KEY id (id)
		) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;
		CREATE TABLE IF NOT EXISTS fx_wp_seatt_days_attendees (
		  id mediumint(9) NOT NULL,
		  event_id mediumint(9) NOT NULL,
		  days_id mediumint(9) NOT NULL,
		  user_id int(9) DEFAULT NULL,
		  attendance int(11) NOT NULL,
		  UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	dbDelta($sql);
 
   add_option("seatt_db_version", $seatt_db_version);
   
    $installed_ver = get_option( "seatt_db_version" );

   if ($installed_ver != $seatt_db_version) {

      $sql = "CREATE TABLE " . $wpdb->prefix . "seatt_events (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		event_name text NOT NULL,
		event_desc text NOT NULL,
		event_limit mediumint(9) NOT NULL,
		event_reserves mediumint(9) NOT NULL,
		event_start int NOT NULL,
		event_expire int NOT NULL,
		event_status mediumint(1) NOT NULL,
		event_resto mediumint(1) NOT NULL,
	    event_resto_date text,
	    event_hotel mediumint(1) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		CREATE TABLE " . $wpdb->prefix . "seatt_attendees (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		event_id mediumint(9) NOT NULL,
		user_id int(9) DEFAULT NULL,
		user_comment text NOT NULL,
		resto int(9) DEFAULT NULL,
		UNIQUE KEY id (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
		CREATE TABLE IF NOT EXISTS fx_wp_seatt_days (
		  id mediumint(9) NOT NULL,
		  event_id mediumint(9) NOT NULL,
		  days_type text,
		  days_date text,
		  UNIQUE KEY id (id)
		) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;
		CREATE TABLE IF NOT EXISTS fx_wp_seatt_days_attendees (
		  id mediumint(9) NOT NULL,
		  event_id mediumint(9) NOT NULL,
		  days_id mediumint(9) NOT NULL,
		  user_id int(9) DEFAULT NULL,
		  attendance int(11) NOT NULL,
		  UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
      dbDelta($sql);

      update_option( "seatt_db_version", $seatt_db_version );
  }
}

function seatt_uninstall() {
	global $wpdb;
   
	// Remove tables
	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "seatt_events");
	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "seatt_attendees");
 	
	// Remove option
   delete_option("seatt_db_version");
}


register_activation_hook( __FILE__, 'seatt_install' );
register_uninstall_hook( __FILE__, 'seatt_uninstall' );

function seatt_update_db_check() {
    global $seatt_db_version;
    if (get_site_option('seatt_db_version') != $seatt_db_version) {
        seatt_install();
    }
}

add_action('seatt_loaded', 'seatt_update_db_check');

function seatt_admin() {  
	include('seatt_events_admin.php');
}

function seatt_admin_add() {   
	include('seatt_events_add.php');
}

function seatt_admin_edit() {   
	include('seatt_events_edit.php');
}

function seatt_admin_actions() {
	add_menu_page("Gestion des Inscriptions aux évènements", "Formulaires d'inscription", "level_3", "seatt_events", "seatt_admin" );
	add_submenu_page( "seatt_events", "SEATT Events View", "Voir les Formulaires d'inscription", "level_3", "seatt_events", "seatt_admin" );
	add_submenu_page( "seatt_events", "SEATT Events Add", "Ajouter un Formulaire d'inscription", "level_3", "seatt_events_add", "seatt_admin_add" );
	add_submenu_page( "seatt_events", "SEATT Events Edit", "Editer un Formulaire d'inscription", "level_3", "seatt_events_edit", "seatt_admin_edit" );
}

add_action('admin_menu', 'seatt_admin_actions');

function seatt_func( $atts ) {
	extract( shortcode_atts( array(
		'event_id' => '1',
	), $atts ) );

	return seatt_form("{$event_id}");
}
add_shortcode( 'seatt-form', 'seatt_func' );

?>