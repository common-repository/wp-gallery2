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
	<div id="message" class="updated fade"><p><strong><?php _e('Block options saved.', 'wpg2') ?></strong></p></div>
<?php endif;

// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

if ( $g2_option['g2_validated'] == "Yes" ) {
	?>
	<div class="wrap">
	<h2><?php _e('Sidebar Block Options', 'wpg2') ?></h2>
	<form name="g2options" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="update" />
		<fieldset class="options">
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr>
		<th valign="top" scope="row"><?php _e('Number of images:', 'wpg2') ?> </th>
		<td>
		<table><tr><td colspan=3>
		<?php _e('Select the number of images to display.', 'wpg2') ?><br />
		</td></tr>
		<tr><td>
		<select name="g2_options[g2_sidebarblockstodisplay]">
			<option label="<?php _e('None', 'wpg2') ?>" value=""<?php if ('' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('None', 'wpg2') ?></option>
			<option label="<?php _e('One', 'wpg2') ?>" value="1"<?php if ('1' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('One', 'wpg2') ?></option>
			<option label="<?php _e('Two', 'wpg2') ?>" value="2"<?php if ('2' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Two', 'wpg2') ?></option>
			<option label="<?php _e('Three', 'wpg2') ?>" value="3"<?php if ('3' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Three', 'wpg2') ?></option>
			<option label="<?php _e('Four', 'wpg2') ?>" value="4"<?php if ('4' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Four', 'wpg2') ?></option>
			<option label="<?php _e('Five', 'wpg2') ?>" value="5"<?php if ('5' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Five', 'wpg2') ?></option>
			<option label="<?php _e('Six', 'wpg2') ?>" value="6"<?php if ('6' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Six', 'wpg2') ?></option>
			<option label="<?php _e('Seven', 'wpg2') ?>" value="7"<?php if ('7' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Seven', 'wpg2') ?></option>
			<option label="<?php _e('Eight', 'wpg2') ?>" value="8"<?php if ('8' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Eight', 'wpg2') ?></option>
			<option label="<?php _e('Nine', 'wpg2') ?>" value="9"<?php if ('9' == $g2_option['g2_sidebarblockstodisplay']){echo " selected";}?>><?php _e('Nine', 'wpg2') ?></option>
		</select>
		</td>
		</tr></table>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Block Type:', 'wpg2') ?> </th>
		<td>
		<table><tr><td colspan=2>
		<?php _e('Select the type of block to display.', 'wpg2') ?><br />
		</td></tr>
		<tr><td>
		<select name="g2_options[g2_sidebarblockstype]">
			<option label="<?php _e('Random Image', 'wpg2') ?>" value="randomImage"<?php if ('randomImage' == $g2_option['g2_sidebarblockstype']){echo " selected";}?>><?php _e('Random Image', 'wpg2') ?></option>
			<option label="<?php _e('Recent Image', 'wpg2') ?>" value="recentImage"<?php if ('recentImage' == $g2_option['g2_sidebarblockstype']){echo " selected";}?>><?php _e('Recent Image', 'wpg2') ?></option>
			<option label="<?php _e('Random Album', 'wpg2') ?>" value="randomAlbum"<?php if ('randomAlbum' == $g2_option['g2_sidebarblockstype']){echo " selected";}?>><?php _e('Random Album', 'wpg2') ?></option>
			<option label="<?php _e('Recent Album', 'wpg2') ?>" value="recentAlbum"<?php if ('recentAlbum' == $g2_option['g2_sidebarblockstype']){echo " selected";}?>><?php _e('Recent Album', 'wpg2') ?></option>
		</select>
		</td></tr></table>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Block Thumbnail Maximum Size:', 'wpg2') ?> </th>
		<td>
		<?php _e('Enter the size of the thumbnail to be displayed.', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblocksimgsize]" id="g2_sidebarblocksimgsize" value="<?php echo $g2_option['g2_sidebarblocksimgsize']; ?>" size="5" / ><br />
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Block Frame:', 'wpg2') ?> </th>
		<td>
		<?php _e('Select the type of frame to be displayed.', 'wpg2') ?><br />
		<select name="g2_options[g2_sidebarblocksimageFrame]">
		 <option label="<?php _e('None', 'wpg2') ?>" value="none"><?php _e('None', 'wpg2') ?></option>
		 <option label="<?php _e('Solid', 'wpg2') ?>" value="solid"<?php if ('solid' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Solid', 'wpg2') ?></option>
		 <option label="<?php _e('Bamboo', 'wpg2') ?>" value="bamboo"<?php if ('bamboo' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Bamboo', 'wpg2') ?></option>
		 <option label="<?php _e('Book', 'wpg2') ?>" value="book"<?php if ('book' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Book', 'wpg2') ?></option>
		 <option label="<?php _e('Branded Wood', 'wpg2') ?>" value="brand"<?php if ('brand' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Branded Wood', 'wpg2') ?></option>
		 <option label="<?php _e('Dots', 'wpg2') ?>" value="dots"<?php if ('dots' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Dots', 'wpg2') ?></option>
		 <option label="<?php _e('Gold', 'wpg2') ?>" value="gold"<?php if ('gold' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Gold', 'wpg2') ?></option>
		 <option label="<?php _e('Gold 2', 'wpg2') ?>" value="gold2"<?php if ('gold2' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Gold 2', 'wpg2') ?></option>
		 <option label="<?php _e('Spiral Notebook', 'wpg2') ?>" value="notebook"<?php if ('notebook' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Spiral Notebook', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroid', 'wpg2') ?>" value="polaroid"<?php if ('polaroid' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Polaroid', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroids', 'wpg2') ?>" value="polaroids"<?php if ('polaroids' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Polaroids', 'wpg2') ?></option>
		 <option label="<?php _e('Shadow', 'wpg2') ?>" value="shadow"<?php if ('shadow' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Shadow', 'wpg2') ?></option>
		 <option label="<?php _e('Shells', 'wpg2') ?>" value="shell"<?php if ('shell' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Shells', 'wpg2') ?></option>
		 <option label="<?php _e('Slide', 'wpg2') ?>" value="slide"<?php if ('slide' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Slide', 'wpg2') ?></option>
		 <option label="<?php _e('Wood', 'wpg2') ?>" value="wood"<?php if ('wood' == $g2_option['g2_sidebarblocksimageFrame']){echo " selected";}?>><?php _e('Wood', 'wpg2') ?></option>
		 </select>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Block Details', 'wpg2') ?> </th>
		<td>
		<?php _e('Choose any additional information to be displayed.', 'wpg2') ?><br />
		<input type="hidden" name="g2_options[g2_sidebarblockinfo][0]" value="">
		<input name="g2_options[g2_sidebarblockinfo][]" type="checkbox" id="g2_sidebarblockinfo1" value="heading" <?php if (in_array('heading',$g2_option['g2_sidebarblockinfo'])){echo "checked ";}?>/> <?php _e('Heading', 'wpg2') ?><br />
		</td>
		</tr>
		</table></fieldset>
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