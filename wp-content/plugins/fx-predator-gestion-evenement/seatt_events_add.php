<div class="wrap">  
<?php include("seatt_header.php"); ?>

          <?php    echo "<h1>Ajouter un Formulaires d'inscription à un évènement</h1>";
		  // Process form if sent
          if(isset($_POST['seatt_name'])) {
			$_POST = stripslashes_deep($_POST);  
			$event_name = sanitize_text_field($_POST['seatt_name']);
			$event_desc = sanitize_text_field($_POST['seatt_desc']);
			$event_limit = intval($_POST['seatt_limit']);
			$event_start = strtotime($_POST['seatt_start']);
			$event_expire = strtotime($_POST['seatt_expire']);
			$event_resto = intval($_POST['IsResto']);
			$event_resto_date = sanitize_text_field($_POST['resto']);
			$event_hotel = intval($_POST['IsHotel']);
			$presence =$_POST['presence'];
			$hotel =$_POST['hotel'];
			$error=false;
			$error_message=array();
						
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
				global $wpdb;
				$wpdb->show_errors();
				
				$wpdb->insert($wpdb->prefix.'seatt_events', array( 
				'event_name' => $event_name, 
				'event_desc' => $event_desc, 
				'event_limit' => $event_limit, 
				'event_start' => $event_start, 
				'event_expire' => $event_expire, 
				'event_status' => 1, 
				'event_reserves' => 0 ,
				'event_resto' => $event_resto,
				'event_resto_date' => $event_resto_date ,
				'event_hotel' => $event_hotel  ), 
				array('%s', '%s', '%d', '%s', '%s', '%d', '%d', '%d','%s','%d') );
				
				$event_id = $wpdb->insert_id;
				
				foreach($presence as $jour_presence){
					if($jour_presence!=""){
						$wpdb->insert($wpdb->prefix.'seatt_days', array( 'event_id' => $event_id, 'days_type' => 'presence', 'days_date' => $jour_presence), array('%d', '%s', '%s') );

					}
				}
				
				if($event_hotel==1){
					foreach($hotel as $jour_hotel){
						if($jour_hotel!=""){
							$wpdb->insert($wpdb->prefix.'seatt_days', array( 'event_id' => $event_id, 'days_type' => 'hotel', 'days_date' => $jour_hotel), array('%d', '%s', '%s') );
						}
					}
				}
				?>
				<div class="updated">
                	<p><strong>Évènement <?php echo esc_html($event_name); ?> créé.</strong></p>
                </div>
				<?php

			}

		  }
			  ?>
	<div id="poststuff">
		<form name="seatt_add_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
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
											<input name="seatt_name" size="50" type="text" id="seatt_name">
											<p class="description">Ne sera pas affiché sur le site. Sert à sélectionner le formulaire sur la page de l'évènement. Sois explicite ! (année, nom, mois si besoin, etc.)</p>
										</td>
									</tr>
									<tr>
										<td>
											<label for="seatt_limit">Limite de Participants</label>
										</th>
										<td>
											<input name="seatt_limit" type="text" id="seatt_limit" value="0">
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
								Quels jours de présence sont possibles ? (Vendredi, Samedi, Dimanche ou date)<br />
								<span><input type="text" id="presence[0]" size="20" name="presence[0]" value="" placeholder="Vendredi ou 01/01/2017" />
								<a href="#" id="addPresence">Ajouter un autre jour</a></span><br />
							</p>
						</div>
					</div>
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Restaurant</span></h2>
						<div class="inside">
							<p>
								Un restaurant est à prévoir ? 
								<select name="IsResto" id="IsResto">
									<option value="0">Non</option>
									<option value="1">Oui</option>
								</select>
							</p>
							<p id="Resto">
								Quel soir se fera le resto ? (Vendredi soir, Samedi soir, Dimanche soir ou date) *<br />
								<input type="text" id="resto" size="20" name="resto" value="" placeholder="Vendredi soir ou 01/01/2017 au soir" />
							</p>						
						</div>
					</div>
					<div class="postbox"> 
						<h2 class="hndle ui-sortable-handle"><span>Hôtel</span></h2>
						<div class="inside">
							<p>
								Un hôtel est à prévoir (évènement sur deux jours ou plus): 
								<select name="IsHotel" id="IsHotel">
									<option value="0">Non</option>
									<option value="1">Oui</option>
								</select>
								
							</p>
							<p id="Hotel">
								Quels soirs d'hôtels sont possibles ? (Vendredi soir, Samedi soir, Dimanche soir ou date) *<br />
								<span><input type="text" id="hotel[0]" size="20" name="hotel[0]" value="" placeholder="Vendredi soir ou 01/01/2017 au soir" />
								<a href="#" id="addHotel">Ajouter un autre soir</a><br /></span>
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
								<input name="seatt_start" type="text" id="seatt_start" value="<?php echo date("d-m-Y H:i", current_time('timestamp')); ?>" />
								<span class="howto">
									Exemple: la date du serveur est <br/><a onclick="document.getElementById('seatt_start').value='<?php echo date("d-m-Y H:i", current_time('timestamp')); ?>';"><?php echo date("d-m-Y H:i", current_time('timestamp')); ?></a> (jj-mm-aaaa hh:mm)
								</span>
							</p>
							<p>
								<label for="seatt_expire">Fermeture des inscriptions :</label>
								<br>
								<input name="seatt_expire" type="text" id="seatt_expire" value="<?php echo date("d-m-Y H:i", current_time('timestamp') + 604800); ?>" />
								<span class="howto">
									Exemple: dans une semaine la date sera <br/><a onclick="document.getElementById('seatt_expire').value='<?php echo date("d-m-Y H:i", current_time('timestamp') + 604800); ?>';"><?php echo date("d-m-Y H:i", current_time('timestamp') + 604800); ?></a> (jj-mm-aaaa hh:mm)
								</span>
							</p>
						
						</div>
					</div>
					
						<input id="publish" class="button button-primary button-large" type="submit" value="Ajouter le formulaire d'inscription" name="Submit">


				</div>
			</div>
		</form>
	</div>
</div>