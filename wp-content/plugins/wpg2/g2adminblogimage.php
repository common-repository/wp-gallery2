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
?>
	<div id="message" class="updated fade"><p><strong><?php _e('Blog Image options saved.', 'wpg2') ?></strong></p></div>
<?php
}

// Get Gallery2 Option Settings
	$g2_option = get_settings('g2_options');

if ( $g2_option['g2_validated'] == "Yes" ) {
	?>
	<div class="wrap">
	<h2><?php _e('Blog Image Options', 'wpg2') ?></h2>
	<form name="g2imageoptions" method="post" action="admin.php?page=wpg2/g2admin.php&noheader=true">
		<input type="hidden" name="action" value="update" />
		<fieldset class="options">
		<legend><?php _e('WPG2ID and WPG2 Tag Options', 'wpg2') ?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr>
		<th valign="top" scope="row"><?php _e('WPG2ID/WPG2 Tag Thumbnail Maximum Size:', 'wpg2') ?> </th>
		<td>
		<input name="g2_options[g2_postimgsize]" id="g2_postimgsize" value="<?php echo $g2_option['g2_postimgsize']; ?>" size="10" / ><br />
		<?php _e('Enter the default size of the thumbnail to be displayed for a WPG2ID/WPG2 tag.', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('WPG2ID/WPG2 Tag Item Frame:', 'wpg2') ?> </th>
		<td>
		<select name="g2_options[g2_postimageFrame]">
		 <option label="<?php _e('None', 'wpg2') ?>" value="none"><?php _e('None', 'wpg2') ?></option>
		 <option label="<?php _e('Solid', 'wpg2') ?>" value="solid"<?php if ('solid' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Solid', 'wpg2') ?></option>
		 <option label="<?php _e('Bamboo', 'wpg2') ?>" value="bamboo"<?php if ('bamboo' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Bamboo', 'wpg2') ?></option>
		 <option label="<?php _e('Book', 'wpg2') ?>" value="book"<?php if ('book' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Book', 'wpg2') ?></option>
		 <option label="<?php _e('Branded Wood', 'wpg2') ?>" value="brand"<?php if ('brand' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Branded Wood', 'wpg2') ?></option>
		 <option label="<?php _e('Dots', 'wpg2') ?>" value="dots"<?php if ('dots' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Dots', 'wpg2') ?></option>
		 <option label="<?php _e('Gold', 'wpg2') ?>" value="gold"<?php if ('gold' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Gold', 'wpg2') ?></option>
		 <option label="<?php _e('Gold 2', 'wpg2') ?>" value="gold2"<?php if ('gold2' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Gold 2', 'wpg2') ?></option>
		 <option label="<?php _e('Spiral Notebook', 'wpg2') ?>" value="notebook"<?php if ('notebook' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Spiral Notebook', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroid', 'wpg2') ?>" value="polaroid"<?php if ('polaroid' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Polaroid', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroids', 'wpg2') ?>" value="polaroids"<?php if ('polaroids' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Polaroids', 'wpg2') ?></option>
		 <option label="<?php _e('Shadow', 'wpg2') ?>" value="shadow"<?php if ('shadow' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Shadow', 'wpg2') ?></option>
		 <option label="<?php _e('Shells', 'wpg2') ?>" value="shell"<?php if ('shell' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Shells', 'wpg2') ?></option>
		 <option label="<?php _e('Slide', 'wpg2') ?>" value="slide"<?php if ('slide' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Slide', 'wpg2') ?></option>
		 <option label="<?php _e('Wood', 'wpg2') ?>" value="wood"<?php if ('wood' == $g2_option['g2_postimageFrame']){echo " selected";}?>><?php _e('Wood', 'wpg2') ?></option>
		 </select>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('WPG2ID/WPG2 Tag Album Frame:', 'wpg2') ?> </th>
		<td>
		<select name="g2_options[g2_postalbumFrame]">
		 <option label="<?php _e('None', 'wpg2') ?>" value="none"><?php _e('None', 'wpg2') ?></option>
		 <option label="<?php _e('Solid', 'wpg2') ?>" value="solid"<?php if ('solid' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Solid', 'wpg2') ?></option>
		 <option label="<?php _e('Bamboo', 'wpg2') ?>" value="bamboo"<?php if ('bamboo' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Bamboo', 'wpg2') ?></option>
		 <option label="<?php _e('Book', 'wpg2') ?>" value="book"<?php if ('book' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Book', 'wpg2') ?></option>
		 <option label="<?php _e('Branded Wood', 'wpg2') ?>" value="brand"<?php if ('brand' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Branded Wood', 'wpg2') ?></option>
		 <option label="<?php _e('Dots', 'wpg2') ?>" value="dots"<?php if ('dots' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Dots', 'wpg2') ?></option>
		 <option label="<?php _e('Gold', 'wpg2') ?>" value="gold"<?php if ('gold' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Gold', 'wpg2') ?></option>
		 <option label="<?php _e('Gold 2', 'wpg2') ?>" value="gold2"<?php if ('gold2' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Gold 2', 'wpg2') ?></option>
		 <option label="<?php _e('Spiral Notebook', 'wpg2') ?>" value="notebook"<?php if ('notebook' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Spiral Notebook', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroid', 'wpg2') ?>" value="polaroid"<?php if ('polaroid' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Polaroid', 'wpg2') ?></option>
		 <option label="<?php _e('Polaroids', 'wpg2') ?>" value="polaroids"<?php if ('polaroids' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Polaroids', 'wpg2') ?></option>
		 <option label="<?php _e('Shadow', 'wpg2') ?>" value="shadow"<?php if ('shadow' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Shadow', 'wpg2') ?></option>
		 <option label="<?php _e('Shells', 'wpg2') ?>" value="shell"<?php if ('shell' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Shells', 'wpg2') ?></option>
		 <option label="<?php _e('Slide', 'wpg2') ?>" value="slide"<?php if ('slide' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Slide', 'wpg2') ?></option>
		 <option label="<?php _e('Wood', 'wpg2') ?>" value="wood"<?php if ('wood' == $g2_option['g2_postalbumFrame']){echo " selected";}?>><?php _e('Wood', 'wpg2') ?></option>
		 </select>
		</td>
		</tr>
		<th valign="top" scope="row"><?php _e('WPG2ID/WPG2 Tag Image Details:', 'wpg2') ?> </th>
		<td>
		<?php _e('Choose the any additional information to be displayed.', 'wpg2') ?><br />
		<input type="hidden" name="g2_options[g2_postblockshow][0]" value="">
		<table><tr><td colspan=2>
		<input name="g2_options[g2_postblockshow][]" type="checkbox" id="g2_postblockshow1" value="title" <?php if (in_array('title',$g2_option['g2_postblockshow'])){echo "checked ";}?>/> <?php _e('Title', 'wpg2') ?>
		</td><td>
		<input name="g2_options[g2_postblockshow][]" type="checkbox" id="g2_postblockshow3" value="fullSize" <?php if (in_array('fullSize',$g2_option['g2_postblockshow'])){echo "checked ";}?>/> <?php _e('FullSize', 'wpg2') ?>
		</td><td></table>
		</td>
		</tr>
		</table>
		<legend><?php _e('G2Image Popup CSS Class Options', 'wpg2') ?></legend>
		<fieldset class="options">
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
		<tr>
		<th valign="top" scope="row"><?php _e('Default Image Class:', 'wpg2') ?></th>
		<td>
		<?php _e('This is the default CSS class applied to WPG2ID/WPG2 tags and HTML thumbnails inserted using the G2Image popup window.', 'wpg2') ?><br />
		<select name="g2_options[g2ic_default_alignment]">
			<option value="none"<?php if ($g2_option['g2ic_default_alignment']=="none" ){ echo " selected";} ?>><?php _e('None', 'wpg2') ?></option>
			<option value="g2image_normal"<?php if ($g2_option['g2ic_default_alignment']=="g2image_normal" ){ echo " selected";} ?>><?php _e('Normal', 'wpg2') ?></option>
			<option value="g2image_float_left"<?php if ($g2_option['g2ic_default_alignment']=="g2image_float_left" ){ echo " selected";} ?>><?php _e('Float Left', 'wpg2') ?></option>
			<option value="g2image_float_right"<?php if ($g2_option['g2ic_default_alignment']=="g2image_float_right" ){ echo " selected";} ?>><?php _e('Float Right', 'wpg2') ?></option>
			<option value="g2image_centered"<?php if ($g2_option['g2ic_default_alignment']=="g2image_centered" ){ echo " selected";} ?>><?php _e('Centered', 'wpg2') ?></option>
			<?php if(isset($g2_option['g2ic_custom_class_1'])&&($g2_option['g2ic_custom_class_1']!='not_used')){ ?>
			<option value="<?php echo $g2_option['g2ic_custom_class_1']; ?>"<?php if ($g2_option['g2ic_default_alignment']==$g2_option['g2ic_custom_class_1']){ echo " selected";} ?>><?php echo $g2_option['g2ic_custom_class_1']; ?></option>
			<?php } ?>
			<?php if(isset($g2_option['g2ic_custom_class_2'])&&($g2_option['g2ic_custom_class_2']!='not_used')){ ?>
			<option value="<?php echo $g2_option['g2ic_custom_class_2']; ?>"<?php if ($g2_option['g2ic_default_alignment']==$g2_option['g2ic_custom_class_2']){ echo " selected";} ?>><?php echo $g2_option['g2ic_custom_class_2']; ?></option>
			<?php } ?>
			<?php if(isset($g2_option['g2ic_custom_class_3'])&&($g2_option['g2ic_custom_class_3']!='not_used')){ ?>
			<option value="<?php echo $g2_option['g2ic_custom_class_3']; ?>"<?php if ($g2_option['g2ic_default_alignment']==$g2_option['g2ic_custom_class_3']){ echo " selected";} ?>><?php echo $g2_option['g2ic_custom_class_3']; ?></option>
			<?php } ?>
			<?php if(isset($g2_option['g2ic_custom_class_4'])&&($g2_option['g2ic_custom_class_4']!='not_used')){ ?>
			<option value="<?php echo $g2_option['g2ic_custom_class_4']; ?>"<?php if ($g2_option['g2ic_default_alignment']==$g2_option['g2ic_custom_class_4']){ echo " selected";} ?>><?php echo $g2_option['g2ic_custom_class_4']; ?></option>
			<?php } ?>
		</select> <br />
		<?php _e('The g2image classes must be implemented in your style.css for the alignment classes to be effective.', 'wpg2') ?><br />
		<?php _e('Custom classes will be available as options after entering them below and hitting the "Update Options" button.', 'wpg2') ?><br />
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Default Class Mode', 'wpg2') ?> </th>
		<td>
		<?php _e('This setting only applies to images inserted as &lt;img&gt; tags.  WPG2ID/WPG2 tags will always be wrapped with a div tag.', 'wpg2') ?><br />
		<input name="g2_options[g2ic_class_mode]" type="radio" id="g2ic_class_mode_img" value="img" <?php if(!isset($g2_option['g2ic_class_mode'])){echo "checked ";} elseif ($g2_option['g2ic_class_mode']=="img"){echo "checked ";}?> /><?php _e('Class in the img tag - &lt img class=... /&gt (Recommended)', 'wpg2') ?><br />
		<input name="g2_options[g2ic_class_mode]" type="radio" id="g2ic_class_mode_div" value="div" <?php if ($g2_option['g2ic_class_mode']=="div"){echo "checked ";}?> /><?php _e('Class in a div tag wrapper - &lt div class=...&gt&lt img ... /&gt&lt /div&gt', 'wpg2') ?>
		</td>
		</tr>
		<tr>
		<th valign="top" scope="row"><?php _e('Custom Classes:', 'wpg2') ?> </th>
		<td>
		<?php _e('Custom Classes (a valid class in your CSS or "not_used")', 'wpg2') ?>
		<table><tr><td colspan=2>
		<?php _e('1. ', 'wpg2') ?><input name="g2_options[g2ic_custom_class_1]" id="g2ic_custom_class_1" value="<?php if(isset($g2_option['g2ic_custom_class_1'])){echo $g2_option['g2ic_custom_class_1'];} else{echo "not_used";} ?>" size="30" / ><br />
		</td><td>
		<?php _e('3. ', 'wpg2') ?><input name="g2_options[g2ic_custom_class_3]" id="g2ic_custom_class_3" value="<?php if(isset($g2_option['g2ic_custom_class_3'])){echo $g2_option['g2ic_custom_class_3'];} else{echo "not_used";} ?>" size="30" / ><br />
		</td></tr>
		<tr><td colspan=2>
		<?php _e('2. ', 'wpg2') ?><input name="g2_options[g2ic_custom_class_2]" id="g2ic_custom_class_2" value="<?php if(isset($g2_option['g2ic_custom_class_2'])){echo $g2_option['g2ic_custom_class_2'];} else{echo "not_used";} ?>" size="30" / ><br />
		</td><td>
		<?php _e('4. ', 'wpg2') ?><input name="g2_options[g2ic_custom_class_4]" id="g2ic_custom_class_4" value="<?php if(isset($g2_option['g2ic_custom_class_4'])){echo $g2_option['g2ic_custom_class_4'];} else{echo "not_used";} ?>" size="30" / ><br />
		</td></tr></table>
		</td></tr></table>
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