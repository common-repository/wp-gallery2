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
if (isset($_GET['updated'])) : ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Embedded Page options saved.', 'wpg2') ?></strong></p></div>
<?php endif;

// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

if ( $g2_option['g2_validated'] == "Yes" ) {
	?>
	<div class="wrap">
	<h2><?php _e('Embedded Page Options', 'wpg2') ?></h2>
	<form name="g2options" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="update" />
		<fieldset class="options">
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr>
		<th valign="top" scope="row"><?php _e('Simple Header<br/>(N/A when using Custom wpg2header)', 'wpg2') ?> </th>
		<td>
		<textarea name="g2_options[g2_header]" cols="40" rows="4"><?php echo stripslashes($g2_option['g2_header']); ?></textarea><br / >
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Simple Footer<br/>(N/A when using Custom wpg2footer)', 'wpg2') ?> </th>
		<td>
		<textarea name="g2_options[g2_footer]" cols="40" rows="4"><?php echo stripslashes($g2_option['g2_footer']); ?></textarea><br / >
		</td>
		</tr>
		</table>
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'wpg2') ?> &raquo;" />
		</p>
	</form>
	</div>
	<?php
	} else {
		?><div id="message" class="error"><p><strong><?php _e('WPG2 Plugin Validation Failed.. Options not available', 'wpg2') ?></strong></p></div>
		<?php
	}

?>