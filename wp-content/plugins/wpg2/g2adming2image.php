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

	// Options Saved?
	if (isset($_GET['updated'])) {
		?><div id="message" class="updated fade"><p><strong><?php _e('G2Image Popup Options saved.', 'wpg2') ?></strong></p></div><?php
	}

	// Get Gallery2 Option Settings
		$g2_option = get_settings('g2_options');

	if ( $g2_option['g2_validated'] == "Yes" ) {
		?>
			<div class="wrap">
			<h2><?php _e('G2Image Popup Options', 'wpg2') ?></h2>
			<form name="g2options" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
			<input type="hidden" name="action" value="update" />
			<fieldset class="options">
			<table width="100%" cellspacing="2" cellpadding="5" class="editform">
			<tr>
			<th valign="top" scope="row"><?php _e('Thumbnails per Page:', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_images_per_page]" id="g2ic_images_per_page" value="<?php if(isset($g2_option['g2ic_images_per_page'])){echo $g2_option['g2ic_images_per_page'];} else{echo "15";} ?>" size="10" / ><br />
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Default Image Sort Order:', 'wpg2') ?></th>
			<td>
			<select name="g2_options[g2ic_sortby]">
				<option value="title_asc"<?php if ($g2_option['g2ic_sortby']=="title_asc" ){ echo " selected";} ?>><?php _e('Gallery2 Title (A-z)', 'wpg2') ?></option>
				<option value="title_desc"<?php if ($g2_option['g2ic_sortby']=="title_desc" ){ echo " selected";} ?>><?php _e('Gallery2 Title (z-A)', 'wpg2') ?></option>
				<option value="name_asc"<?php if ($g2_option['g2ic_sortby']=="name_asc" ){ echo " selected";} ?>><?php _e('Filename (A-z)', 'wpg2') ?></option>
				<option value="name_desc"<?php if ($g2_option['g2ic_sortby']=="name_desc" ){ echo " selected";} ?>><?php _e('Filename (z-A)', 'wpg2') ?></option>
				<option value="mtime_desc"<?php if ($g2_option['g2ic_sortby']=="mtime_desc" ){ echo " selected";} ?>><?php _e('Last Modification (newest first)', 'wpg2') ?></option>
				<option value="mtime_asc"<?php if ($g2_option['g2ic_sortby']=="mtime_asc" ){ echo " selected";} ?>><?php _e('Last Modification (oldest first)', 'wpg2') ?></option>
			</select> <br />
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Default Display', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_display_filenames]" type="radio" id="g2ic_display_filenames_false" value="no" <?php if(!isset($g2_option['g2ic_display_filenames'])){echo "checked ";} elseif ($g2_option['g2ic_display_filenames']=="no"){echo "checked ";}?> /><?php _e('Thumbnails Only', 'wpg2') ?><br />
			<input name="g2_options[g2ic_display_filenames]" type="radio" id="g2ic_display_filenames_true" value="yes" <?php if ($g2_option['g2ic_display_filenames']=="yes"){echo "checked ";}?> /><?php _e('Thumbnails with Titles and Filenames', 'wpg2') ?>
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Default Click Action', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_click_mode]" type="radio" id="g2ic_click_mode_one_click_insert" value="one_click_insert" <?php if(!isset($g2_option['g2ic_click_mode'])){echo "checked ";} elseif ($g2_option['g2ic_click_mode']=="one_click_insert"){echo "checked ";}?> /><?php _e('Instantly insert using default settings', 'wpg2') ?><br />
			<input name="g2_options[g2ic_click_mode]" type="radio" id="g2ic_click_mode_show_advanced_options" value="show_advanced_options" <?php if ($g2_option['g2ic_click_mode']=="show_advanced_options"){echo "checked ";}?> /><?php _e('Show Advanced Options Panel', 'wpg2') ?>
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Allow User to Change Click Options?', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_click_mode_variable]" type="radio" id="g2ic_click_mode_variable_true" value="yes" <?php if(!isset($g2_option['g2ic_click_mode_variable'])){echo "checked ";} elseif ($g2_option['g2ic_click_mode_variable']=="yes"){echo "checked ";}?> /><?php _e('Yes', 'wpg2') ?><br />
			<input name="g2_options[g2ic_click_mode_variable]" type="radio" id="g2ic_click_mode_variable_false" value="no" <?php if ($g2_option['g2ic_click_mode_variable']=="no"){echo "checked ";}?> /><?php _e('No', 'wpg2') ?>
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('WPG2ID or WPG2 Tags?', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_wpg2id_tags]" type="radio" id="g2ic_wpg2id_tags_true" value="yes" <?php if(!isset($g2_option['g2ic_wpg2id_tags'])){echo "checked ";} elseif ($g2_option['g2ic_wpg2id_tags']=="yes"){echo "checked ";}?> /><?php _e('WPG2ID Tags (Recommended.  Allows changing Gallery2 arrangement without updating existing WordPress posts.)', 'wpg2') ?><br />
			<input name="g2_options[g2ic_wpg2id_tags]" type="radio" id="g2ic_wpg2id_tags_false" value="no" <?php if ($g2_option['g2ic_wpg2id_tags']=="no"){echo "checked ";}?> /><?php _e('WPG2 Tags', 'wpg2') ?>
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Default Action:', 'wpg2') ?></th>
			<td>
			<?php _e('Choose the default "How to Insert" option.', 'wpg2') ?><br />
			<select name="g2_options[g2ic_default_action]">
				<option value="wpg2"<?php if ($g2_option['g2ic_default_action']=="wpg2" ){ echo " selected";} ?>><?php _e('WPG2/WPG2ID Tag', 'wpg2') ?></option>
				<option value="thumbnail_image"<?php if ($g2_option['g2ic_default_action']=="thumbnail_image" ){ echo " selected";} ?>><?php _e('Thumbnail with link to image', 'wpg2') ?></option>
				<option value="thumbnail_album"<?php if ($g2_option['g2ic_default_action']=="thumbnail_album" ){ echo " selected";} ?>><?php _e('Thumbnail with link to parent album', 'wpg2') ?></option>
				<option value="thumbnail_custom_url"<?php if ($g2_option['g2ic_default_action']=="thumbnail_custom_url" ){ echo " selected";} ?>><?php _e('Thumbnail with link to custom URL', 'wpg2') ?></option>
				<option value="thumbnail_only"<?php if ($g2_option['g2ic_default_action']=="thumbnail_only" ){ echo " selected";} ?>><?php _e('Thumbnail only - no link', 'wpg2') ?></option>
				<option value="link_image"<?php if ($g2_option['g2ic_default_action']=="link_image" ){ echo " selected";} ?>><?php _e('Text link to image', 'wpg2') ?></option>
				<option value="link_album"<?php if ($g2_option['g2ic_default_action']=="link_album" ){ echo " selected";} ?>><?php _e('Text link to parent album', 'wpg2') ?></option>
			</select> <br />
			</td>
			</tr>
			<tr>
			<th valign="top" scope="row"><?php _e('Default Custom URL:', 'wpg2') ?> </th>
			<td>
			<input name="g2_options[g2ic_custom_url]" id="g2ic_custom_url" value="<?php if(isset($g2_option['g2ic_custom_url'])){echo $g2_option['g2ic_custom_url'];} else{echo "http://";} ?>" size="50" / ><br />
			</td>
			</tr>
			</table>
			</fieldset>
			<p class="submit">
				<input type="submit" name="submit" value="<?php _e('Update Options', 'wpg2') ?> &raquo;" />
			</p>
		</form>
		</div>
		<?php
	} else {
		?><div id="message" class="error"><p><strong><?php _e('WPG2 Plugin Validation Failed.. Options not available', 'wpg2') ?></strong></p></div>
		<?php
	}

?>