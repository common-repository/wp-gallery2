<?php
//  Initialization File for Gallery 2 Image Chooser
//  Version 2.0
//  By Kirk Steffensen - http://g2image.steffensenfamily.com/
//  Released under the GPL version 2.
//  A copy of the license is in the root folder of this plugin.

// ====( Initialize Variables )=================================
$g2ic_current_page = 1;
$g2ic_rel_path = '/';
$g2ic_wpg2_valid = FALSE;
$g2ic_language_loaded = FALSE;
$g2ic_dirs = null;
$g2ic_wp_rel_path = '';
$g2ic_base_path = str_repeat("../", substr_count(dirname($_SERVER['PHP_SELF']), "/"));
if(isset($_SESSION['g2ic_last_album_visited'])) {
	$g2ic_last_album = $_SESSION['g2ic_last_album_visited'];
}
else {
	$g2ic_last_album = '/';
}

// ==============================================================
// WPG2 validation
// ==============================================================

// Determine if in a WordPress installation by checking for wp-config.php
for ($count = 1; $count <= 10; $count++) {
	$g2ic_wp_rel_path = $g2ic_wp_rel_path.'../';
	if (file_exists($g2ic_wp_rel_path . 'wp-config.php')) {
		require_once($g2ic_wp_rel_path.'wp-config.php');
		require_once($g2ic_wp_rel_path.'wp-admin/admin.php');
		$g2_option = get_settings('g2_options');
		$g2ic_language = get_locale();

		if (file_exists('langs/' . $g2ic_language . '.php'))
			require_once('langs/' . $g2ic_language . '.php');
		else
			require_once('langs/en.php');

		$g2ic_language_loaded = TRUE;

		// Determine if WP-Gallery2 Plugin is present, active, and validated
		$current_plugins = get_option('active_plugins');
		if (in_array('wpg2/g2embed.php', $current_plugins)) {
			if ($g2_option['g2_validated'] == "Yes" ){
				$g2ic_wpg2_valid = TRUE;
				$g2ic_embedded_mode = TRUE;
				$g2ic_use_full_path = TRUE;

				// If WPG2 validated, call do g2ic_init with WPG2 URI info
				$g2ic_embed_uri = $g2_option['g2_embeduri'];
				$g2ic_gallery2_uri = $g2_option['g2_url'];
				$g2ic_gallery2_path = $g2_option['g2_filepath'];
			}

			// If WPG2 is active, but not validated, print an error
			else {
				print g2ic_make_html_header();
				print $g2ic_lang['wpg2_configuration_error'];
				print "</body>\n\n";
				print "</html>";
				die;
			}
		}

		// Get the configurations options from the WPG2 admin panel
		if(isset($g2_option['g2ic_images_per_page']))
			$g2ic_images_per_page = $g2_option['g2ic_images_per_page'];
		if(isset($g2_option['g2ic_display_filenames'])){
			if($g2_option['g2ic_display_filenames']=='yes')
				$g2ic_display_filenames = TRUE;
			else
				$g2ic_display_filenames = FALSE;
		}
		if(isset($g2_option['g2ic_default_alignment']))
			$g2ic_default_alignment = $g2_option['g2ic_default_alignment'];
		if(isset($g2_option['g2ic_custom_class_1']))
			$g2ic_custom_class_1 = $g2_option['g2ic_custom_class_1'];
		if(isset($g2_option['g2ic_custom_class_2']))
			$g2ic_custom_class_2 = $g2_option['g2ic_custom_class_2'];
		if(isset($g2_option['g2ic_custom_class_3']))
			$g2ic_custom_class_3 = $g2_option['g2ic_custom_class_3'];
		if(isset($g2_option['g2ic_custom_class_4']))
			$g2ic_custom_class_4 = $g2_option['g2ic_custom_class_4'];
		if(isset($g2_option['g2ic_custom_url']))
			$g2ic_custom_url = $g2_option['g2ic_custom_url'];
		if(isset($g2_option['g2ic_class_mode']))
			$g2ic_class_mode = $g2_option['g2ic_class_mode'];
		if(isset($g2_option['g2ic_click_mode']))
			$g2ic_click_mode = $g2_option['g2ic_click_mode'];
		if(isset($g2_option['g2ic_click_mode_variable'])){
			if($g2_option['g2ic_click_mode_variable']=='yes')
				$g2ic_click_mode_variable = TRUE;
			else
				$g2ic_click_mode_variable = FALSE;
		}
		if(isset($g2_option['g2ic_sortby']))
			$g2ic_sortby = $g2_option['g2ic_sortby'];
		if(isset($g2_option['g2ic_wpg2id_tags'])){
			if($g2_option['g2ic_wpg2id_tags']=='yes'){
				$g2ic_wpg2id_tags = TRUE;
			}
			else{
				$g2ic_wpg2id_tags = FALSE;
			}
		}
		else
			$g2ic_wpg2id_tags = TRUE;
		if(isset($g2_option['g2ic_default_action'])){
			if(!($g2_option['g2ic_default_action']=='wpg2'&&!$g2ic_wpg2_valid)){
				if($g2_option['g2ic_default_action']=='wpg2'){
					if($g2ic_wpg2id_tags)
						$g2ic_default_action = 'wpg2id_image';
					else
						$g2ic_default_action = 'wpg2_image';
				}
				else
					$g2ic_default_action = $g2_option['g2ic_default_action'];
			}
			else
				$g2ic_default_action = 'thumbnail_image';
		}
		else if($g2ic_wpg2_valid)
			$g2ic_default_action = 'wpg2id_image';
		else
			$g2ic_default_action = 'thumbnail_image';

		if(!$g2ic_wpg2_valid) {
			if (isset($g2_option['g2ic_gallery2_path']))
				$g2ic_gallery2_path = $g2_option['g2ic_gallery2_path'];
			$g2ic_use_full_path = FALSE;
		}

		break;
	}
}

// ==============================================================
// NOTE for developers:
// If you are developing an embedded application for Gallery2 and want to use
// this plugin for accessing Gallery2, this is where you'll need
// to validate that your ebedded page exists and then get the values from your
// embedded application $g2ic_gallery2_path, $g2ic_gallery2_uri, and
// $g2ic_embed_uri.  Descriptions of these variables are in config.php
//
// You'll also need to set $g2ic_embedded_mode to TRUE.
//
// If you use the full directory path for $g2ic_gallery_path, you'll need to set
// $g2ic_use_full_path to TRUE.  If you use a path relative to the root web page,
// you'll need to set $g2ic_use_full_path to FALSE.
//
// If your embedded application sets its own localization, you'll need to set the
// language in $g2ic_language.  If you need to load the language file for any
// initialization messages (as in the WPG2 code above), you'll need to load it
// before those messages appear.  If you do load it in your initialization
// sequence, set $g2ic_language_loaded to TRUE, so that the language pack won't
// get loaded again.
//
// See http://g2image.steffensenfamily.com for more details.
//
// ==============================================================

if(!$g2ic_language_loaded){
	if (file_exists('langs/' . $g2ic_language . '.php'))
		require_once('langs/' . $g2ic_language . '.php');
	else
		require_once('langs/en.php');
}

if(!$g2ic_embedded_mode)
	$g2ic_gallery2_uri = '/' . $g2ic_gallery2_path . 'main.php';

if(!$g2ic_use_full_path)
	$g2ic_gallery2_path = $g2ic_base_path.$g2ic_gallery2_path;

if(file_exists($g2ic_gallery2_path.'embed.php')) {
	require_once($g2ic_gallery2_path.'embed.php');

	if ($g2ic_embedded_mode){
		$g2ic_option['g2Uri'] = $g2ic_gallery2_uri;
		$g2ic_option['embedUri'] = $g2ic_embed_uri;
		g2ic_init($g2ic_option,$g2ic_embedded_mode);
	}
	else{
		$g2ic_option['g2Uri'] = $g2ic_gallery2_uri;
		g2ic_init($g2ic_option,$g2ic_embedded_mode);
	}
}
// Else die on a fatal error
else {
	print g2ic_make_html_header();
	print $g2ic_lang['g2_embedded_error'];
	print "</body>\n\n";
	print "</html>";
	die;
}


//---------------------------------------------------------------------
//	Function:	g2ic_init
//	Parameters:	$option,$embedded_mode
//	Returns:	None
//	Purpose:	Initialize the emedded functions of Gallery2.
//	Notes:		Exit on Fatal
//---------------------------------------------------------------------

function g2ic_init($option,$embedded_mode) {

	// Initialise GalleryAPI
	if ($embedded_mode){
		$ret = GalleryEmbed::init( array(
			'g2Uri' => $option['g2Uri'],
			'embedUri' => $option['embedUri'],
			'fullInit' => true)
		);
	}
	else{
		$ret = GalleryEmbed::init( array(
			'g2Uri' => $option['g2Uri'],
			'embedUri' => $option['g2Uri'],
			'fullInit' => true)
		);
	}
	if ($ret) {
		print g2ic_make_html_header();
		print '{$lang_g2image_g2_fatal_error}' .$ret->getAsHtml() . "\n";
		print "</body>\n\n";
		print "</html>";
		die;
	}

	return;
}
?>
