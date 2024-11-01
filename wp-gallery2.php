<?php
/*
	Author: WordPress Gallery Team
	Author URI: http://wpg2.galleryembedded.com/
	Version: 2.0
	Updated: 15/03/2006

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/

/***************************************************
Gallery2  Embedded WordPress

***************************************************/
//Include current Wordpress Theme Header etc.
// In PHP/CGI mode, this gets set to /gallery2.php, but that can confuse
// WP who expects that to be a rewrite rule of some kind.  So since we know
// that we're not using PathInfo, just unset this here before we load
// wp-blog-header.php
unset($_SERVER['PATH_INFO']);

//Include current WordPress Theme Header etc.
require('./wp-config.php');

//Get Gallery Plug-in Settings
$g2_option = get_settings('g2_options');

//Check if we're wrapping the WP Theme
//Get Theme settings.
$themes = get_themes();
$current_theme = get_current_theme();
$current_template_dir = $themes[$current_theme]['Template Dir'];
$current_stylesheet_dir = $themes[$current_theme]['Stylesheet Dir'];

// Log into G2
$ret = g2_login();

if (!$ret) {
	$g2data = GalleryEmbed::handleRequest();

	if ($g2data['isDone']) {
		exit; // G2 has already sent output (redirect or binary data)
	}

	// Should we Disable the Header output and instead allow the g2wpheader to control the Output?
	if ( $g2_option['g2_externalheader']=="Yes" )
		include ($current_stylesheet_dir.'/wpg2header.php');
	else {
		//Initialize the WP class to be able to get the header
		wp();
		//Set status to 200 to override the 404 set by WordPress
		status_header(200);
		//Set $g2_wp_init to TRUE so that won't do again for footer
		$g2_wp_init = TRUE;
		//Include the WP Header
		get_header();
		//Include any plug-in header content set in the plugin options
		echo stripslashes($g2_option['g2_header']);

	}

	// Display G2 Output
	if ( $g2_option['g2_externalheader']=="No" ) {
		echo $g2data['headHtml'];    //Include the gallery header
	}
	echo $g2data['bodyHtml'];	 //Display the gallery content

	//Close Gallery Connection
	GalleryEmbed::done();

	// Should we Disable the Header output and instead allow the g2wpheader to control the Output?
	if ($g2_option['g2_externalfooter']=="Yes" ) {
		include ($current_stylesheet_dir.'/wpg2footer.php');
	} else {
		//Initialize WP class if not already initialized to be able to get the footer
		if(!$g2_wp_init)
			wp();
			//Set status to 200 to override the 404 set by WordPress
			status_header(200);
		//Include plug-in footer content
		echo stripslashes($g2_option['g2_footer']);
		//Include WP footer
		get_footer();
	}
}

?>