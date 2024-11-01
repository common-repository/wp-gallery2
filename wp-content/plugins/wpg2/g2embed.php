<?php
/*
	Plugin Name: WPG2
	Plugin URI: http://wpg2.galleryembedded.com/
	Description: Embeds Gallery2 within Wordpress to share photos, videos and any other Gallery2 content seamlessly into your Blog & Sidebar Content.<br/> <a href="http://wpg2.galleryembedded.com/">Documentation</a>, <a href="http://wpg2.galleryembedded.com/forums/">Support Forums</a>.
	Author: <a href="http://www.ozgreg.com/">Ozgreg</a> help and invaluable support by <a href="http://wpg2.galleryembedded.com/">WPG2 Team</a>
	Version: 2.0
	Author URI: http://www.ozgreg.com/
	Updated: 25/03/2006

	Past code, invaluable design & help, thanks to <a href="http://holosite.com/">John Arnold</a>

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/

// Load the WPG2 translation files
	load_plugin_textdomain('wpg2', 'wp-content/plugins/wpg2/locale');

// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

if($g2_option['g2_wpg2versionnumber'] < 2.0){
		// Reset g2_options if WPG2 version number is lower than current version
		g2_pluginactivate();
		// Get Gallery2 Option Settings
		$g2_option = get_settings('g2_options');
}


/**
* g2_init
* Initialize Gallery; must be called before most GalleryEmbed methods can be used.
* This method should only be called once during the lifetime of the request.
*
* @return string GalleryStatus
*/

function g2_init() {

	// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

	// Test if Plugin has been validated.

		if (!defined('G2_EMBED') && $g2_option['g2_validated'] == "Yes" )
				define('G2_EMBED', 'True');

		if (!defined ('G2_EMBED') ) {
				echo '<h2>' . __('Fatal Gallery Plug-in error', 'wpg2') . '</h2> ' . __('Plug-in options not validated', 'wpg2');
				exit;
		}

		require_once($g2_option['g2_filepath'].'embed.php');

	// Initialise GalleryAPI
		$ret = GalleryEmbed::init( array(
					'embedUri' => $g2_option['g2_embeduri'],
					'g2Uri' => $g2_option['g2_url'],
					'loginRedirect' => $g2_option['g2_errorredirect'],
					'fullInit' => true )
				);

		if ($ret) {
			$ret->getAsHtml();
			return $ret;
		}

		// Declate G2 Init so we do not do it again..
		define("G2INIT", "True");

		if ($ret) {
			$ret->getAsHtml();
		}

		return $ret;
}

/**
* g2_configurerewrites
* Detects if rewrite module is active in Gallery2 and configured. Then
* it pushes the current values of Wordpress file and site locations over to
* Gallery2 rewrite module via the new class calls
*
* @return string GalleryStatus
*/

function g2_configurerewrites() {

	// Get Gallery2 Option Settings
	global $g2_option;

	// Initialize Gallery
	if (!defined('G2INIT')) {
		$ret = g2_login();
		if ($ret) {
			echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
			exit;
		}
	}

	// Get the Gallery2 Rewrite Configuration
	list ($ret, $rewriteApi) = GalleryCoreApi::newFactoryInstance('RewriteApi');
		if ($ret) {
			echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
			exit;
		}
	list ($ret, $params) = $rewriteApi->fetchEmbedConfig();

	// Find the File Path of WP

	$site_url = trailingslashit(get_settings('siteurl'));
	$site_path = str_replace(('https://' . $_SERVER['HTTP_HOST']),'', $site_url);
	$site_path = str_replace(('http://' . $_SERVER['HTTP_HOST']),'', $site_url);
	$file_path = ABSPATH;

	// Check if .htaccess exists.  If not and path is writable, create it.
	if (!file_exists($file_path . '.htaccess')) {
		if(is_writable($file_path)) {
			$f = fopen($file_path . '.htaccess', 'w');
			fclose($f);
		}
		// If path is not writable, generate "WordPress Path Not Writable Error"
		else
			return (__('There is no .htaccess file in your WordPress root directory (where wp-config.php is located) and that directory is not writable.  Please create a writeable .htaccess in that directory.', 'wpg2'));
	}

	if (file_exists($file_path . '.htaccess'))   {
		if (is_writable($file_path . '.htaccess')) {

			// Set the G2 rewrite Values
			$params['embeddedLocation'] = $site_path;
			$params['embeddedHtaccess'] = $file_path;

			// Save the G2 rewrite Values
			list ($ret, $code, $err) = $rewriteApi->saveEmbedConfig($params);

			if ( $code > 0 ) {
				list ($ret, $errstr) = $err;
				$errstr =  $code." - ".$errstr;
				return ($errstr);
			}
			else return (NULL);
		}

		// Else return ".htaccess Not Writable Error"
		else
			return (__('The .htaccess file in your WordPress root directory (where wp-config.php is located) is not writable.  Please CHMOD it to 644 (or 666 if 644 does not work).', 'wpg2'));
	}

}

/**
* g2_login
* Logs the current WP user into Gallery2
* Exits on Fatal
*
* @return string GalleryStatus
*/

function g2_login() {

	// Get Gallery2 Option Settings
	global $g2_option;

	// Get WordPress's CurrentUser

	if (isset($_COOKIE['wordpressuser_' . COOKIEHASH]))
		$user_login = $_COOKIE['wordpressuser_' . COOKIEHASH];
	$userdata = get_userdatabylogin($user_login);
	$user_ID = $userdata->ID;

	if (!defined('G2INIT')) {
		$ret = g2_init();
		if (!$ret)
			if ($user_ID)
				g2_manage_wpg2user($user_ID);
			else
				GalleryEmbed::checkActiveUser('');

		if ($ret) {
				$ret->getAsHtml();
		}
		return $ret;
	}

}

/**
* g2_updateuser
* Checks for WP user existence in G2.  If G2 mapping exists, updates user's info.
*
* @param integer $new_user_id WordPress user ID
* @return string GalleryStatus
*/

function g2_updateuser($new_user_id) {
	// Initialize Gallery

	// Get Gallery2 Option Settings
	global $g2_option;

		if (!defined('G2INIT')) {
			$ret = g2_login();
			if ($ret) {
				echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
				exit;
			}
		}

	//    Check G2 User
		$ret = GalleryEmbed::isExternalIdMapped($new_user_id, 'GalleryUser');

	//    if error then do not update as G2 User Does not Exist Anyway otherwise update Gallery2 User.
		if (!$ret) {
			//Get Current Timestamp
			$new_nicename    = sanitize_title($_POST['nickname']);
			$new_email       = wp_specialchars($_POST['email']);
			$new_userpass    = MD5($_POST["pass1"]);
			$ret = GalleryEmbed::updateUser( $new_user_id, array ( 'email' => $new_email, 'fullname' => $new_nicename,
								'hashedpassword' => $new_userpass, 'hashmethod' => 'md5'));
			GalleryEmbed::done();
	}

	return $ret;

}

/**
* g2_logout
* Logs out the current user.
*/

function g2_logout() {

	// Get Gallery2 Option Settings
	global $g2_option;

	// Test if Plugin has been validated.
	if ($g2_option['g2_validated'] == "Yes" ) {
		require_once($g2_option['g2_filepath'].'embed.php');

		$ret = GalleryEmbed::logout( array(
				'embedUri' => $g2_option['g2_embed'])
			);

	}

}

/**
* g2_manage_wpg2user
* Manage User Sync between Wordpress and Gallery2
*
* @param integer $user_ID WordPress User ID
* @return string GalleryStatus
*/

function g2_manage_wpg2user($user_ID) {

	// Get Gallery2 Option Settings
	global $g2_option;

	// Call WP<->G2 User Syncing
		if ($g2_option['g2_usersynched'] != "Yes")
			g2_sync_userbase();

	// If G2 User not active then link or create.
		$ret = GalleryEmbed::checkActiveUser($user_ID);

		if ($ret) {
	// Always find the G2 User if it has been manually created
			$userdata = get_userdata($user_ID);
			list ($ret, $g2finduser ) = GalleryCoreApi::fetchUserByUsername ($userdata->user_login);
			if (!$ret) {
				GalleryEmbed::addExternalIdMapEntry($user_ID, $g2finduser->_id, 'GalleryUser');
	// Set WP/G2 User Active
				$ret = GalleryEmbed::checkActiveUser($user_ID);
			} else {
	// Guest
				GalleryEmbed::checkActiveUser('');
			}
		}

		if ($ret) {
				$ret->getAsHtml();
		}

		return $ret;
}

/**
* g2_sync_userbase
* Synchronize the WordPress and Gallery2 User Databases
*/

function g2_sync_userbase() {

	// Get Gallery2 Option Settings
	global $g2_option, $wpdb;

	// Get a List of all externally Mapped Users in Gallery2 & Validate with WP
	list ($ret, $g2users) = GalleryEmbed::getExternalIdMap('externalId');
	if (!$ret) {
	$cnt = 0;
		foreach ($g2users as $g2user) {
			if ( $g2user['entityType'] == "GalleryUser" ) {
				$userdata = new WP_User($g2user['externalId']);
				if (!$userdata->has_cap('gallery2_user'))
					$ret = GalleryEmbed::deleteUser($g2user['externalId']);
					if ($ret)
						$ret = GalleryCoreApi::removeMapEntry('ExternalIdMap', array('externalId' => $g2user['externalId'], 'entityType' => 'GalleryUser'));
			}
		}
	}


	// Renew a List of all externally Mapped Users in Gallery2
	list ($ret, $g2users) = GalleryEmbed::getExternalIdMap('externalId');

	if (!$ret) {
	$cnt = 0;
		foreach ($g2users as $g2user) {
			if ( $g2user['entityType'] == "GalleryUser" ) {
				$g2entityarray[$cnt] = $g2user['entityId'];
				$g2array[$cnt] = $g2user['externalId'];
				$cnt++;
			}
		}

	// Get a List of all current WP Users
		$wpusers = $wpdb->get_results("SELECT ID FROM $wpdb->users ORDER BY ID");
		$cnt = 0;
		foreach ($wpusers as $wpuser) {
			$wparray[$cnt] = $wpuser->ID;
			$cnt++;
		}

	// Find any unmapped WP Users

		if ( count ($g2array) > 0 )
			$g2create = array_diff($wparray, $g2array);
		else
			$g2create = $wparray;

	// Link or Create the Missing WP->G2 Users
		foreach ($g2create as $g2user) {
			$userdata = new WP_User($g2user);
			if ($userdata->has_cap('gallery2_user')) {
				list ($ret, $g2finduser ) = GalleryCoreApi::fetchUserByUsername ($userdata->user_login);
				if (!$ret) {
					$entity = "";
					if ( count ($g2entityarray) > 0 )
						$entity = array_search($g2finduser->id, $g2entityarray);
						// Remove Duplicated External Mapping (If Found), Remap User or Create User
					if ($entity) {
						$ret = GalleryCoreApi::removeMapEntry( array('externalId' => $g2array[$entity], 'entityType' => 'GalleryUser'));
						unset ($g2array[$entity]);
					}
					GalleryEmbed::addExternalIdMapEntry($g2user, $g2finduser->id, 'GalleryUser');
				} else {
					$ret = GalleryEmbed::createUser( $userdata->ID, array ( 'username' => $userdata->user_login, 'email' => $userdata->user_email, 'fullname' => $userdata->user_nicename,
								'hashedpassword' => $userdata->user_pass, 'hashmethod' => 'md5'));
				}
			}
		}

	// Find any mapped G2 External Users without WP Users

		if ( count ($g2array) > 0 ) {
			$g2delete = array_diff($g2array, $wparray);

	// Delete G2 Externally Mapped User without WP Users.
			foreach ($g2delete as $g2user) {
						$ret = GalleryEmbed::deleteUser($g2user);
						if ($ret)
							$ret = GalleryCoreApi::removeMapEntry('ExternalIdMap', array('externalId' => $g2user, 'entityType' => 'GalleryUser'));
			}
		}


	// Set User Synched = Yes
		$g2_option['g2_usersynched'] = "Yes";
		update_option('g2_options', $g2_option);

	}
}

/**
* g2_imagebypathblock
* Include image from gallery based on path
*
* @param string $g2inputpath Gallery2 Item path relative to root Gallery2 Data directory
* @return string HTML for img tag
*/

function g2_imagebypathblock( $g2inputpath ) {
	// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {

		// Initialize Gallery
		if (!defined('G2INIT')) {
			$ret = g2_login();
			if ($ret) {
				echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
				exit;
			}
		}

		// Check for the Item Size | parameter & Clean up Strings..
		$g2itempos = strpos ($g2inputpath, '|');
		if ($g2itempos) {
			$g2itempath = substr ($g2inputpath, 0, $g2itempos);
			$g2itemsize = substr ($g2inputpath, $g2itempos+1);
		} else {
			$g2itempath = $g2inputpath;
		}

		// Make Sure Item Path does not contain a + as it should instead be a space
		$g2itempath = str_replace ("+", " ", $g2itempath);

		// Get the Image
		list ($ret, $g2itemid) = GalleryCoreAPI::fetchItemIdByPath($g2itempath);
		if (!$ret) {
			$img = g2_imageblock($g2itemid, $g2itemsize);
			$img = str_replace("\n", "", $img); // strip out CRs 
		} else $img = '* ' . $g2inputpath . ' ' . __('NOT FOUND', 'wpg2') . ' *';

	} else
			$img = '* ' . __('WPG2 Plugin Not Validated', 'wpg2') . ' *';

	return $img;
}

/**
* g2_imageblock
* Include image from Gallery2 based on Gallery2 Item ID
*
* @param string $g2inputid Gallery2 Item path relative to root Gallery2 Data directory
* @param integer $g2itemsize Item Size in pixels.  Defaults to null if not included in GET parameters.
* @return string HTML for img tag
*/

function g2_imageblock( $g2inputid, $g2itemsize=null ) {
	// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {

		// Initialize Gallery
		if (!defined('G2INIT')) {
			$ret = g2_login();
			if ($ret) {
				echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
				exit;
			}
		}

		// Check for the Item Size | parameter & Clean up Strings..
		$g2itempos = strpos ($g2inputid, '|');
		if ($g2itempos) {
			$g2itemid = substr ($g2inputid, 0, $g2itempos);
			$g2itemsize = substr ($g2inputid, $g2itempos+1);
		} else {
			$g2itemid = $g2inputid;
		}

		// Build the Image Block
		$blockoptions['blocks'] = 'specificItem';
		$blockoptions['show'] = 'none';
		$blockoptions['itemId'] = $g2itemid;

		// Assign Show Details
		if ( $g2_option['g2_postblockshow'] ) {
			if ( count($g2_option['g2_postblockshow']) > 1 )
				$blockoptions['show'] = implode ( $g2_option['g2_postblockshow'], '|' );
			else
				$blockoptions['show'] = $g2_option['g2_postblockshow'][0];
		} else
				$blockoptions['show'] = 'none';

		// Assign maxSize

		if ($g2itemsize)
			$blockoptions['maxSize'] = $g2itemsize;
		else
			if ( $g2_option['g2_postimgsize'] )
				$blockoptions['maxSize'] = $g2_option['g2_postimgsize'];

		// Assign Item Frame Style
		if ($g2_option['g2_postimageFrame'])
			$blockoptions['itemFrame']  = $g2_option['g2_postimageFrame'];

		// Assign Album Frame Style
		if ($g2_option['g2_postalbumFrame'])
			$blockoptions['albumFrame']  = $g2_option['g2_postalbumFrame'];

		list ($ret, $itemimg, $headimg) = GalleryEmbed::getImageBlock($blockoptions);
		if ($ret)
			$img = '* ' . __('NOT FOUND', 'wpg2') . ' *';
		else {
			$img = $itemimg;

		// Compact the output
				$img = preg_replace("/(\s+)?(\<.+\>)(\s+)?/", "$2", $img);
				$img = str_replace("\n", "", $img); // strip out CRs 

			GalleryEmbed::done();
		}

	 } else
		$img = '* ' . __('WPG2 Plugin Not Validated', 'wpg2') . ' *';


	return $img;
}

/**
* function name
* Imageblock Function for Side blocks individual items in accordance with the Blog Image Options tab in WPG2
*
* @param string $g2itemid Gallery2 item ID of the image
* @return string HTML for coming back from school.  Check-in first.  Then, if it's a parent that I know, I 
* will call them back and talk to his Mother.
*/

function g2_sidebarimageblock( $g2itemid="" ) {

	// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {

	// Do we have configuration?
		if ( count ($g2_option['g2_sidebarblock']) > 1 || $g2itemid != ""  ) {

			// Assign itemID
				if ( $g2itemid ) {
					$blockoptions['itemId'] = $g2itemid;
					$blockoptions['show'] = "randomImage";
				}

			// Assign blocks
				if ( $block ) {
					$blockoptions['blocks'] = $block;
				} else {
					if ( count($g2_option['g2_sidebarblock']) > 1 )
						$blockoptions['blocks'] = implode ( $g2_option['g2_sidebarblock'], '|' );
					else if ( $g2_option['g2_sidebarblock'][0] )
						$blockoptions['blocks'] = $g2_option['g2_sidebarblock'][0];
					else if ( $itemID )
						$blockoptions['blocks'] = 'specificItem';
				}

			// Assign Show Details

				if (!$blockoptions['show']) {
					if ( $g2_option['g2_sidebarblockshow'] ) {
						if ( count($g2_option['g2_sidebarblockshow']) > 1 )
							$blockoptions['show'] = implode ( $g2_option['g2_sidebarblockshow'], '|' );
						else
							$blockoptions['show'] = $g2_option['g2_sidebarblockshow'][0];
					} else
							$blockoptions['show'] = 'none';
				}

			// Assign maxSize
				if ( $g2_option['g2_sidebarblockimgsize'] )
					$blockoptions['maxSize'] = $g2_option['g2_sidebarblockimgsize'];

			// Assign Item Frame Style
				if ($g2_option['g2_sidebarblockimageFrame'])
					$blockoptions['itemFrame']  = $g2_option['g2_sidebarblockimageFrame'];

			// Assign Album Frame Style
				if ($g2_option['g2_sidebarblockalbumFrame'])
					$blockoptions['albumFrame']  = $g2_option['g2_sidebarblockalbumFrame'];

			// Initialize Gallery
				if (!defined('G2INIT')) {
					$ret = g2_login();
					if ($ret) {
						echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
						exit;
					}
				}

			// Get Image Block
					list ($ret, $img, $headimg ) = GalleryEmbed::getImageBlock($blockoptions);
					$img = str_replace("\n", "", $img); // strip out CRs 

					if ($ret)
						$img = "Error:".print_r($blockoptions);

		 } else {
				$img = '* ' . __('Sidebar Not Configured', 'wpg2') . ' *';
		 }

	 } else {
			$img = '* ' . __('WPG2 Plugin Not Validated', 'wpg2') . ' *';
	 }

	 print_r($img);
}

/**
* g2_sidebargridblock
* Imageblock function for ouputting more than one image in a sidebar gridblock
*
* @param string $g2blocktype determines type of gridblock.  Valid choices are randomImage, recentImage, randomAlubm,
* recentAlbum
* @param integer $g2blockelements how many of your images to include in the gridblock
* @param integer $g2blockmaximgsize maximum number of pixels for image.  Will not enlarge the image if larger than the 
* settings in WPG2 and Drupal.
* @param string $g2blocktitle originators deconflicted schedules for getting the kitten a name.  
* @return string HTML for the sidebar gridblock
*/

function g2_sidebargridblock( $g2blocktype="", $g2blockelements="", $g2blockmaximgsize="", $g2blocktitle="" ) {
	// Get Gallery2 Option Settings
	global $g2_option;

	// Set error Title
	$title = '<h2>' . __('Sidebar Block', 'wpg2') . '</h2>';

	if ($g2_option['g2_validated'] == "Yes") {

		// Do we have configuration?
		if ( $g2_option['g2_sidebarblockstype'] || $g2blocktype !=""  ) {
			// if no option was passed, set to setting in options
			if ( $g2blocktype == "" ) {
				 $g2blocktype = $g2_option['g2_sidebarblockstype'];
			}

			switch ($g2blocktype) {
				case "randomImage":
					$title = '<h2>' . __('Random Image', 'wpg2') . '</h2>';
					break;
				case "recentImage":
					$title = '<h2>' . __('Recent Image', 'wpg2') . '</h2>';
					break;
				case "randomAlbum":
					$title = '<h2>' . __('Random Album', 'wpg2') . '</h2>';
					break;
				case "recentAlbum":
					$title = '<h2>' . __('Recent Album', 'wpg2') . '</h2>';
					break;
				default:
					$title = __('Error', 'wpg2');
					break;
			}

			// if no option was passed, set to setting in options
			if ( $g2blockelements == "" ) {
				$g2blockelements = $g2_option['g2_sidebarblockstodisplay'];
			}

			$g2_blockgrid = $g2blocktype;

			// create the block grid thing
			for ($loop = 2; $loop <= $g2blockelements; $loop++ ) {
				// this way, we don't need to stip a final "|" off
				$g2_blockgrid.="|".$g2blocktype;
			}

			//Has the title been built, if not then this block is not supported
			if ( $title != 'Error' ) {
				// If title was passed in parameters, display it
				if ( $g2blocktitle ) {
					$title = '<h2>' . $g2blocktitle . '</h2>';
				}
				// Else use the UI settings to determine whether to display title
				else if (!in_array('heading',$g2_option['g2_sidebarblockinfo'])) {
					$title = "";
				}

				$blockoptions['blocks'] = $g2_blockgrid;
				$blockoptions['show'] = "none";
				// Override the image Size?
				if ($g2blockmaximgsize) {
					$blockoptions['maxSize'] = $g2blockmaximgsize;
				} else {
					$blockoptions['maxSize'] = $g2_option[g2_sidebarblocksimgsize];
				}
				$blockoptions['itemFrame']  = $g2_option[g2_sidebarblocksimageFrame];
				$blockoptions['albumFrame']  = $g2_option[g2_sidebarblocksimageFrame];

				// Initialize Gallery
				if (!defined('G2INIT')) {
					$ret = g2_login();
					if ($ret) {
					echo '<h2>' . __('Fatal G2 error', 'wpg2') . '</h2> ' . __("Here's the error from G2: ", 'wpg2') . $ret->getAsHtml();
					exit;
					}
				}

				//Get Image Block
				list ($ret, $img, $headimg ) = GalleryEmbed::getImageBlock($blockoptions);
				if ($ret)
					$img = __('Error: ', 'wpg2').print_r($blockoptions);

			}  else {
				$img = '* ' . __("Unsupported Block Type", 'wpg2') . ' *';
			}

		} else {
			$img = '* ' . __("Block Not Configured", 'wpg2') . ' *';
		}

	} else {
		$img = '* ' . __('WPG2 Plugin Not Validated', 'wpg2') . ' *';
	}

	$output = $title.$img;
	return $output;

}

/**
* g2_imagebypathinpost
* Parse Plugin quicktag for image path
*
* @param string $text The text to be parsed
* @param binary $case_sensitive TRUE/FALSE variable to determine whether to consider case in the preg_replace
* function.  If not present, defaults to FALSE
* @return string the Gallery2 ID of the item
*/

function g2_imagebypathinpost($text, $case_sensitive=false) {
	global $g2_option;

	$preg_flags = ($case_sensitive) ? 'e' : 'ei';
	$output = preg_replace("'<WPG2>(.*?)</WPG2>'$preg_flags", "g2_imagebypathblock('\\1')", $text);

	return $output;
}

/**
* function name
* Parse Plugin quicktag for itemID
*
* @param string $text The text to be parsed
* @param binary $case_sensitive TRUE/FALSE variable to determine whether to consider case in the preg_replace
* function.  If not present, defaults to FALSE
* @return string the Gallery2 ID of the item
*/

function g2_imagebyidinpost($text, $case_sensitive=false) {
	global $g2_option;

	$preg_flags = ($case_sensitive) ? 'e' : 'ei';
	$output = preg_replace("'<WPG2ID>(.*?)</WPG2ID>'$preg_flags", "g2_imageblock('\\1')", $text);

	return $output;
}

/**
* g2_imageframes
* WP action to add the Gallery2 image frame style sheets to the WP header
*/

function g2_imageframes() {
	// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {
		$framenames = array('none');
		$frameoptions = array('g2_sidebarblocksimageFrame','g2_sidebarblockimageFrame','g2_sidebarblockalbumFrame','g2_postalbumFrame','g2_postimageFrame');
		foreach($frameoptions as $frameoption) {
			$framename = $g2_option[$frameoption];
			if($framename && !in_array($framename, $framenames)) {
				$framenames[] = $framename;
				echo '<link rel="stylesheet" href="'.$g2_option['g2_url'].'main.php?g2_controller=imageblock.ExternalCSS&amp;g2_frames='.$framename."\"/>\n";
			}
		}
	}
}

/**
* g2_addwpmenus
* WP action to add the WPG2 admin menus and submenus
*/

function g2_addwpmenus() {
	// Get Gallery2 Option Settings
	global $g2_option;

	// Admin Menu Functions
	if ( current_user_can('manage_options') ) {
		add_menu_page(__('WPG2 Options', 'wpg2'), __('WPG2', 'wpg2'), 'manage_options', 'wpg2/g2admin.php' );
		add_submenu_page('wpg2/g2admin.php', '', __('Embedded Page Options', 'wpg2'), 'manage_options', 'wpg2/g2adminpage.php' );
		add_submenu_page('wpg2/g2admin.php', '', __('G2Image Options', 'wpg2'), 'manage_options', 'wpg2/g2adming2image.php' );
		add_submenu_page('wpg2/g2admin.php', '', __('Blog Images Options', 'wpg2'), 'manage_options', 'wpg2/g2adminblogimage.php' );
		add_submenu_page('wpg2/g2admin.php', '', __('Sidebar Options', 'wpg2'), 'manage_options', 'wpg2/g2adminsidebar.php' );
		add_submenu_page('wpg2/g2admin.php', '', __('Sidebar Block Options', 'wpg2'), 'manage_options', 'wpg2/g2adminsideblock.php' );
	}

	if ($g2_option['g2_validated'] == "Yes" && current_user_can('edit_users')) {
		add_submenu_page('profile.php', __('Gallery2 Users', 'wpg2'), __('Gallery2 Users', 'wpg2'), 'edit_users', 'wpg2/g2users.php' );
	}
}

/**
* g2_processwpuseredithooks
* WP hook for User edit
*/

function g2_processwpuseredithooks() {

	// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {
		if ( strstr( $_SERVER['PHP_SELF'], "users.php" ) )  {
		// Manually Set User being unsync'd because WP missing User Hooks to trap event correctly
			$g2_option['g2_usersynched'] = "No";
			update_option('g2_options', $g2_option);
		}

		if ( substr( $_POST['submit'], 0, 14 )  == 'Update Profile' && $g2_option['g2_validated'] == "Yes" ) {
			$new_user_id     = $_POST['checkuser_id'];
			g2_updateuser($new_user_id);
		}

		if ( substr( $_POST['submit'], 0, 11 )  == 'Update User' &&  $g2_option['g2_validated'] == "Yes" ) {
			$new_user_id     = $_POST['user_id'];
			g2_updateuser($new_user_id);
		}
	}
}

/**
* g2_processwpuserhooks
* WP hook for User registration
*/

function g2_processwpuserhooks() {
// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {
		// Manually Set User being unsync'd because WP missing User Hooks to trap event correctly
			$g2_option['g2_usersynched'] = "No";
			update_option('g2_options', $g2_option);
	}
}

/**
* g2_themeswitchvalidate
* WP action hook for determining if the g2_externalheader/footer variables need to be changed on a switch
* of themes.
*/

function g2_themeswitchvalidate() {

// Get Gallery2 Option Settings
	global $g2_option;

	if ($g2_option['g2_validated'] == "Yes") {

	// Custom Header?
		if ( file_exists( TEMPLATEPATH . '/wpg2header.php') ) {
			$g2_option['g2_externalheader'] = "Yes";
		} else {
			$g2_option['g2_externalheader'] = "No";
		}

	// Custom Footer?
		if ( file_exists( TEMPLATEPATH . '/wpg2footer.php') ) {
			$g2_option['g2_externalfooter'] = "Yes";
		} else {
			$g2_option['g2_externalfooter'] = "No";
		}

	// Update the Options
		update_option('g2_options', $g2_option);

	}
}

/**
* g2_pluginactivate
* Sets up the defaults and makes sure that the WPG2 user capabilities are added.
*/
function g2_pluginactivate() {
	global $g2_option;

		delete_option('g2_options');

		// Set WPG2 Defaults
		$g2_option['g2_externalheader'] = "No";
		$g2_option['g2_externalfooter'] = "No";
		$g2_option['g2_header'] = '<style type="text/css">.g2_column {margin: 0px 1px 0px 12px;width: 738px}</style><div id="content" class="g2_column">';
		$g2_option['g2_footer'] = "</div>";
		$g2_option['g2_sidebarblock'][0] = ''; // Don't change this.  If you want a different default, change [1]
		$g2_option['g2_sidebarblock'][1] = 'randomImage';
		$g2_option['g2_sidebarblockshow'][0] = ''; // Don't change this.  If you want a different default, change [1]
		$g2_option['g2_sidebarblockshow'][1] = 'heading';
		$g2_option['g2_sidebarblockstodisplay'] = 4;
		$g2_option['g2_sidebarblockstype'] = 'randomImage';
		$g2_option['g2_sidebarblockinfo'][1] = 'heading';
		$g2_option['g2_postblockshow'] = array(1);
		$g2_option['g2_apiversion'] = array(1,0);
		$g2_option['g2_wpversion_low'] = "2";
		$g2_option['g2_wpversion_high'] = "3";
		$g2_option['g2_usersynched'] = "No";
		$g2_option['g2_postimgsize'] = "150";
		$g2_option['g2_sidebarblocksimgsize'] = "85";
		$g2_option['g2_sidebarblockimgsize'] = "150";
		$g2_option['g2_wpg2version'] = "2.0 RC1";
		$g2_option['g2_wpg2versionnumber'] = 2.0;

		require_once('G2EmbedDiscoveryUtilities.class');

		// Set up the URL's and Paths
		$site_url = trailingslashit(get_settings('siteurl'));
		$embedUri = G2EmbedDiscoveryUtilities::normalizeG2Uri($site_url);

		// Set the WPG2 Paths
		$g2_option['g2_url'] = "";
		$g2_option['g2_filepath'] = "";
		$g2_option['g2_embeduri'] = $embedUri."wp-gallery2.php";
		$g2_option['g2_errorredirect'] = $embedUri."index.php";

		update_option('g2_options', $g2_option);

		// Add Security Groups
		$wp_roles = new WP_Roles();
		$wp_roles->add_cap('contributor','gallery2_user');
		$wp_roles->add_cap('administrator','gallery2_user');
		$wp_roles->add_cap('editor','gallery2_user');
		$wp_roles->add_cap('author','gallery2_user');
		$wp_roles->add_cap('author','Unfiltered Html');
		$wp_roles->add_cap('editor','Unfiltered Html');
		$wp_roles->add_cap('contributor','Unfiltered Html');
		$wp_roles->add_cap('administrator','Unfiltered Html');
		$wp_roles->add_cap('administrator','gallery2_admin');
	
}

/**
* g2_pluginactivate
*  Removes all the WPG2 settings / Caps.
*/
function g2_plugindeactivate() {

	// Reset g2_options on deactivation..
	delete_option('g2_options');

	// Add Security Groups
	$wp_roles = new WP_Roles();
	$wp_roles->remove_cap('administrator','gallery2_user');
	$wp_roles->remove_cap('administrator','gallery2_admin');
	$wp_roles->remove_cap('contributor','gallery2_user');
	$wp_roles->remove_cap('editor','gallery2_user');
	$wp_roles->remove_cap('author','gallery2_user');
	$wp_roles->remove_cap('author','Unfiltered Html');
	$wp_roles->remove_cap('editor','Unfiltered Html');
	$wp_roles->remove_cap('contributor','Unfiltered Html');
}


/**
* g2_extended_editor_mce_plugins
* Adds g2image to the TinyMCE plugins list
*
* @param string $plugins the buttons string from the WP filter
* @return string the appended plugins string
*/

function g2_extended_editor_mce_plugins($plugins) {
	array_push($plugins, 'g2image');
	return $plugins;
}

/**
* g2_extended_editor_mce_buttons
* Adds g2image to the TinyMCE button bar
*
* @param string $buttons the buttons string from the WP filter
* @return string the appended buttons string
*/

function g2_extended_editor_mce_buttons($buttons) {
	array_push($buttons, 'separator', 'g2image');
	return $buttons;
}

/**
* g2_extended_editor_mce_valid_elements
* Adds WPG2 and WPG2ID tags to the TinyMCE valid elements list
*
* @param string $valid_elements the valid elements string from the WP filter
* @return string the appended valid elements string
*/

function g2_extended_editor_mce_valid_elements($valid_elements) {
	$valid_elements .= 'wpg2,wpg2id';
	return $valid_elements;
}

/**
* g2_callback
* Javascript appended to the bottom of the "Write Post" or "Write Page" admin pages for the WPG2 quicktag.
*/

function g2_callback() {

	$g2_wp_url = get_settings('siteurl');

	if(strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'bookmarklet.php'))
	{
?>

<script language="JavaScript" type="text/javascript"><!--

	var g2_toolbar = document.getElementById("ed_toolbar");
<?php
g2_edit_insert_button(__('WPG2', 'wpg2'), 'g2_open', __('Gallery2 Image Chooser', 'wpg2'));
?>
	function g2_open() {
		var form = 'post';
		var field = 'content';
		var url = '<?php echo $g2_wp_url; ?>/wp-includes/js/tinymce/plugins/g2image/g2image.php?g2ic_form='+form+'&g2ic_field='+field+'&g2ic_tinymce=0';
		var name = 'g2image';
		var w = 600;
		var h = 600;
		var valLeft = (screen.width) ? (screen.width-w)/2 : 0;
		var valTop = (screen.height) ? (screen.height-h)/2 : 0;
		var features = 'width='+w+',height='+h+',left='+valLeft+',top='+valTop+',resizable=1,scrollbars=1';
		var g2imageWindow = window.open(url, name, features);
		g2imageWindow.focus();
	}

//--></script>

<?php
	}
}

if(!function_exists('g2_edit_insert_button')) {

	//edit_insert_button: Inserts a button into the editor
	function g2_edit_insert_button($caption, $js_onclick, $title = '') {
	?>

	if(g2_toolbar){
		var theButton = document.createElement('input');
		theButton.type = 'button';
		theButton.value = '<?php echo $caption; ?>';
		theButton.onclick = <?php echo $js_onclick; ?>;
		theButton.className = 'ed_button';
		theButton.title = "<?php echo $title; ?>";
		theButton.id = "<?php echo "ed_{$caption}"; ?>";
		g2_toolbar.appendChild(theButton);
	}

	<?php
	}
}

//-------------------------------------------------------------------------------------------------------
// Admin User Functions
//-------------------------------------------------------------------------------------------------------

$plugin_filename= str_replace('\\', '/', preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__));

// Add User Sync Functions
add_action('user_register', 'g2_processwpuserhooks');

// Switching Theme's
add_action('switch_theme', 'g2_themeswitchvalidate');

// Add WP Menus
add_action('admin_menu', 'g2_addwpmenus');
add_action('user_menu', 'g2_addwpmenus');
add_action('admin_menu', 'g2_processwpuseredithooks');

// Logout
add_action('wp_logout', 'g2_logout');

// ImageFrames
add_action('wp_head', 'g2_imageframes');

// Filter for WPG2 Tags - Blog - G2WP Path in post
add_filter('the_content', 'g2_imagebypathinpost', 0);

// Filter for WPG2 Tags - Category - G2WP Path in post
add_filter('the_excerpt', 'g2_imagebypathinpost', 0);

// Filter for WPG2 Tags - Blog Excerpt -G2WP Path in post
add_filter('excerpt_save_pre', 'g2_imagebypathinpost', 0);

// Filter for WPG2 Tags - Blog - G2WP ID in post
add_filter('the_content', 'g2_imagebyidinpost', 0);

// Filter for WPG2 Tags - Category - G2WP ID in post
add_filter('the_excerpt', 'g2_imagebyidinpost', 0);

// Filter for WPG2 Tags - Comments - G2WP ID in post
add_filter('comment_text', 'g2_imagebyidinpost', 0);

// G2 Image Filters
add_filter('admin_footer', 'g2_callback');
add_filter('mce_plugins', 'g2_extended_editor_mce_plugins', 0);
add_filter('mce_buttons', 'g2_extended_editor_mce_buttons', 0);
add_filter('mce_valid_elements', 'g2_extended_editor_mce_valid_elements', 0);

?>