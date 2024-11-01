<?php
/*
	Author: WPG2
	Author URI: http://wpg2.galleryembedded.com/
	Updated: 25/03/2006

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/

if ( !current_user_can('edit_users') )
	die ( __('Cheatin&#8217; uh?', 'wpg2') );

// Initialize Gallery
	if (!defined('G2INIT')) {
		$ret = g2_init();
		if ($ret) {
			echo '<h2>' . __('Fatal G2 error:', 'wpg2') . '</h2>' . $ret;
			exit;
		}
	}

// Add the g2_user cap
	if($_GET['duser_id'] != "" && $_GET['dg2user_id'] == "") {
		$userdata = new WP_User($_GET['duser_id']);
		if ($userdata->has_cap('gallery2_user')) {
			$userdata->add_cap('gallery2_user', false);
			?><div id="message" class="updated fade"><p><strong><?php _e('Gallery2 capability revoked.', 'wpg2') ?></strong></p></div><?php
		}
	}

// remove the g2_user cap
	if($_GET['auser_id'] != "" && $_GET['ag2user_id'] == "" ) {
		$userdata = new WP_User($_GET['auser_id']);
		if (!$userdata->has_cap('gallery2_user')) {
				$userdata->add_cap('gallery2_user', true);
				?><div id="message" class="updated fade"><p><strong><?php _e('Gallery2 capability granted.', 'wpg2') ?></strong></p></div><?php
		}
	}

// Add the g2_user cap
	if($_GET['duser_id'] != "" && $_GET['dg2user_id'] != "" ) {
		$userdata = new WP_User($_GET['duser_id']);
		if ($userdata->has_cap('gallery2_admin')) {
			$userdata->add_cap('gallery2_admin', false);
			GalleryCoreApi::removeUserFromGroup($_GET['dg2user_id'], 3);
			if (!$ret) {
				?><div id="message" class="updated fade"><p><strong><?php _e('Gallery2 admin capability revoked.', 'wpg2') ?></strong></p></div><?php
			} else {
				?><div id="message" class="error"><p><strong><?php _e('Gallery2 admin capability revoked Failed.', 'wpg2') ?></strong></p></div><?php
				}
		}
	}

// remove the g2_user cap
	if($_GET['auser_id'] != "" && $_GET['ag2user_id'] != "" ) {
		$userdata = new WP_User($_GET['auser_id']);
		if (!$userdata->has_cap('gallery2_admin')) {
			$userdata->add_cap('gallery2_admin', true);
			GalleryCoreApi::addUserToGroup($_GET['ag2user_id'], 3);
			if (!$ret) {
				?><div id="message" class="updated fade"><p><strong><?php _e('Gallery2 admin capability granted.', 'wpg2') ?></strong></p></div><?php
			} else {
				?><div id="message" class="error"><p><strong><?php _e('Gallery2 admin capability grant Failed.', 'wpg2') ?></strong></p></div><?php
				}
		}
	}


// Call WP<->G2 User Syncing
	g2_sync_userbase();

	// Get a List of all current WP Users
		$wpusers = $wpdb->get_results("SELECT ID FROM $wpdb->users ORDER BY ID");
		$cnt = 0;
		foreach ($wpusers as $wpuser) {
			$wparray[$cnt] = $wpuser->ID;
			$cnt++;
		}

// Get G2 Mapping
	list ($ret, $g2users) = GalleryEmbed::getExternalIdMap('entityId');
	if ($ret) {
		echo __('Fatal G2 error:', 'wpg2') . $ret->getAsHtml();
		exit;
	}

	foreach ($g2users as $g2user) {
		if ( $g2user['entityType'] == "GalleryUser" ) {
			$g2entityarray[$g2user['externalId']] = $g2user['entityId'];
		}
	}

	echo '<div class="wrap" id="main_page"><h2>' . __('Manage Mapped Gallery2 <> Wordpress Users', 'wpg2') . '</h2>';
	echo '<p>' . __('This page is for controlling the Gallery2 capabilities that are associated with each user.', 'wpg2') . '</p>';

// Wordpress Accounts Mapped to G2

	$output = '<fieldset class="options">';
	$output.= '<legend>' . __('Wordpress Users with Gallery2 admin accounts', 'wpg2') . '</legend>';
	$output.= '<table cellpadding="3" cellspacing="3" width="100%">';
	$output.= '<tr><th>' . __('WP ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('G2 ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('User Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Nickname', 'wpg2') . '</th>';
	$output.= '<th>' . __('Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Email', 'wpg2') . '</th>';
	$output.= '<th>' . __('Website', 'wpg2') . '</th>';
	$output.= '<th>' . __('Action', 'wpg2') . '</th>';
	$output.= '<th>&nbsp;</th>';
	$output.= '</tr>';

	foreach ($wpusers as $wpuser) {
		//	Get WP & G2 Member Information
		$userdata = new WP_User($wpuser->ID);
		$wpuserid = $wpuser->ID;
		if ($userdata->has_cap('gallery2_user') && $userdata->has_cap('gallery2_admin') ) {
			if ($output) {
				echo $output;
				$style = '';
				$output = "";
			}
			// Output WP Infomation
			$url = $userdata->user_url;
			$email = $userdata->user_email;
			$short_url = str_replace('http://', '', $url);
			$short_url = str_replace('www.', '', $short_url);
			if ('/' == substr($short_url, -1))
				$short_url = substr($short_url, 0, -1);
			if (strlen($short_url) > 35)
				$short_url =  substr($short_url, 0, 32).'...';
			$style = ('class="alternate"' == $style) ? '' : 'class="alternate"';
			echo "";
			echo "<tr $style>";
			echo '<td align="center">' . $userdata->ID . '</td>';
			echo '<td align="center">' . $g2entityarray[$wpuserid] . '</td>';
			echo '<td align="center">' . $userdata->user_login . '</td>';
			echo '<td align="center">' . $userdata->nickname . '</td>';
			echo '<td align="center">' . $userdata->user_firstname .' ' . $userdata->user_lastname . '</td>';
			echo '<td align="center"><a href="mailto:' . $email . '" title="' . __('e-mail: ', 'wpg2') . $email . '">' . $email . '</a></td>';
			echo '<td align="center"><a href="' . $url . '" title="' . __('website: ', 'wpg2') . $url . '">' . $short_url . '</a></td>';
			if ( current_user_can('gallery2_admin') )
				echo '<td align="center"><a href="profile.php?page=wpg2/g2users.php&duser_id=' . $userdata->ID . '&dg2user_id=' . $g2entityarray[$wpuserid] . '">' . __('Revoke G2 Admin', 'wpg2') . '</a></td>';
			echo '</tr>';
		}
	}
	echo '</table></fieldset>';

	$output = '<fieldset class="options">';
	$output.= '<legend>' . __('Wordpress Users without Gallery2 admin accounts', 'wpg2') . '</legend>';
	$output.= '<table cellpadding="3" cellspacing="3" width="100%">';
	$output.= '<tr><th>' . __('WP ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('G2 ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('User Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Nickname', 'wpg2') . '</th>';
	$output.= '<th>' . __('Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Email', 'wpg2') . '</th>';
	$output.= '<th>' . __('Website', 'wpg2') . '</th>';
	$output.= '<th>' . __('Action', 'wpg2') . '</th>';
	$output.= '<th>&nbsp;</th>';
	$output.= '</tr>';

	foreach ($wpusers as $wpuser) {
		//	Get WP & G2 Member Information
		$userdata = new WP_User($wpuser->ID);
		$wpuserid = $wpuser->ID;
		if ($userdata->has_cap('gallery2_user') && !$userdata->has_cap('gallery2_admin') ) {
			if ($output) {
				echo $output;
				$style = '';
				$output = "";
			}
			// Output WP Infomation
			$url = $userdata->user_url;
			$email = $userdata->user_email;
			$short_url = str_replace('http://', '', $url);
			$short_url = str_replace('www.', '', $short_url);
			if ('/' == substr($short_url, -1))
				$short_url = substr($short_url, 0, -1);
			if (strlen($short_url) > 35)
				$short_url =  substr($short_url, 0, 32).'...';
			$style = ('class="alternate"' == $style) ? '' : 'class="alternate"';
			echo "";
			echo "<tr $style>";
			echo '<td align="center">' . $userdata->ID . '</td>';
			echo '<td align="center">' . $g2entityarray[$wpuserid] . '</td>';
			echo '<td align="center">' . $userdata->user_login . '</td>';
			echo '<td align="center">' . $userdata->nickname . '</td>';
			echo '<td align="center">' . $userdata->user_firstname .' ' . $userdata->user_lastname . '</td>';
			echo '<td align="center"><a href="mailto:' . $email . '" title="' . __('e-mail: ', 'wpg2') . $email . '">' . $email . '</a></td>';
			echo '<td align="center"><a href="' . $url . '" title="' . __('website: ', 'wpg2') . $url . '">' . $short_url . '</a></td>';
			if ( current_user_can('gallery2_admin') ) {
				echo '<td align="center"><a href="profile.php?page=wpg2/g2users.php&auser_id=' . $userdata->ID . '&ag2user_id=' . $g2entityarray[$wpuserid] . '">' . __('Grant G2 Admin', 'wpg2') . '</a>';
				echo ' / <a href="profile.php?page=wpg2/g2users.php&duser_id=' . $userdata->ID . '">' . __('Revoke G2 User', 'wpg2') . '</a></td>';
			} else {
				echo '<td align="center"><a href="profile.php?page=wpg2/g2users.php&duser_id=' . $userdata->ID . '">' . __('Revoke G2 User', 'wpg2') . '</a></td>';
			}
			echo '</tr>';
		}
	}

	echo '</table></fieldset>';

// Wordpress Accounts Not mapped to G2

	$output = '<fieldset class="options">';
	$output.= '<legend>' . __('Wordpress Users without Gallery2 accounts', 'wpg2') . '</legend>';
	$output.= '<table cellpadding="3" cellspacing="3" width="100%">';
	$output.= '<tr><th>' . __('WP ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('G2 ID', 'wpg2') . '</th>';
	$output.= '<th>' . __('User Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Nickname', 'wpg2') . '</th>';
	$output.= '<th>' . __('Name', 'wpg2') . '</th>';
	$output.= '<th>' . __('Email', 'wpg2') . '</th>';
	$output.= '<th>' . __('Website', 'wpg2') . '</th>';
	$output.= '<th>' . __('Action', 'wpg2') . '</th>';
	$output.= '<th>&nbsp;</th>';
	$output.= '</tr>';

	foreach ($wpusers as $wpuser) {
		//	Get WP & G2 Member Information
		$userdata = new WP_User($wpuser->ID);
		if (!$userdata->has_cap('gallery2_user')) {
			if ($output) {
				echo $output;
				$style = '';
				$output = "";
			}
			// Output WP Infomation
			$url = $userdata->user_url;
			$email = $userdata->user_email;
			$short_url = str_replace('http://', '', $url);
			$short_url = str_replace('www.', '', $short_url);
			if ('/' == substr($short_url, -1))
				$short_url = substr($short_url, 0, -1);
			if (strlen($short_url) > 35)
				$short_url =  substr($short_url, 0, 32).'...';
			$style = ('class="alternate"' == $style) ? '' : 'class="alternate"';
			echo "";
			echo "<tr $style>";
			echo '<td align="center">' . $userdata->ID . '</td>';
			echo '<td align="center">' . __('NA', 'wpg2') . '</td>';
			echo '<td align="center">' . $userdata->user_login . '</td>';
			echo '<td align="center">' . $userdata->nickname . '</td>';
			echo '<td align="center">' . $userdata->user_firstname  . ' ' . $userdata->user_lastname . '</td>';
			echo '<td align="center"><a href="mailto:' . $email . '" title="' . __('e-mail: ', 'wpg2') . $email . '">' . $email . '</a></td>';
			echo '<td align="center"><a href="' . $url . '" title="' . __('website: ', 'wpg2') . $url . '">' . $short_url . '</a></td>';
			echo '<td align="center"><a href="profile.php?page=wpg2/g2users.php&auser_id=' . $userdata->ID . '">' . __('Grant G2 User', 'wpg2') . '</a></td>';
			echo '</tr>';
		}
	}

	echo '</table></fieldset>';



	echo '</div>';

?>