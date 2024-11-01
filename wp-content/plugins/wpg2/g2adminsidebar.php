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
	<div id="message" class="updated fade"><p><strong><?php _e('Sidebar Image options saved.', 'wpg2') ?></strong></p></div>
<?php endif;

// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

if ( $g2_option['g2_validated'] == "Yes" ) {
	?>
	<div class="wrap">
	<h2><?php _e('Sidebar Image Options', 'wpg2') ?></h2>
	<form name="g2options" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="update" />
		<fieldset class="options">
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr>
		<th valign="top" scope="row"><?php _e('Sidebar Block:', 'wpg2') ?> </th>
		<td>
		<table><tr><td colspan=2>
		<?php _e('Choose the type of block to display, one or more possible blocks may be checked.', 'wpg2') ?><br />
		</td></tr>
		<tr><td>
		<input type="hidden" name="g2_options[g2_sidebarblock][0]" value="">
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock1" value="randomImage" <?php if (in_array('randomImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Random Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock2" value="recentImage" <?php if (in_array('recentImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Recent Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock3" value="viewedImage" <?php if (in_array('viewedImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Viewed Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock4" value="randomAlbum" <?php if (in_array('randomAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Random Album', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock5" value="recentAlbum" <?php if (in_array('recentAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Recent Album', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock6" value="viewedAlbum" <?php if (in_array('viewedAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Viewed Album', 'wpg2') ?><br />
		</td><td>
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock7" value="dailyImage" <?php if (in_array('dailyImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Daily Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock8" value="weeklyImage" <?php if (in_array('weeklyImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Weekly Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock9" value="monthlyImage" <?php if (in_array('monthlyImage',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Monthly Image', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock10" value="dailyAlbum" <?php if (in_array('dailyAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Daily Album', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock11" value="weeklyAlbum" <?php if (in_array('weeklyAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Weekly Album', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblock][]" type="checkbox" id="g2_sidebarblock12" value="monthlyAlbum" <?php if (in_array('monthlyAlbum',$g2_option['g2_sidebarblock'])){echo "checked ";}?>/> <?php _e('Monthly Album', 'wpg2') ?><br />
		</td></tr></table>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Sidebar Block Details:', 'wpg2') ?> </th>
		<td>
		<table><tr><td colspan=2>
		<?php _e('Choose the any additional information to be displayed.', 'wpg2') ?><br />
		</td></tr>
		<tr><td>
		<input type="hidden" name="g2_options[g2_sidebarblockshow][0]" value="">
		<input name="g2_options[g2_sidebarblockshow][1]" type="checkbox" id="g2_sidebarblockshow1" value="title" <?php if (in_array('title',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('Title', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblockshow][2]" type="checkbox" id="g2_sidebarblockshow2" value="date" <?php if (in_array('date',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('Date', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblockshow][3]" type="checkbox" id="g2_sidebarblockshow3" value="views" <?php if (in_array('views',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('Views', 'wpg2') ?><br />
		</td><td>
		<input name="g2_options[g2_sidebarblockshow][4]" type="checkbox" id="g2_sidebarblockshow4" value="owner" <?php if (in_array('owner',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('Owner', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblockshow][5]" type="checkbox" id="g2_sidebarblockshow5" value="heading" <?php if (in_array('heading',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('Heading', 'wpg2') ?><br />
		<input name="g2_options[g2_sidebarblockshow][6]" type="checkbox" id="g2_sidebarblockshow6" value="fullSize" <?php if (in_array('fullSize',$g2_option['g2_sidebarblockshow'])){echo "checked ";}?>/> <?php _e('FullSize', 'wpg2') ?><br />
		</td></tr></table>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Maximum Thumbnail Image Size:', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_sidebarblockimgsize]" id="g2_sidebarblockimgsize" value="<?php echo $g2_option['g2_sidebarblockimgsize']; ?>" size="10" / ><br />
		<?php _e('Enter the Maximum size of the thumbnail to be displayed.', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Sidebar Item Frame:', 'wpg2') ?> </th>
		<td>
		<select name="g2_options[g2_sidebarblockimageFrame]">
		 <option label="<?php _e('None', 'wpg2') ?>" value="none"><?php _e('None', 'wpg2') ?></option>
		 <option label="<?php _e('Solid', 'wpg2') ?>" value="solid"<?php if ('solid' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Solid', 'wpg2') ?></option>
		 <option label="<?php _e('Bamboo', 'wpg2') ?>" value="bamboo"<?php if ('bamboo' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Bamboo', 'wpg2') ?></option>
		 <option label="<?php _e('Book', 'wpg2') ?>" value="book"<?php if ('book' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Book', 'wpg2') ?></option>
		 <option label="<?php _e('Branded Wood', 'wpg2') ?>" value="brand"<?php if ('brand' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Branded Wood', 'wpg2') ?></option>
		 <option label="<?php _e('Dots', 'wpg2') ?>" value="dots"<?php if ('dots' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Dots', 'wpg2') ?></option>
		 <option label="<?php _e('Gold', 'wpg2') ?>" value="gold"<?php if ('gold' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Gold', 'wpg2') ?></option>
		 <option label="<?php _e('Gold 2', 'wpg2') ?>" value="gold2"<?php if ('gold2' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Gold 2', 'wpg2') ?></option>
		 <option label="<?php _e('Spiral Notebook', 'wpg2') ?>" value="notebook"<?php if ('notebook' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Spiral Notebook', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroid', 'wpg2') ?>" value="polaroid"<?php if ('polaroid' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Polaroid', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroids', 'wpg2') ?>" value="polaroids"<?php if ('polaroids' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Polaroids', 'wpg2') ?></option>
		 <option label="<?php _e('Shadow', 'wpg2') ?>" value="shadow"<?php if ('shadow' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Shadow', 'wpg2') ?></option>
		 <option label="<?php _e('Shells', 'wpg2') ?>" value="shell"<?php if ('shell' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Shells', 'wpg2') ?></option>
		 <option label="<?php _e('Slide', 'wpg2') ?>" value="slide"<?php if ('slide' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Slide', 'wpg2') ?></option>
		 <option label="<?php _e('Wood', 'wpg2') ?>" value="wood"<?php if ('wood' == $g2_option['g2_sidebarblockimageFrame']){echo " selected";}?>><?php _e('Wood', 'wpg2') ?></option>
		 </select>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Sidebar Album Frame:', 'wpg2') ?> </th>
		<td>
		<select name="g2_options[g2_sidebarblockalbumFrame]">
		 <option label="<?php _e('None', 'wpg2') ?>" value="none"><?php _e('None', 'wpg2') ?></option>
		 <option label="<?php _e('Solid', 'wpg2') ?>" value="solid"<?php if ('solid' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Solid', 'wpg2') ?></option>
		 <option label="<?php _e('Bamboo', 'wpg2') ?>" value="bamboo"<?php if ('bamboo' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Bamboo', 'wpg2') ?></option>
		 <option label="<?php _e('Book', 'wpg2') ?>" value="book"<?php if ('book' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Book', 'wpg2') ?></option>
		 <option label="<?php _e('Branded Wood', 'wpg2') ?>" value="brand"<?php if ('brand' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Branded Wood', 'wpg2') ?></option>
		 <option label="<?php _e('Dots', 'wpg2') ?>" value="dots"<?php if ('dots' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Dots', 'wpg2') ?></option>
		 <option label="<?php _e('Gold', 'wpg2') ?>" value="gold"<?php if ('gold' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Gold', 'wpg2') ?></option>
		 <option label="<?php _e('Gold 2', 'wpg2') ?>" value="gold2"<?php if ('gold2' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Gold 2', 'wpg2') ?></option>
		 <option label="<?php _e('Spiral Notebook', 'wpg2') ?>" value="notebook"<?php if ('notebook' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Spiral Notebook', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroid', 'wpg2') ?>" value="polaroid"<?php if ('polaroid' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Polaroid', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroids', 'wpg2') ?>" value="polaroids"<?php if ('polaroids' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Polaroids', 'wpg2') ?></option>
		 <option label="<?php _e('Shadow', 'wpg2') ?>" value="shadow"<?php if ('shadow' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Shadow', 'wpg2') ?></option>
		 <option label="<?php _e('Shells', 'wpg2') ?>" value="shell"<?php if ('shell' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Shells', 'wpg2') ?></option>
		 <option label="<?php _e('Slide', 'wpg2') ?>" value="slide"<?php if ('slide' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Slide', 'wpg2') ?></option>
		 <option label="<?php _e('Wood', 'wpg2') ?>" value="wood"<?php if ('wood' == $g2_option['g2_sidebarblockalbumFrame']){echo " selected";}?>><?php _e('Wood', 'wpg2') ?></option>
		 </select>
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