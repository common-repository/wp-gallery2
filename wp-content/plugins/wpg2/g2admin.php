<?php
/*
	Author: WordPress Gallery Team
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

//If we're setting options,
if ( isset($_POST['action']) ) {
	check_admin_referer();

	if ($_POST['action'] == 'validate') {

		require_once('G2EmbedDiscoveryUtilities.class');

		// Set up the URL's and Paths
		$site_url = trailingslashit(get_settings('siteurl'));
		$g2Uri = G2EmbedDiscoveryUtilities::normalizeG2Uri($_POST['g2url']);
		$embedUri = G2EmbedDiscoveryUtilities::normalizeG2Uri($site_url);
		$file_path = ABSPATH;

		list ($success, $embedPhpPath, $errorString) = G2EmbedDiscoveryUtilities::getG2EmbedPathByG2Uri($g2Uri);
		if (!$success)
			list ($success, $embedPhpPath, $errorString) = G2EmbedDiscoveryUtilities::getG2EmbedPathByG2UriEmbedUriAndLocation($g2Uri, $embedUri, $file_path);

		if (!$success) {
		// Return the Error

?>
				<div id="message" class="error"><p><strong><?php echo $errorString; ?></strong></p></div>
				<?php
		} else {
		// Set the Plugin Paths
			$g2_option['g2_url'] = $g2Uri;
			$g2_option['g2_filepath'] = rtrim ($embedPhpPath, "embed.php");

			update_option('g2_options', $g2_option);
		}
	}

	else if ($_POST['action'] == 'update') {
		// Option Saving Time
		$g2_option = get_settings('g2_options');
		foreach ($_POST['g2_options'] as $key=>$value){
			$g2_option[$key] = $value;
		}
		update_option('g2_options', $g2_option);

		$referred = remove_query_arg('updated' , $_SERVER['HTTP_REFERER']);
		$goback = add_query_arg('updated', 'true', $_SERVER['HTTP_REFERER']);
		$goback = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $goback);
		header('Location: ' . $goback);
	}

	else if ($_POST['action'] == 'reset') {
		g2_pluginactivate();
	}

	else if ($_POST['action'] == 'delete') {
		g2_plugindeactivate();
		header('Location: plugins.php');
	}
}


// Initialisation
	$validate_err=0;
	//Get Current Gallery2 Plug-in Options
	$g2_option = get_settings('g2_options');


?>
	<div id="show_checking_status" class="wrap">
	<h2><?php _e('Checking System Status...', 'wpg2') ?></h2>

<?php
// Gallery Ok?
	$environment_html = '<tr><td>' . __('Gallery2 API located in G2 Path?', 'wpg2') . '</td><td>';
	if (file_exists( $g2_option['g2_filepath'].'embed.php' ) )
		$environment_html .= '<font color="green">' . __('Success', 'wpg2');
	else {
		$environment_html .= '<font color="red">' . __('Failed', 'wpg2');
				$validate_err=1;
	}
	$environment_html .= "</font></td></tr>";


// g2image installed?
	$environment_html .= '<tr><td>' . __('G2Image located in TinyMCE plugins folder?', 'wpg2') . '</td><td>';
	if (file_exists('../wp-includes/js/tinymce/plugins/g2image/g2image.php' ) )
		$environment_html .= '<font color="green">' . __('Success', 'wpg2');
	else {
		$environment_html .= '<font color="red">' . __('Failed', 'wpg2');
				$validate_err=1;
	}
	$environment_html .= "</font></td></tr>";



// Validate G2 Plug-In
	if (!$validate_err) {
		$g2_option['g2_validated'] = "Yes";
		update_option('g2_options', $g2_option);
	}

// Test Initialise G2
	$environment_html .='<tr><td>' . __('Gallery2 init?', 'wpg2') . '</td><td>';
	if (!$validate_err) {
		if (!defined('G2INIT')) {
			$ret = g2_init();
			if (!$ret)
				$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
			else {
				$environment_html .= '<font color="red">' . __('Failed', 'wpg2') . '-><font>' . $ret->getAsHtml();
				$validate_err=1;
			}
		} else
				$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';
	$environment_html .= "</td></tr>";

// Test G2 API Version
	$environment_html .= '<tr><td>' . __('Gallery2 Version Compatible?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		$ret = GalleryEmbed::isCompatibleWithEmbedApi($g2_option['g2_apiversion']);
		if ($ret)
			$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
		else {
			$environment_html .= '<font color="red">' . __('Failed', 'wpg2') . '</font>';
			$validate_err=1;
		}
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';
	$environment_html .= "</td></tr>";

// Test Wordpress Version
	global $wp_version;
	$environment_html .= '<tr><td>' . __('Wordpress Version Compatible?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		if ($wp_version >=$g2_option['g2_wpversion_low'] && $wp_version <=$g2_option['g2_wpversion_high'])
			$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
		else {
			$environment_html .= '<font color="red">' . __('Failed', 'wpg2') . '</font>';
			$validate_err=1;
		}
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';
	$environment_html .= "</td></tr>";


// ImageBlock Active?
	$environment_html .= '<tr><td>' . __('Gallery2 Module: ImageBlock Active?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		$g2_image_block_active = 0;
		$g2_moduleid = 'imageblock';
		list ($ret, $g2_modulestatus ) = GalleryCoreApi::fetchPluginStatus('module');
		if (!$ret) {
			if (isset($g2_modulestatus[$g2_moduleid]) && !empty($g2_modulestatus[$g2_moduleid]['active']) && $g2_modulestatus[$g2_moduleid]['active'])
				$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
			else {
				$environment_html .= '<font color="red">' . __('Failed', 'wpg2') . '</font>';
				$validate_err=1;
			}
		}
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';
	$environment_html .= "</td></tr>";

// ImageFrame Active?
	$environment_html .= '<tr><td>' . __('Gallery2 Module: ImageFrame Active?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		$g2_image_block_active = 0;
		$g2_moduleid = 'imageframe';
		list ($ret, $g2_modulestatus ) = GalleryCoreApi::fetchPluginStatus('module');
		if (!$ret) {
			if (isset($g2_modulestatus[$g2_moduleid]) && !empty($g2_modulestatus[$g2_moduleid]['active']) && $g2_modulestatus[$g2_moduleid]['active'])
				$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
			else {
				$environment_html .= '<font color="red">' . __('Failed', 'wpg2') . '</font>';
				$validate_err=1;
			}
		}
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';
	$environment_html .= "</td></tr>";

// Start Rewrite Module Testing
	$environment_html .= '<tr><td>' . __('Gallery2 Module: Rewrite Active?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		list ($ret, $rewriteApi) = GalleryCoreApi::newFactoryInstance('RewriteApi');
		if ($rewriteApi) { // Module Active
			$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
		} else $environment_html .= '<font color="black">' . __('Not Active', 'wpg2') . '</font>';
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';

	$environment_html .= '<tr><td>' . __('Gallery2 URL Rewrite Rules Updated?' , 'wpg2') . '</td><td>';
	if (!$validate_err) {
		if ($rewriteApi) {
			$required = array(1, 0);
			list ($ret, $iscompatible) = $rewriteApi->isCompatibleWithApi($required);
			if ($iscompatible) { // API OK
				$errstr = g2_configurerewrites(); // Call the Auto-configuration Function
				if (empty($errstr)) {
					list ($ret, $configrequired) = $rewriteApi->needsEmbedConfig();
					if ($configrequired) {
						$environment_html .= '<font color="red">' . __('Failed with Incomplete Configuration', 'wpg2') . '</font>';
						$validate_err=1;
					} else
						$environment_html .= '<font color="green">' . __('Success', 'wpg2') . '</font>';
				} else {
					$environment_html .= '<font color="red">' . sprintf(__('Active But Config Failed with error: %s', 'wpg2'), $errstr) . '</font>';
					$validate_err=1;
				}
			} else $environment_html .= '<font color="black">' . __('Skipped Config cannot be Verified', 'wpg2') . '</font>';
		} else $environment_html .= '<font color="black">' . __('Not Active', 'wpg2') . '</font>';
	} else $environment_html .= '<font color="red">' . __('Skipped Due to Previous Error', 'wpg2') . '<font>';

// Custom Header?
	if ( file_exists( TEMPLATEPATH . '/wpg2header.php') ) {
		$environment_html .= '<tr><td>' . __('Using External Header?', 'wpg2') . '</td>';
		$environment_html .= '<td><font color="green">' . __('Success', 'wpg2');
		$g2_option['g2_externalheader'] = "Yes";
	} else {
		$environment_html .= '<tr><td>' . __('Using Simple Header?', 'wpg2') . '</td>';
		$environment_html .= '<td><font color="green">' . __('Success', 'wpg2');
		$g2_option['g2_externalheader'] = "No";
	}
	$environment_html .= "</td></tr>";

// Custom Footer?
	if ( file_exists( TEMPLATEPATH . '/wpg2footer.php') ) {
		$environment_html .= '<tr><td>' . __('Using External Footer?', 'wpg2') . '</td>';
		$environment_html .= '<td><font color="green">' . __('Success', 'wpg2');
		$g2_option['g2_externalfooter'] = "Yes";
	} else {
		$environment_html .= '<tr><td>' . __('Using Simple Footer?', 'wpg2') . '</td>';
		$environment_html .= '<td><font color="green">' . __('Success', 'wpg2');
		$g2_option['g2_externalfooter'] = "No";
	}
	$environment_html .= "</td></tr>";
	$environment_html .= "</table><br/>";

	if ($validate_err) {
		// Make sure the Plug In Validation Status is Not Validated.
		if ( $g2_option['g2_validated'] = "Yes" ) {
			$g2_option['g2_validated'] = "No";
		}
	}

// Update the Options
	update_option('g2_options', $g2_option);

	?>
	</div>

	<script type="text/javascript">
		document.getElementById("show_checking_status").style.display = 'none';
		function toggle_auto_config_form() {
			if (document.getElementById('auto_configuration').style.display == 'none')
				document.getElementById('auto_configuration').style.display = 'block';
			else
				document.getElementById('auto_configuration').style.display = 'none';
		}
		function toggle_manual_config_form() {
			if (document.getElementById('manual_configuration').style.display == 'none')
				document.getElementById('manual_configuration').style.display = 'block';
			else
				document.getElementById('manual_configuration').style.display = 'none';
		}
	</script>

	<br clear="all" />
	<div class="wrap">

<?php
if ($validate_err){
	echo '<h2>' . __('WPG2 Plugin ', 'wpg2') . '<font color="red">' . __('Validation Failed', 'wpg2') . '</h2>';
	echo '<h3>' . __('Please see "WPG2 Environment Status" below for reasons.', 'wpg2') . '</font></h3>';
	echo '<div id = "auto_configuration">';
} else {
	echo '<h2>' . __('WPG2 Plugin ', 'wpg2') . '<font color="green">' . __('Validated Successfully', 'wpg2') . '</font></h2>';
	echo '<button onclick="toggle_auto_config_form()">' . __('Show/Hide Auto Configuration Form &raquo ', 'wpg2') . '</button><br />';
	echo __('Your configuration is valid.', 'wpg2') . '<br />';
	echo __('You should only need to use auto configuration again if you are moving your Gallery to a new location.', 'wpg2') . '<br />';
	echo '<div id = "auto_configuration" style="display: none">';
}
?>
	<h2><?php _e('Auto Configuration of Embedded Paths', 'wpg2') ?></h2>
	<form name="g2paths" method="post" action="admin.php?page=wpg2/g2admin.php">
		<input type="hidden" name="action" value="validate" />
		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> <tr><td></td><td>
		<tr>
		<th valign="top" scope="row"><?php _e('Gallery2 URL:', 'wpg2') ?></th>
		<td>
		<input name="g2url" type="text" id="g2url" value="<?php echo $g2url ?>" size="70" /><br />
		<?php _e('URL to Gallery2.', 'wpg2') ?><br /><?php _e('Example: http://www.domain.com/gallery2/main.php', 'wpg2') ?><br /><?php _e('Recommend pasting in the URL from a browser window when at your Gallery2 homepage.', 'wpg2') ?><br />
		</td>
		</tr>
		</table>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Auto Configure', 'wpg2') ?> &raquo;" />
		</p>
	</form>
	</div>
	<br />
	<button onclick="toggle_manual_config_form()"><?php _e('Show/Hide Manual Configuration Form &raquo', 'wpg2') ?> </button><br />
	<?php _e('If you are having problems with automatic configuration, you can use manual configuration.', 'wpg2') ?>

	<br clear="all" />
	<div id = "manual_configuration" style="display: none">
	<h2><?php _e('Manually Configure/Adjust Embedded Paths', 'wpg2') ?></h2>
	<form name="g2options" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="update" />
		<fieldset class="options">
		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> <tr><td></td><td>
		<tr>
		<th valign="top" scope="row"><?php _e('Gallery2 URL:', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_url]" type="text" id="g2_url" value="<?php echo $g2_option['g2_url']; ?>" size="70" /><br />
		<?php _e('URL to Gallery2.<br />Example: http://www.domain.com/gallery2/main.php<br />Recommend pasting in the URL from a browser window when at your Gallery2 homepage.', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('URI to Gallery2 Embed Page (wp-gallery2.php):', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_embeduri]" type="text" id="g2_embed" value="<?php echo $g2_option['g2_embeduri']; ?>" size="70" /><br />
		<?php _e('URL to wp-gallery2.php (or, if renamed, to the renamed page).', 'wpg2') ?><br /><?php _e('Example: http://www.domain.com/wordpress/wp-gallery2.php', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<th width="25%" valign="top" scope="row"><?php _e('Gallery2 File Path:', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_filepath]" type="text" id="g2_path" value="<?php echo $g2_option['g2_filepath']; ?>" size="70" /><br />
		<?php _e('This is the File path to your Gallery2 installation.<br />Example: /usr/name/public_html/gallery2/', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<tr>
		<th valign="top" scope="row"><?php _e('On Gallery Error Redirect to:', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_errorredirect]" type="text" id="g2_errorredirect" value="<?php echo $g2_option['g2_errorredirect']; ?>" size="70" /><br />
		<?php _e('The page you want to redirect to if there is a Gallery login error.', 'wpg2') ?><br />
		</td>
		</tr>
		</table>
		<input type="hidden" id="g2_validated" name="g2_options[g2_validated]" value="No">
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'wpg2') ?> &raquo;" />
		</p>
	</form>
	</div>
<?php
//Status
	echo '<h2>' . __('WPG2 Environment Status', 'wpg2') . '</h2>';
	echo "	<table width='800' bordercolor='#242424' border=1 cellpadding=1 cellspacing=0 >";
	echo $environment_html;
?>
	<h2><?php _e('WPG2 Plug-in Operations', 'wpg2') ?></h2>
	<form name="g2reset" method="post" action="admin.php?page=wpg2/g2admin.php">
		<input type="hidden" name="action" value="reset" />
		<button name="submit"><?php _e('Reset WPG2 Options to Defaults', 'wpg2')?></button><br />
		<?php _e('Reset WPG2 Options to Defaults', 'wpg2') ?>
	</form>
	<br />
	<form name="g2reset" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="delete" />
		<button name="Submit"><?php _e('Delete all WPG2 Options', 'wpg2') ?> </button><br />
		<?php _e('Only do this in preparation for permanently deactivating the plugin.  You will be taken to the plugins page.  You should immediately deactivate WPG2.  If you reactive WPG2, all settings will be reset to their defaults.', 'wpg2') ?>
	</form>
</div>