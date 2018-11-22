<?php
global $wpdb;

// Clean down event ID by checking if numeric, then casting to integer
if (isset($_GET['event_id'])) {
	if (is_numeric($_GET['event_id'])) {
		$event_id = intval($_GET['event_id']);
	} else {
		$event_id = '';
	}
} else {
	$event_id = '';
}
	
?>
<div class="wrap">  
<?php include("seatt_header.php"); ?>

<?php echo "<h1>Inscription à un évènement</h1>";
// Kill page if no event_id, and check to see if in DB
if ($event_id == '') {
	die("<div class=\"error\"><p><strong>No event ID specified, please reload the main SEATT page.</strong></p></div>");
} else {		  
	// Check to see whether event exists
	$seatt_this_limit = $wpdb->get_var($wpdb->prepare("SELECT event_limit FROM ".$wpdb->prefix."seatt_events WHERE id = %d", $event_id));
	if ($seatt_this_limit == NULL) {
		die("<div class=\"error\"><p><strong>No valid event found, please reload the main SEATT page.</strong></p></div>");
	}
}

// Remove all participants
if (isset($_GET['clear_event'])) {
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d", $event_id));
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."seatt_days_attendees WHERE event_id = %d", $event_id));
	?>
	<div class="updated">
    	<p><strong></strong></p>
    </div>
	<?php
}
		  
// Register user to event
if (isset($_POST['seatt_add_user'])) {
	$_POST = stripslashes_deep($_POST);
	$add_username = sanitize_text_field($_POST['seatt_add_user']);
	  
	// Check username exists in wordpress system
	if (username_exists($add_username) != NULL) {
		// Check whether user is already registered for event
		$add_userid = username_exists($add_username);
		if ($wpdb->get_var($wpdb->prepare("SELECT user_id FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d AND user_id = %d", $event_id, $add_userid)) == NULL) {
			
			// Sanitise any comments
			$user_comment = sanitize_text_field($_POST['seatt_add_comment']);
			if (strlen(trim($user_comment)) == 0) {
				$user_comment = "";
			}
			
			// Check there's still space to add user
			if ($seatt_this_limit > ($wpdb->get_var($wpdb->prepare("SELECT count(user_id) FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d", $event_id)))) {
				
				// Check the event is active and not expired
				if ($wpdb->get_var($wpdb->prepare("SELECT event_status FROM ".$wpdb->prefix."seatt_events WHERE id = %d AND event_expire >= %d", $event_id, time())) == 1) {
			
					// Create registration
					$wpdb->insert($wpdb->prefix.'seatt_attendees', array( 'event_id' => $event_id, 'user_id' => $add_userid, 'user_comment' => $user_comment), array('%d', '%d', '%s') );
					?>
					<div class="updated">
						<p><strong>User <em><?php echo esc_html($add_username); ?></em> registered to event.</strong></p>
					</div>
					<?php 
				} else {
					// Else if event not active
					?>
					<div class="error">
						<p><strong>User <em><?php echo esc_html($add_username); ?></em> was not added, as the event status is closed, or the event closing date has passed.</strong></p>
					</div>
					<?php						
				}
			} else {
				// Else if no space to add the user
				?>
				<div class="error">
					<p><strong>User <em><?php echo esc_html($add_username); ?></em> was not added, as the event is fully subscribed.</strong></p>
				</div>
				<?php
			}
		} else {
			// Else if the user was already registered for the event
			?>
			<div class="updated">
				<p><strong>User <em><?php echo esc_html($add_username); ?></em> was already registered for the event.</strong></p>
			</div>
			<?php
		}
	} else {
		// Else if username doesn't exist
		?>
		<div class="error">
			<p><strong>User <em><?php echo esc_html($add_username); ?></em> not found.</strong></p>
		</div>
		<?php
	}
}
// END
// Edit event details
elseif ((isset($_POST['seatt_name'])) && (!isset($_POST['seatt_add_user']))) {
		
	$_POST = stripslashes_deep($_POST);  
	$event_name = sanitize_text_field($_POST['seatt_name']);
	$event_desc = sanitize_text_field($_POST['seatt_desc']);
	$event_limit = intval($_POST['seatt_limit']);
	$event_status = 1;
	$event_start = strtotime($_POST['seatt_start']);
	$event_expire = strtotime($_POST['seatt_expire']);
	$event_resto = intval($_POST['IsResto']);
	$event_resto_date = sanitize_text_field($_POST['resto']);
	$event_hotel = intval($_POST['IsHotel']);
	$presence =$_POST['presence'];
	$hotel =$_POST['hotel'];
	$error=false;
	$error_message=array();
	
	// Ensure required fields contain values, update if true
	if(strlen(trim($event_name)) < 0){
		$error=true;
		$error_message[]="Le nom de l'évènement est vide.";
	}
	if(!($event_start)){
		$error=true;
		$error_message[]="La date d'ouverture des inscriptions est vide ou incorrecte";
	}
	if(!($event_expire)){
		$error=true;
		$error_message[]="La date de fermeture des inscriptions est vide ou incorrecte";
	}
	if($presence[0]==""){
		$error=true;
		$error_message[]="Il faut au moins un jour de présence renseigné";
	}
	if($event_hotel==1 && $hotel[0]==""){
		$error=true;
		$error_message[]="Il faut au moins un soir d'hôtel renseigné si un hôtel est à prévoir";
	}
	if($event_resto==1 && $event_resto_date==""){
		$error=true;
		$error_message[]="Il faut un soir de restaurant renseigné si un restaurants est à prévoir";
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
		$users = $wpdb->get_results($wpdb->prepare("SELECT id, user_id, user_comment FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d ORDER BY id ASC", $event_id));
		if(count($users)>0){
			$wpdb->update($wpdb->prefix.'seatt_events', array( 
				'event_name' => $event_name, 
				'event_desc' => $event_desc, 
				'event_limit' => $event_limit, 
				'event_start' => $event_start, 
				'event_expire' => $event_expire, 
				'event_status' => $event_status  
			), array( 'id' => $event_id ), array('%s', '%s', '%d', '%s', '%s', '%d', '%d', '%s','%d'));
		}else{
			$wpdb->update($wpdb->prefix.'seatt_events', array( 
				'event_name' => $event_name, 
				'event_desc' => $event_desc, 
				'event_limit' => $event_limit, 
				'event_start' => $event_start, 
				'event_expire' => $event_expire, 
				'event_status' => $event_status,
				'event_resto' => $event_resto,
				'event_resto_date' => $event_resto_date ,
				'event_hotel' => $event_hotel   
			), array( 'id' => $event_id ), array('%s', '%s', '%d', '%s', '%s', '%d', '%d', '%s','%d'));
			
			
			$wpdb->delete($wpdb->prefix.'seatt_days',array('event_id' => $event_id, 'days_type' => "presence"));
			foreach($presence as $jour_presence){
				if($jour_presence!=""){
					$wpdb->insert($wpdb->prefix.'seatt_days', array( 'event_id' => $event_id, 'days_type' => 'presence', 'days_date' => $jour_presence), array('%d', '%s', '%s') );
	
				}
			}
			
			$wpdb->delete($wpdb->prefix.'seatt_days',array('event_id' => $event_id, 'days_type' => "hotel"));
			if($event_hotel==1){
				foreach($hotel as $jour_hotel){
					if($jour_hotel!=""){
						$wpdb->insert($wpdb->prefix.'seatt_days', array( 'event_id' => $event_id, 'days_type' => 'hotel', 'days_date' => $jour_hotel), array('%d', '%s', '%s') );
					}
				}
			}
		}

		
		?>
		<div class="updated">
        	<p><strong>Event <em><?php echo esc_html($event_name); ?></em> updated.</strong></p>
        </div>
		<?php
	} 
}
// END

// Remove user registration
// This needs improving
if (isset($_GET['remove_attendee'])) {
	if (is_numeric($_GET['remove_attendee'])) {
		$remove_attendee = intval($_GET['remove_attendee']);
		$user = $wpdb->get_row($wpdb->prepare("SELECT user_id FROM ".$wpdb->prefix."seatt_attendees WHERE id = %d", $remove_attendee));
		if ($wpdb->delete($wpdb->prefix.'seatt_attendees', array('id'=>$remove_attendee, 'event_id' => $event_id), array('%d', '%d'))) {
			$place = intval($_GET['place']);
			$wpdb->delete($wpdb->prefix.'seatt_days_attendees', array('user_id'=>$user->user_id, 'event_id' => $event_id), array('%d', '%d'));
			?>
			<div class="updated">
				<p><strong>Attendee <em><?php echo esc_html($place); ?></em> removed.</strong></p>
			</div>
			<?php
		}
	}
}
// END

// GET EVENT DETAILS FOR PAGE
$event = $wpdb->get_row($wpdb->prepare("SELECT id, event_name, event_desc, event_limit, event_start, event_expire, event_status, event_resto , event_resto_date , event_hotel  FROM ".$wpdb->prefix."seatt_events WHERE id = %d", $event_id));

// Check to see if a value has been returned   
if ($event->id != "") {
	$disabled="";
	$users = $wpdb->get_results($wpdb->prepare("SELECT id, user_id, user_comment FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d ORDER BY id ASC", $event_id));
	if(count($users)>0){
		$disabled="disabled";
		echo "<p>Il y a déjà des utilisateurs inscrits à cet évènement, le questionnaire de présence ne peut pas être modifié, seulement les dates d'ouverture et de fermeture, et le nombre limite de participants.</p>";
	}
?>
	
	
	
	<div id="poststuff">
		<form name="seatt_edit_form" method="post" action="admin.php?page=seatt_events_edit&event_id=<?php echo $event_id; ?>">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" style="position: relative;">
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Informations Générales</span></h2>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr class"form-field form-required ">
										<td>
											<label for="seatt_name">Nom de l'évènement </label>
										</th>
										<td>
											<input name="seatt_name" size="50" type="text" id="seatt_name" value="<?php echo esc_html($event->event_name); ?>">							
											<p class="description">Ne sera pas affiché sur le site. Sert à sélectionner le formulaire sur la page de l'évènement. Sois explicite ! (année, nom, mois si besoin, etc.)</p>
										</td>
									</tr>
									<tr>
										<td>
											<label for="seatt_limit">Limite de Participants</label>
										</th>
										<td>
											<input name="seatt_limit" type="text" id="seatt_limit" value="<?php echo esc_html($event->event_limit); ?>">
											<p class="description">(0 = Illimité)</p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Présence</span></h2>
						<div class="inside">
							<p id="Presence">
								Quels jours de présence sont possibles ? (Vendredi, Samedi, Dimanche ou date) *<br />
								<?php 
								// GET EVENT DETAILS FOR PAGE
								$days_presence = $wpdb->get_results($wpdb->prepare("SELECT id, event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='presence' order by id", $event_id));
									$iPresence = 0;
									echo '<span><input type="text" id="presence[0]" '.$disabled.' size="20" name="presence[0]" value="'.$days_presence[0]->days_date.'" placeholder="Vendredi ou 01/01/2017" />
										<a href="#" id="addPresence'.$disabled.'">Ajouter un autre jour</a></span><br />';
									foreach($days_presence as $jour){
										if($iPresence >0){
											echo '<span><input type="text" id="presence['.$iPresence.']" '.$disabled.' size="20" name="presence['.$iPresence.']" value="'.$jour->days_date.'" placeholder="Vendredi ou 01/01/2017" /></label> <a href="#" id="remPresence'.$disabled.'">Supprimer</a><br/></span>';
										}
										$iPresence++;
									}
								
								?>
							</p>
						</div>
					</div>
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Restaurant</span></h2>
						<div class="inside">
							<p>
								Un restaurant est à prévoir ? 
								<select name="IsResto"  <?php echo $disabled; ?> id="IsResto">
									<option value="0" <?php selected( $event->event_resto, '0' ); ?>>Non</option>
									<option value="1" <?php selected( $event->event_resto, '1' ); ?>>Oui</option>
								</select>
							</p>
							<p id="Resto" <?php if($event->event_resto==1){ echo 'class="show"'; }?>>
								Quel soir se fera le resto ? (Vendredi soir, Samedi soir, Dimanche soir ou date) *<br />
								<input type="text" id="resto" <?php echo $disabled; ?> size="20" name="resto" value="<?php if($event->event_resto==1){ echo $event->event_resto_date; }?>" placeholder="Vendredi soir ou 01/01/2017 au soir" />
							</p>						
						</div>
					</div>
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Hôtel</span></h2>
						<div class="inside">
							<p>
								Un hôtel est à prévoir (évènement sur deux jours ou plus): 
								<select name="IsHotel" <?php echo $disabled; ?> id="IsHotel">
									<option value="0 <?php selected( $event->event_resto, '0' ); ?>">Non</option>
									<option value="1" <?php selected( $event->event_hotel, '1' ); ?>>Oui</option>
								</select>
							</p>
							<p id="Hotel" <?php if($event->event_hotel==1){ echo 'class="show"'; }?>>
								Quels soirs d'hôtels sont possibles ? (Vendredi soir, Samedi soir, Dimanche soir ou date) *<br />
								
								
								
								<?php 
									// GET EVENT DETAILS FOR PAGE
							if($event->event_hotel==1){ 
							$days_hotel = $wpdb->get_results($wpdb->prepare("SELECT id, event_id , days_type , days_date FROM ".$wpdb->prefix."seatt_days WHERE event_id = %d and days_type='hotel' order by id", $event_id));
								$ihotel = 0;
								echo '<span><input type="text" '.$disabled.' id="hotel[0]" size="20" name="hotel[0]" value="'.$days_presence[0]->days_date.'" placeholder="Vendredi ou 01/01/2017" />
									<a href="#" id="addHotel'.$disabled.'">Ajouter un autre soir</a></span><br />';
								foreach($days_hotel as $jour){
									if($ihotel >0){
										echo '<span><input type="text" '.$disabled.' id="hotel['.$ihotel.']" size="20" name="hotel['.$ihotel.']" value="'.$jour->days_date.'" placeholder="Vendredi ou 01/01/2017" /></label> <a href="#" id="remHotel'.$disabled.'">Supprimer</a><br/></span>';
									}
									$ihotel++;
								}
							}else{
								?>
								<input type="text" id="hotel[0]" size="20" name="hotel[0]" value="" placeholder="Vendredi soir ou 01/01/2017 au soir" />
							<a href="#" id="addHotel">Ajouter un autre soir</a><br />
								
							<?php
								
							}
						
							?>
							</p>				
						</div>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div id="choisir_date" class="postbox ">
						<h2 class="hndle ui-sortable-handle">
							<span>Date Inscriptions</span>
						</h2>
						<div class="inside">
							<p>
								<label for="seatt_start">Ouverture des inscriptions :</label>
								<br>
								<input name="seatt_start" type="text" id="seatt_start" value="<?php echo date("d-m-Y H:i", esc_html($event->event_start)); ?>" />
								<span class="howto">
									Exemple: la date du serveur est <br/><a onclick="document.getElementById('seatt_start').value='<?php echo date("d-m-Y H:i", current_time('timestamp')); ?>';"><?php echo date("d-m-Y H:i", current_time('timestamp')); ?></a> (jj-mm-aaaa hh:mm)
								</span>
							</p>
							<p>
								<label for="seatt_expire">Fermeture des inscriptions :</label>
								<br>
								<input name="seatt_expire" type="text" id="seatt_expire" value="<?php echo date("d-m-Y H:i", esc_html($event->event_expire)); ?>" />
								<span class="howto">
									Exemple: dans une semaine la date sera <br/><a onclick="document.getElementById('seatt_expire').value='<?php echo date("d-m-Y H:i", current_time('timestamp') + 604800); ?>';"><?php echo date("d-m-Y H:i", current_time('timestamp') + 604800); ?></a> (jj-mm-aaaa hh:mm)
								</span>
							</p>
						
						</div>
					</div>
					<input id="publish" class="button button-primary button-large" type="submit" value="Enregistrer le formulaire d'inscription" name="Submit">


				</div>
			</div>
		</form>
	</div>
	

	<hr class="clear" /><br />
	<a class="red" href="admin.php?page=seatt_events&event_id=<?php echo $event_id; ?>&remove_event=1">Supprimer l'évènement</a> | <a href="admin.php?page=seatt_events_edit&event_id=<?php echo $event_id; ?>&clear_event=1" class="red">Effacer tous les participants</a>
	<br/>Ces changements sont permanents, aucun retour en arrière possible.

	<h3>
	Participants:</h3>
	<p>
	<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="wp-list-table widefat fixed striped posts participants">
		<thead>
			<tr>
				<th rowspan="2" scope="col" class="titre">Nom Utilisateur</th>
				<th colspan="<?php echo $iPresence; ?>" class="jour titre" scope="col">Jour(s) de présence</th>
				<?php 
					if($event->event_hotel==1){ 
						?>
						<th colspan="<?php echo $ihotel; ?>" class="jour titre" scope="col">Soir(s) d'hôtel</th>
						<?php
					}
					if($event->event_resto==1){
						?>
						<th scope="col" class="jour titre">Participation Resto</th>
						<?php
					}
				?>
				<th rowspan="2" scope="col" class="titre">Comment</th>
				<th rowspan="2" scope="col" class="titre">Options</th>
			</tr>
			<tr>
			<?php 
					foreach($days_presence as $jour){
						echo '<th>'.$jour->days_date.'</th>';
					}
					if($event->event_hotel==1){ 
						foreach($days_hotel as $jour){
							echo '<th>'.$jour->days_date.'</th>';
						}
					}
					if($event->event_resto==1){
						echo '<th>'.$event->event_resto_date.'</th>';
					}
				
				?>
			</tr>
		</thead>
		<?php
		$users = $wpdb->get_results($wpdb->prepare("SELECT id, user_id, user_comment, resto FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d ORDER BY id ASC", $event_id));
		$num = 1;$total=array();
		foreach($days_presence as $jour){
			$total[$jour->id]=0;
		}
		if($event->event_hotel==1){ 
			foreach($days_hotel as $jour){
				$total[$jour->id]=0;
			}
		}
		if($event->event_resto==1){
			$total['resto']=0;
		}
		foreach ($users as $user) {
			$user_info = get_userdata($user->user_id);
			?>
			<tr>
				<td><?php echo $user_info->user_login; ?></td>
				
				<?php 
				foreach($days_presence as $jour){
					$day_user = $wpdb->get_row($wpdb->prepare("SELECT id,attendance, event_id,user_id FROM ".$wpdb->prefix."seatt_days_attendees WHERE event_id = %d AND days_id = %d AND user_id = %d ORDER BY id ASC", $event_id,$jour->id,$user->user_id));
					if($day_user->attendance==0){
						echo  '<td class="non">Non</td>';
					}else{
						echo  '<td class="oui">Oui</td>';
						$total[$jour->id]++;
					}
				}
				if($event->event_hotel==1){ 
					foreach($days_hotel as $jour){
						$day_user = $wpdb->get_row($wpdb->prepare("SELECT id,attendance, event_id,user_id FROM ".$wpdb->prefix."seatt_days_attendees WHERE event_id = %d AND days_id = %d AND user_id = %d ORDER BY id ASC", $event_id,$jour->id,$user->user_id));
						if($day_user->attendance==0){
							echo  '<td class="non">Non</td>';
						}else{
							echo  '<td class="oui">Oui</td>';
							$total[$jour->id]++;
						}
					}
				}
				if($event->event_resto==1){
					if($user->resto==0){
						echo  '<td class="non">Non</td>';
					}else{
						echo  '<td class="oui">Oui</td>';
						$total['resto']++;
					}
				}
			
			?>
				
				
				<td><?php echo esc_html($user->user_comment); ?></td>
				<td><a class="red" href="admin.php?page=seatt_events_edit&event_id=<?php echo $event_id; ?>&remove_attendee=<?php echo $user->id; ?>&place=<?php echo $num; ?>">Supprimer</a></td>
			</tr>
			<?php
			$num++;
		}
		
		?>
		<thead>
			<tr>
				<th rowspan="2" scope="col" class="titre"></th>
				<th colspan="<?php echo $iPresence; ?>" class="jour titre" scope="col">Jour(s) de présence</th>
				<?php 
					if($event->event_hotel==1){ 
						?>
						<th colspan="<?php echo $ihotel; ?>" class="jour titre" scope="col">Soir(s) d'hôtel</th>
						<?php
					}
					if($event->event_resto==1){
						?>
						<th scope="col" class="jour titre">Participation Resto</th>
						<?php
					}
				?>
				<th rowspan="2" scope="col" class="titre"></th>
				<th rowspan="2" scope="col" class="titre"></th>
			</tr>
			<tr>
			<?php 
					foreach($days_presence as $jour){
						echo '<th >'.$jour->days_date.'</th>';
					}
					if($event->event_hotel==1){ 
						foreach($days_hotel as $jour){
							echo '<th>'.$jour->days_date.'</th>';
						}
					}
					if($event->event_resto==1){
						echo '<th>'.$event->event_resto_date.'</th>';
					}
				
				?>
			</tr>
		</thead>
		<tr>
			<th class="total titre" rowspan="2" scope="col">Total</th>
			<?php 
				foreach($days_presence as $jour){
					echo '<th class="total titre">'.$total[$jour->id].'</th>';
				}
				if($event->event_hotel==1){ 
					foreach($days_hotel as $jour){
						echo '<th class="total titre">'.$total[$jour->id].'</th>';
					}
				}
				if($event->event_resto==1){
					echo '<th class="total titre">'.$total['resto'].'</th>';
				}
			
			?>				
			<th class="total titre" scope="col"></th>
			<th  class="total titre"scope="col"></th>
		</tr>
	</table>
	</p>
	<p style="clear:both;"><br/><br/><br/><strong>Emails des Participants:</strong></p>
	<p>Pour garder ce plugin simple, il n'y a pas de système de mail automatique. Voici la liste des emails si besoin :</p>
	<blockquote>
	<p>
	<?php 
	$num = 1;
	foreach ($users as $user) {
		$user_info = get_userdata($user->user_id);
		echo $user_info->user_email . "; ";
		$num++;
	}
	?>
	</p>
	</blockquote>
	<?php
	} 
?>          
</div>