<?php
// seatt_events_include.php
// Purpose: Carries functions for UI (in-page form and the event list)

// Function to cleanse numeric values, set to nothing if something other than an integer
function check_event_id($event_id) {
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
	// Return the event_id
	return $event_id;
}


// Function for the in-page form
function seatt_form($event_id, $public_comments = 0) {
	global $wpdb;
	global $current_user;
	//get_currentuserinfo(); deprecated since wp 4.5
	$current_user = wp_get_current_user();
	$seatt_error = "";

	// Clean down event ID by checking if numeric, then casting to integer
	$event_id = check_event_id($event_id);

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
		$seatt_event_state = $wpdb->get_var($wpdb->prepare("SELECT event_status FROM ".$wpdb->prefix."seatt_events WHERE id = %d AND event_expire >= %d", $event_id, current_time('timestamp')));

		// If submitted, remove registration
		if ((isset($_POST['seatt_unregister'])) && ($form_event_id == $event_id)) {

			// Check that the event isn't closed or expired, remove if not
			if ($seatt_event_state == 1) {
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."seatt_attendees WHERE user_id = %d AND event_id = %d", $current_user->ID,  $event_id));
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

                // Ensure if 0, user can signup
                if ($seatt_this_limit == 0) {
                    $seatt_this_limit = 100000;
                }

				// If space, add registration
				if ($seatt_this_limit > $seatt_this_registered) {

					// If not already registered
					if ($wpdb->get_var($wpdb->prepare("SELECT user_id FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d AND user_id = %d", $event_id, $current_user->ID)) == "") {
						$user_comment = stripslashes_deep(sanitize_text_field($_POST['seatt_comment']));
						$wpdb->insert($wpdb->prefix.'seatt_attendees', array( 'event_id' => $event_id, 'user_id' => $current_user->ID, 'user_comment' => $user_comment ), array('%d', '%d', '%s'));
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
		$event = $wpdb->get_row($wpdb->prepare("SELECT id, event_name, event_desc, event_limit, event_start, event_expire, event_status FROM ".$wpdb->prefix."seatt_events WHERE id = %d LIMIT 1", $event_id));

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

				$seatt_output = '<div style="background:#E8E8E8;padding:10px;margin-bottom:5px;"><h2 style="margin-top:10px;">' . esc_html($event->event_name) . '</h2>
				<p><strong>Description:</strong> ' . wpautop($event->event_desc) . '</p>
				<p>Registration opens at ' . date("d-m-Y H:i", $event->event_start) . '</p>
				<p>Registration closes at ' . date("d-m-Y H:i", $event->event_expire) . '</p>';

				if ($event->event_limit != 100000) {
					$seatt_output .= '<p>
					<strong>Max Participants:</strong> ' . intval($event->event_limit) . '</p>';
			}

			$seatt_output .= '<p><strong>Registered Users:</strong>
			<ol style="margin-left:1em;">';

			$users = $wpdb->get_results($wpdb->prepare("SELECT id, user_id, user_comment FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d ORDER BY id ASC", $event_id));

			foreach ($users as $user) {
				$user_info = get_userdata($user->user_id);
				$seatt_output .= '<li>' . esc_html($user_info->user_login);
				// If public comments setting is enabled, display comments in form
                if ($public_comments & esc_html($user->user_comment) != '') {
                    $seatt_output .= ' (' . esc_html($user->user_comment) . ')</li>';
                }
                $seatt_output .= '</li>';
			}

			if (count($users) == 0) {
				$seatt_output .= 'None found.';
			}

			$seatt_output .= '</ol></p>';

			// Check if user has already registered
			$attending = $wpdb->get_row($wpdb->prepare("SELECT user_id, user_comment FROM ".$wpdb->prefix."seatt_attendees WHERE event_id = %d AND user_id = %d", $event_id, $current_user->ID));

			$current_time = current_time('timestamp');
			if (($event->event_status != 1) OR ($current_time >= $event->event_expire) OR ($current_time < $event->event_start)) {
				$seatt_output .= '
				<p><strong>Registration is currently closed.</strong></p>';
			}
			// Check if user logged in
			elseif (!is_user_logged_in()) {
				$seatt_output .= '
			<p><strong>Please <a href="' . site_url('wp-login.php') . '">login</a> (or <a href="' . site_url('wp-login.php?action=register') . '">create an account</a>) to sign up for this event. </strong></p>';
				} elseif ($attending) {
					// if they have already signed up
					$seatt_output .= '
					<p><strong>You\'re currently registered with the following details:</strong></p>
			<form name="seatt_unregister" method="post" action="">
					<input name="seatt_event_id" type="hidden" id="seatt_event_id" value="' . $event_id . '" size="40">
					<p><strong>Username:</strong> ' . esc_html($current_user->user_login) . '
					  <br />
						<strong>Comment:</strong> ' . esc_html($attending->user_comment) . '
				  </p>
				  <p>
						<input type="submit" name="seatt_unregister" id="seatt_unregister" value="Unregister">
				  </p>
				</form>';

				} elseif ($attendees >= $event->event_limit) {
				$seatt_output .= '<p><strong>Unfortunately all places are already reserved. </strong></p>';
				} else {
				$seatt_output .= '
				<strong>Register for this event:</strong>
					</p>

				<form name="seatt_register" method="post" action="">
					<input name="seatt_event_id" type="hidden" id="seatt_event_id" value="' . $event_id . '" size="40">
				  <p>Username:
					<label for="seatt_username"></label>
					<input name="seatt_username" type="text" disabled id="seatt_username" value="' . esc_html($current_user->user_login) . '" size="40" readonly="readonly">
			<br />Comment:
					<input name="seatt_comment" type="text" id="seatt_comment" size="40" maxlength="40">
				  </p>
				  <p>
					<input type="submit" name="seatt_register" id="seatt_register" value="Register">
				  </p>
				</form>';

				}
			} //End if no results

			// Add on error message if needed
			if ($seatt_error != "") {
				$seatt_error = "<br /><p style=\"color:#ff0000\"><strong>Error:</strong> " . $seatt_error . "</p><br />";
				$seatt_output .= $seatt_error;
			}

			$seatt_output .= '</div>';

			return $seatt_output;
		}
	}
}


// Function for the in-page list
function seatt_list($category="",$public_comments = 0) {
	global $wpdb;

	// Get list of open events, which haven't expired
	$seatt_event_list = $wpdb->get_results($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."seatt_events WHERE event_status = %d AND event_expire >= %d ORDER BY event_expire ASC", 1, current_time('timestamp')));

	if (count($seatt_event_list) > 0) {
		$seatt_output = '<div style="background:#E8E8E8;padding:10px;margin-bottom:5px;"><h2 style="margin-top:10px;">Event listing</h2>';

		if (count($seatt_event_list) > 1) {
			$seatt_output .= '<p>There are ' . count($seatt_event_list) . ' open events.</p></div>';
		} else {
			$seatt_output .= '<p>There is ' . count($seatt_event_list) . ' open event.</p></div>';
		}

		foreach ($seatt_event_list as $seatt_event) {
			$seatt_output .= seatt_form($seatt_event->id,$public_comments);
		}

		return $seatt_output;

	} else {
		// If there are no records, return a message
		return '<div style="background:#E8E8E8;padding:10px;margin-bottom:5px;"><h2 style="margin-top:10px;">All open events</h2>
		<p>There are currently no open events.</p></div>';
	}

}

?>
