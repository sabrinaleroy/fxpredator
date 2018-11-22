<?php
function seatt_form($event_id) {
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	$seatt_error = "";
	
	// Clean down event ID by checking if numeric, then casting to integer
	if (isset($event_id)) {
		if (is_numeric($event_id)) {
			$event_id = intval($event_id);
		} else {
			$event_id = '';
		}
	} else {
		$event_id = '';
	}
	
	// Clean down form event ID by checking if numeric, then casting to integer
	if (isset($_POST['seatt_event_id'])) {
		if (is_numeric($_POST['seatt_event_id'])) {
			$form_event_id = intval($_POST['seatt_event_id']);
		} else {
			$form_event_id = '';
		}
	} else {
		$form_event_id = '';
	}

	// Continue if field isn't NULL
	if ($event_id != '') {
		
		// Get current state of event. 1 = open, 0 = closed, NULL = expired or doesn't exist
		$seatt_event_state = $wpdb->get_var($wpdb->prepare("SELECT event_status FROM ".$wpdb->prefix."seatt_events WHERE id = %d AND event_expire >= %d", $event_id, time()));
		
		// If submitted, remove registration
		if ((isset($_POST['seatt_unregister'])) && ($form_event_id == $event_id)) {
			
			// Check that the event isn't closed or expired, remove if not
			if ($seatt_event_state == 1) {
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."seatt_attendees WHERE user_id = %d AND event_id = %d", $current_user->ID,  $event_id));
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."seatt_days_attendees WHERE user_id = %d AND event_id = %d", $current_user->ID,  $event_id));
			} else {
				// If event no longer active
					$seatt_error = "Unable to remove registration as this event is closed.";
			}
		}
		
		// If submitted, add registration
		if ((isset($_POST['seatt_register'])) && ($form_event_id == $event_id)) {
			
			// If event active
			if ($seatt_event_state == 1) {
			
				// Get current number of free slots
				$seatt_this_limit = $wpdb->get_var($wpdb->prepare("SELECT event_limit FROM ".$wpdb->prefix."seatt_events WHERE id = %d", $event_id));
				$seatt_this_registered = $wpdb->get_var($wpdb->prepare("SELECT count(user_id) FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d", $event_id));
				
				// If space, add registration
				if ($seatt_this_limit > $seatt_this_registered || $seatt_this_limit==0 ) {
					
					// If not already registered
					if ($wpdb->get_var($wpdb->prepare("SELECT user_id FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d AND user_id = %d", $event_id, $current_user->ID)) == "") {
						$error=false;
						$error_message=array();
						$user_comment = stripslashes_deep(sanitize_text_field($_POST['seatt_comment']));
						$user_resto=0;
						if(isset($_POST['resto'])){
							$user_resto = intval($_POST['resto']);
							if($user_resto==-1){
								$error=true;
								$error_message[]="Merci de faire un choix pour le resto";
							}
						}
						$wpdb->delete($wpdb->prefix.'seatt_days_attendees',array('event_id' => $event_id,"user_id"=>$current_user->ID));
						
						if(isset($_POST['hotel'])){
							$user_hotel = $_POST['hotel'];
							foreach($user_hotel as $id_jour_hotel=>$jour_hotel){
								if($jour_hotel==-1){
									$error=true;
									$error_message[]="Merci de faire un choix pour les soirs d'hôtel";
								}else{
									$wpdb->insert($wpdb->prefix.'seatt_days_attendees', array( 'event_id' => $event_id, 'user_id' => $current_user->ID, 'days_id' => $id_jour_hotel, 'attendance'=> $jour_hotel), array('%d', '%d', '%d', '%d'));
								}
							}
						}
						$user_presence = $_POST['presence'];
						foreach($user_presence as $id_jour_presence=>$jour_presence){
							if($jour_hotel==-1){
								$error=true;
								$error_message[]="Merci de faire un choix pour les jours de présence";
							}else{
								$wpdb->insert($wpdb->prefix.'seatt_days_attendees', array( 'event_id' => $event_id, 'user_id' => $current_user->ID, 'days_id' => $id_jour_presence, 'attendance'=> $jour_presence), array('%d', '%d', '%d', '%d'));
							}
						}
						if($error){
							?>
					        <div class="error">
					            <ul>
					            <?php
					            	foreach($error_message as $message){
					                	echo "<li>".$message."</li>";
					            	}
								?>
					            </ul>
					        </div>
					        <?php
					
						}else{
						$wpdb->insert($wpdb->prefix.'seatt_attendees', array( 'event_id' => $event_id, 'user_id' => $current_user->ID, 'user_comment' => $user_comment, 'resto'=>$user_resto), array('%d', '%d', '%s','%d'));
						}
					} else {
						// If registered already
						$seatt_error = "You are already registered.";
					}
				} else {
					// If no space
					$seatt_error = "There are no free slots.";
				}
			} else {
				// If event no longer active
					$seatt_error = "Registration for this event is closed.";
			}
		}
		// End registration
		
		// Get event details
		$event = $wpdb->get_row($wpdb->prepare("SELECT id, event_name, event_desc, event_limit, event_start, event_expire, event_status , event_resto, event_resto_date, event_hotel FROM ".$wpdb->prefix."seatt_events WHERE id = %d LIMIT 1", $event_id));
		
		// Make sure that results were returned
		if (count($event) > 0) {
			
			if ($event->id != "") {
				$attendees = $wpdb->get_var($wpdb->prepare("SELECT COUNT(user_id) FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d", $event_id));
				 
				if ($attendees == '') {
					$attendees = 0;
				}
				if (intval($event->event_limit) == 0) { 
					$event->event_limit = 100000; 
				}
				
				$seatt_output = '<div style="background:#E8E8E8;padding:10px;" class="formulaire"><h2 id="ancre_form">Formulaire d\'inscription pour : ' . esc_html($event->event_name) . '</h2>
				<div class="col-xs-12 col-sm-6 col-md-6">
				<p><strong>Dates d\'inscription:</strong> ' . esc_html($event->event_desc) . '<br />
				Les inscriptions ouvrent le <strong>' . date("d-m-Y H:i", $event->event_start) . '</strong>.<br />
				Les inscriptions ferment le <strong>' . date("d-m-Y H:i", $event->event_expire) . '</strong>.</p>
				<p>Toutes personnes non inscrites à l\'évènement avant la date de fermeture des inscriptions risque de se voir exclue de l\'évènement s\'il n\'y a pas la possibilité de demander de nouvelles places, idem pour le restaurant et l\'hôtel si il y a lieu. Dans tous les cas, cette personne doit se faire connaitre auprès du responsable de l\'évènement. Voir plus haut.</p>';
				
				if ($event->event_limit != 100000) {
					$seatt_output .= '<p>
					<strong>Max Participants:</strong> ' . intval($event->event_limit) . '</p>';
			}
			
			$seatt_output .= '<p><strong>Utilisateurs Inscrits</strong>
			';
			
			$users = $wpdb->get_results($wpdb->prepare("SELECT id, user_id , resto FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d ORDER BY id ASC", $event_id));
			$days_presence = $wpdb->get_results($wpdb->prepare("SELECT id, event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='presence' order by id", $event_id));
			$iPresence = count($days_presence);
			if($event->event_hotel==1){ 
				$days_hotel = $wpdb->get_results($wpdb->prepare("SELECT id, event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='hotel' order by id", $event_id));
				$ihotel = count($days_hotel);
			}
			$seatt_output .= '<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="wp-list-table widefat fixed striped posts participants">
		<thead>
			<tr>
				<th rowspan="2" scope="col" class="titre">Nom Utilisateur</th>
				<th colspan="'.$iPresence.'" class="jour titre" scope="col">Jour(s) de présence</th>'; 
			if($event->event_hotel==1){ 
				$seatt_output .= '<th colspan="'.$ihotel.'" class="jour titre" scope="col">Soir(s) d\'hôtel</th>';
			}
			if($event->event_resto==1){
				$seatt_output .= '<th scope="col" class="jour titre">Participation Resto</th>';
			}
			$seatt_output .= '</tr><tr>';

			foreach($days_presence as $jour){
				$seatt_output .= '<th>'.$jour->days_date.'</th>';
			}
			if($event->event_hotel==1){ 
				foreach($days_hotel as $jour){
					$seatt_output .= '<th>'.$jour->days_date.'</th>';
				}
			}
			if($event->event_resto==1){
				$seatt_output .= '<th>'.$event->event_resto_date.'</th>';
			}
			$seatt_output .= '</tr></thead>';
			$num = 1;
			foreach ($users as $user) {
				$user_info = get_userdata($user->user_id);
				$seatt_output .= '<tr><td>'.esc_html($user_info->nickname) .'</td>';
				
				foreach($days_presence as $jour){
					$day_user = $wpdb->get_row($wpdb->prepare("SELECT id,attendance, event_id,user_id FROM ".$wpdb->prefix."seatt_days_attendees WHERE event_id = %d AND days_id = %d AND user_id = %d ORDER BY id ASC", $event_id,$jour->id,$user->user_id));
					if($day_user->attendance==0){
						$seatt_output .= '<td class="non">Non</td>';
					}else{
						$seatt_output .= '<td class="oui">Oui</td>';
					}
				}
				if($event->event_hotel==1){ 
					foreach($days_hotel as $jour){
						$day_user = $wpdb->get_row($wpdb->prepare("SELECT id,attendance, event_id,user_id FROM ".$wpdb->prefix."seatt_days_attendees WHERE event_id = %d AND days_id = %d AND user_id = %d ORDER BY id ASC", $event_id,$jour->id,$user->user_id));
						if($day_user->attendance==0){
							$seatt_output .= '<td class="non">Non</td>';
						}else{
							$seatt_output .= '<td class="oui">Oui</td>';
						}
					}
				}
				if($event->event_resto==1){
					if($user->resto==0){
						$seatt_output .= '<td class="non">Non</td>';
					}else{
						$seatt_output .= '<td class="oui">Oui</td>';
					}
				}
				$seatt_output .= '</tr>';
				$num++;
			}
			
			if ($num == 1) {
				$seatt_output .= '</table>Aucun Utilisateur pour le moment </div>';
			}else{
				$seatt_output .= '</table></div>';
			}
			
			
			$seatt_output .= '<div class="col-xs-12 col-sm-6 col-md-6">';
			// Check if user has already registered
			$attending = $wpdb->get_row($wpdb->prepare("SELECT user_id, user_comment FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d AND user_id = %d", $event_id, $current_user->ID));
			
			$current_time = current_time('timestamp');
			if (($event->event_status != 1) OR ($current_time >= $event->event_expire) OR ($current_time < $event->event_start)) {
				$seatt_output .= '
				<p><strong>Inscriptions Fermées</strong></p>';
			}
			// Check if user logged in
			elseif (!is_user_logged_in()) {
				$seatt_output .= '
			<p><strong>Merci de se <a href="' . site_url('wp-login.php') . '">loguer</a> (ou <a href="' . site_url('wp-login.php?action=register') . '">créer un compte</a>) pour s\'inscrire à cet évènement. </strong></p>'; 
				} elseif ($attending) {
					// if they have already signed up
					$seatt_output .= '
					<p><strong>Tu es déjà inscrit. Tu peux voir tes réponses dans la liste des utilisateurs inscrits. Si tes réponses ne sont pas correctes désinscris toi, et recommences ton inscription. Tu peux aussi te désinscrire si tu ne souhaites plus participer à l\'évènement.</strong></p>
					<form name="seatt_unregister" method="post" action="">
					<input name="seatt_event_id" type="hidden" id="seatt_event_id" value="' . $event_id . '" size="40">
					<input name="seatt_username" type="hidden" disabled id="seatt_username" value="' . esc_html($current_user->user_login) . '" size="40" readonly="readonly"> 
					<input name="seatt_comment" type="hidden" disabled id="seatt_comment" value="' . esc_html($attending->user_comment) . '" size="40" maxlength="40" readonly="readonly">
					<input type="submit" name="seatt_unregister" id="seatt_unregister" value="Se désinscrire">
				  </p>
				</form>';
			
				} elseif ($attendees >= $event->event_limit) { 
				$seatt_output .= '<p><strong>Unfortunately all places are already reserved. </strong></p>';
				} else {
				$seatt_output .= '
				<strong>S\'inscrire pour cet évènempent:</strong>
					</p>
			
				<form name="seatt_register" method="post" action="#ancre_form">
					<input name="seatt_event_id" type="hidden" id="seatt_event_id" value="' . $event_id . '" size="40">
				  <p>Username: 
					<input name="seatt_username" type="text" disabled id="seatt_username" value="' . esc_html($current_user->nickname) . '" size="40" readonly="readonly"><br />';
				
				
				$seatt_output .= '<p>Les FX-Predator seront présents le(s) jour(s) suivant(s) sur l\'évènement. Seras-tu présent ? ';
				$days_presence = $wpdb->get_results($wpdb->prepare("SELECT id,event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='presence' order by id", $event_id));
				foreach($days_presence as $jour){
					$seatt_output .= '<br/><label>'.$jour->days_date.' :</label> 
				<select name="presence['.$jour->id.']">
					<option value="-1">Choisir</option>
					<option value="1">Oui</option>
					<option value="0">Non</option>			
				</select>';
				}
				$seatt_output .= '</p>';
				
				
				if($event->event_resto==1){
					$seatt_output .= '<p> Un Resto est prévu le '.$event->event_resto_date.'. Souhaites-tu y participer ?
					<select name="resto">
						<option value="-1">Choisir</option>
						<option value="1">Oui</option>
						<option value="0">Non</option>			
					</select></p>';
				}
				if($event->event_hotel==1){
					$seatt_output .= '<p>Veux-tu te joindre à la reservation commune pour l\'hôtel pour le(s) soir(s) suivant(s) ? ';
					$days_hotel = $wpdb->get_results($wpdb->prepare("SELECT id,event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='hotel' order by id", $event_id));
					foreach($days_hotel as $jour){
						$seatt_output .= '<br/><label>'.$jour->days_date.' : </label>
					<select name="hotel['.$jour->id.']">
						<option value="-1">Choisir</option>
						<option value="1">Oui</option>
						<option value="0">Non</option>			
					</select>';
					}
					$seatt_output .= '</p>';
				}
				
				
				$seatt_output .= '	
			Comment: 
					<input name="seatt_comment" type="text" id="seatt_comment" size="40" maxlength="40">
				  </p>
				  <p>
					<input type="submit" name="seatt_register" id="seatt_register" value="S\'inscrire">
				  </p>
				</form>';
				
				}
			} //End if no results
			
			// Add on error message if needed
			if ($seatt_error != "") {
				$seatt_error = "<br /><p style=\"color:#ff0000\"><strong>Error:</strong> " . $seatt_error . "</p><br />";
				$seatt_output .= $seatt_error;
			}
			
			$seatt_output .= '</div><div class="clear"></div></div>';
			
			return $seatt_output;
		}
	}
}

?>