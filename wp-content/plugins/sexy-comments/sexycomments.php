<?php
/*
Plugin Name: Sexy Comments
Plugin URI: http://borkweb.com/plugins/
Description: Sexify your comments with avatars and forum-like formatting.
Version: 1.4.6
Author: Matthew Batchelder
Author URI: http://borkweb.com
*/
?>
<?php
/*  Copyright 2007  Matthew Batchelder  (email : borkweb@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
?>
<?php

class sexycomments {
	function version() { return '1.4.6'; }

	function add_menu() 
	{
		add_submenu_page('plugins.php','Comment Settings', 'SexyComments', 8, __FILE__, array('sexycomments','optionspage'));
	}

	function css() {
		$sexycomments_hide_credits = get_settings('sexycomments_hide_credits');
?>
<style type="text/css">
<!--
/*

Styles necessary to support various sexycomments features.
sexycomments is a a WordPress plugin that sexifies your comments 
with avatars and forum-like formatting.
http://borkweb.com/plugins

Author: Matthew Batchelder
Author URI: http://borkweb.com/

This CSS, such as it is, is released under the GPL:
http://www.opensource.org/licenses/gpl-license.php

*/
#sexycomments ol {
	font-size:1em;
	font-weight:normal;
	margin:0;
	padding:0;
}
#sexycomments ol li {
	background: #eee;
	border-top: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	font-size: 0.9em;
	list-style:none;
	margin:0 0 3px 0;
	position:relative;
}
#sexycomments ol li.odd {background: #f4f4f4;}
#sexycomments ol li.author{
	background: #eee;
	border-top: 1px solid #666;
	border-bottom: 1px solid #666;
}
#sexycomments ol li.author .bio{background:#ddd;}
#sexycomments ol li a.avatar img{
	border: 3px double #ccc;
	float:left;
	margin: 0 3px 3px 0;
}
#sexycomments ol li blockquote{
	border-top:5px solid #ccc;
	border-left:1px solid #ccc;
	border-right:1px solid #ccc;
	border-bottom:1px solid #ccc;
	padding: 3px;
	margin: 3px 6px;
}	
#sexycomments ol .bio{background:#e8e8e8;overflow:auto;width:150px;}
#sexycomments ol .comment{padding-right:2em;position:relative;}
#sexycomments ol .comment-count{
	color: #ccc;
	font-size: 1.8em;
	font-style: italic;
	position:absolute;
	right: 5px;
	top: 5px;
}
#sexycomments ol li .message{color:#aaa;font-size: 0.9em;font-style:italic;}	
#sexycomments ol td{padding:3px;}
#sexycomments .commentmetadata {
	color:#333;
	font-size:0.8em;
	font-family: Times New Roman, Georgia, Serif;
	margin:0;
	padding-bottom:5px;
}
#sexycomments .sexycomments-footer{
	<?php
		if( $sexycomments_hide_credits )
			echo 'display:none;';
	?>
	color:#ccc;
	font-size:0.6em;
	text-align:center;
}
-->
</style>
<?php
	}//end css
	
	function install() {
		$newversion = false;
		$version = get_settings('sexycomments_version');
		if($version != sexycomments::version()) {
			$newversion = true;
			if(!$version) $newinstall = true;
		}//end if
	
		if($newversion)
		{
			$sexycomments_display_avatar = get_settings('sexycomments_display_avatar');
			$sexycomments_avatar_site = get_settings('sexycomments_avatar_site');
			$sexycomments_default_avatar = get_settings('sexycomments_default_avatar');
			$sexycomments_default_trackback = get_settings('sexycomments_default_trackback');
			$sexycomments_comment_0 = get_settings('sexycomments_comment_0');
			$sexycomments_comment_1 = get_settings('sexycomments_comment_1');
			$sexycomments_comment_more = get_settings('sexycomments_comment_more');
			$sexycomments_disable_css = get_settings('sexycomments_disable_css');
			$sexycomments_rating = get_settings('sexycomments_rating');
			$sexycomments_size = get_settings('sexycomments_size');
			$sexycomments_theme = get_settings('sexycomments_theme');
			$sexycomments_include_jquery = get_settings('sexycomments_include_jquery');
			$sexycomments_jquery_form = get_settings('sexycomments_jquery_form');
			$sexycomments_preview = get_settings('sexycomments_preview');
			$sexycomments_replyto = get_settings('sexycomments_replyto');
	
			if($sexycomments_display_avatar=='')	update_option('sexycomments_display_avatar', 1);		
			if($sexycomments_avatar_site=='') update_option('sexycomments_avatar_site', 'mybloglog');
			if($sexycomments_default_avatar=='') update_option('sexycomments_default_avatar', get_bloginfo('wpurl').'/wp-content/plugins/sexycomments/images/default.gif');
			if($sexycomments_trackback=='') update_option('sexycomments_trackback', get_bloginfo('wpurl').'/wp-content/plugins/sexycomments/images/trackback.gif');
			if($sexycomments_comment_0=='') update_option('sexycomments_comment_0', 'No Responses');
			if($sexycomments_comment_1=='') update_option('sexycomments_comment_1', 'One Response');
			if($sexycomments_comment_more=='') update_option('sexycomments_comment_more', '% Responses');
			if($sexycomments_disable_css=='') update_option('sexycomments_disable_css', 0);
			if($sexycomments_rating=='') update_option('sexycomments_rating', 'PG');
			if($sexycomments_size=='') update_option('sexycomments_size', 40);
			if($$sexycomments_theme=='') update_option('sexycomments_theme','default.php');
			if($$sexycomments_include_jquery=='') update_option('sexycomments_include_jquery',0);
			if($$sexycomments_jquery_form=='') update_option('sexycomments_jquery_form',0);
			if($$sexycomments_preview=='') update_option('sexycomments_preview',0);
			if($$sexycomments_replyto=='') update_option('sexycomments_replyto',0);
			
	
			update_option('sexycomments_version', sexycomments::version());
			if($newinstall) {
	?>
		<div id="message" class="updated fade">
			<p><?=_e("The <strong>Sexy Comments</strong> plugin is now initialized.")?></p>
		</div>
	<?
			} else {
	?>
		<div id="message" class="updated fade">
			<p><?=_e("The <strong>Sexy Comments</strong> plugin settings have been updated to the new version.")?></p>
		</div>
	<?
			}
		}//end if
	}//end install
	
	function is_outdated() {
		/*$rss = @fetch_rss('http://borkweb.com/code/sexycomment.version.xml');

		if(isset($rss->items) && count($rss->items)>0) {
			if($rss->items[0]['version']>sexycomments::version())	{
				return true;
			}
		}*/
		return false;
	}//end is_outdated
	
	function optionspage() {
		//  output settings/configuration form
		sexycomments::install();
	
		//  apply new settings if form submitted
		if(isset($_POST['Options'])) {
			$sexycomments_display_avatar = $_POST['sexycomments_display_avatar'];
			update_option('sexycomments_display_avatar', $sexycomments_display_avatar);
			
			$sexycomments_avatar_site = $_POST['sexycomments_avatar_site'];
			update_option('sexycomments_avatar_site', $sexycomments_avatar_site);
	
			$sexycomments_default_avatar = $_POST['sexycomments_default_avatar'];
			update_option('sexycomments_default_avatar', $sexycomments_default_avatar);
	
			$sexycomments_trackback = $_POST['sexycomments_trackback'];
			update_option('sexycomments_trackback', $sexycomments_trackback);
	
			$sexycomments_comment_0 = $_POST['sexycomments_comment_0'];
			update_option('sexycomments_comment_0', $sexycomments_comment_0);
	
			$sexycomments_comment_1 = $_POST['sexycomments_comment_1'];
			update_option('sexycomments_comment_1', $sexycomments_comment_1);
	
			$sexycomments_comment_more = $_POST['sexycomments_comment_more'];
			update_option('sexycomments_comment_more', $sexycomments_comment_more);
	
			$sexycomments_disable_css = $_POST['sexycomments_disable_css'];
			update_option('sexycomments_disable_css', $sexycomments_disable_css);
	
			$sexycomments_rating = $_POST['sexycomments_rating'];
			update_option('sexycomments_rating', $sexycomments_rating);
	
			$sexycomments_size = $_POST['sexycomments_size'];
			update_option('sexycomments_size', $sexycomments_size);
	
			$sexycomments_hide_credits = $_POST['sexycomments_hide_credits'];
			update_option('sexycomments_hide_credits', $sexycomments_hide_credits);
			
			$sexycomments_theme = $_POST['sexycomments_theme'];
			update_option('sexycomments_theme', $sexycomments_theme);
	
			$sexycomments_include_jquery = $_POST['sexycomments_include_jquery'];
			update_option('sexycomments_include_jquery', $sexycomments_include_jquery);

			$sexycomments_jquery_form = $_POST['sexycomments_jquery_form'];
			update_option('sexycomments_jquery_form', $sexycomments_jquery_form);

			$sexycomments_preview = $_POST['sexycomments_preview'];
			update_option('sexycomments_preview', $sexycomments_preview);
			
			$sexycomments_replyto = $_POST['sexycomments_replyto'];
			update_option('sexycomments_replyto', $sexycomments_replyto);

			echo '<div class="updated"><p><strong>' . __('Options updated.', 'sexycomments') . '</strong></p></div>';
		}
		$sexycomments_display_avatar = get_settings('sexycomments_display_avatar');
		$sexycomments_avatar_site = get_settings('sexycomments_avatar_site');
		$sexycomments_default_avatar = get_settings('sexycomments_default_avatar');
		$sexycomments_trackback = get_settings('sexycomments_trackback');
		$sexycomments_comment_0 = get_settings('sexycomments_comment_0');
		$sexycomments_comment_1 = get_settings('sexycomments_comment_1');
		$sexycomments_comment_more = get_settings('sexycomments_comment_more');
		$sexycomments_disable_css = get_settings('sexycomments_disable_css');
		$sexycomments_rating = get_settings('sexycomments_rating');
		$sexycomments_size = get_settings('sexycomments_size');
		$sexycomments_hide_credits = get_settings('sexycomments_hide_credits');
		$sexycomments_theme = get_settings('sexycomments_theme');
		$sexycomments_include_jquery = get_settings('sexycomments_include_jquery');
		$sexycomments_jquery_form = get_settings('sexycomments_jquery_form');
		$sexycomments_preview = get_settings('sexycomments_preview');
		$sexycomments_replyto = get_settings('sexycomments_replyto');
?>
<div class="wrap">
<style>
	small em{color:#999;}
</style>
<h2><?php _e('Settings') ?></h2>
<?php if(sexycomments::is_outdated()) { ?>
	<span style="color:red;font-weight:bold;">Your version of the Sexy Comments plugin is outdated.  Get the <a href="http://borkweb.com/plugins">new version here</a>.</span><br/>
<?php	} ?>
<?php
echo bloginfo('wpurl');
?>
	<form method="post">
		<fieldset name="sexycomments_general" class="options">
		<legend><?php _e('General Options', 'sexycomments') ?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Comment Display Theme:', 'sexycomments') ?></th>
				<td>
				<select name="sexycomments_theme" id="sexycomments_theme">
					<?php
						if ($handle = opendir(get_template_directory().'/../../plugins/sexy-comments/templates')) {
								while (false !== ($file = readdir($handle))) {
									if ($file != "." && $file != "..") {
								?>
										<option value="<?php echo $file; ?>" <?php echo ( $sexycomments_theme==$file ) ? 'selected="1"':''; ?>><?php echo $file; ?></option>
								<?php
									}
								}
						
								closedir($handle);
						}
					?>
				</select>
				<br/>
				<small><em>Your avatar service of choice.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Disable Default CSS:', 'sexycomments') ?></th>
				<td>
					<input type="checkbox" name="sexycomments_disable_css" id="sexycomments_disable_css" value="1" <?php if($sexycomments_disable_css) echo 'checked="checked"'; ?>/><br/>
					If you would like to override the default css, simply integrate <a href="javascript:void(0);" onClick="$('#sexycomments_css_box').toggle();">this CSS</a> code into your theme!
					<div id="sexycomments_css_box" style="display:none;"><textarea cols="60" rows="8" style="font-size:10px;"><?php sexycomments::css(); ?></textarea></div>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('0 Comments Message:', 'sexycomments') ?></th>
				<td><input type="text" name="sexycomments_comment_0" id="sexycomments_comment_0" value="<?php echo $sexycomments_comment_0; ?>"/>
					<br/>
					<small><em>Message to display if no comments have been made.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('1 Comment Message:', 'sexycomments') ?></th>
				<td><input type="text" name="sexycomments_comment_1" id="sexycomments_comment_1" value="<?php echo $sexycomments_comment_1; ?>"/>
					<br/>
					<small><em>Message to display if 1 comment has been made.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('2+ Comments Message:', 'sexycomments') ?></th>
				<td><input type="text" name="sexycomments_comment_more" id="sexycomments_comment_more" value="<?php echo $sexycomments_comment_more; ?>"/>
					<br/>
					<small><em>Message to display if more than 1 comment has been made. (use % as the placeholder for the number of comments)</em></small>
				</td>
			</tr>
		</table>
		<div class="submit"><input type="submit" name="Options" value="<?php _e('Update Options', 'sexycomments') ?> &raquo;" /></div>
		</fieldset>

		<fieldset name="sexycomments_avatars" class="options">
		<legend><?php _e('Avatar Settings', 'sexycomments') ?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Display Avatars?', 'sexycomments') ?></th>
				<td>
					<input type="checkbox" name="sexycomments_display_avatar" id="sexycomments_display_avatar" value="1" <?php echo ( $sexycomments_display_avatar ) ? 'checked="1"':''; ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Avatar Service:', 'sexycomments') ?></th>
				<td>
				<select name="sexycomments_avatar_site" id="sexycomments_avatar_site">
					<option value="mybloglog" <?php echo ( $sexycomments_avatar_site=='mybloglog' ) ? 'selected="1"':''; ?>>MyBlogLog</option>
					<option value="gravatar" <?php echo ( $sexycomments_avatar_site=='gravatar' ) ? 'selected="1"':''; ?>>Gravatar</option>
				</select>
				<br/>
				<small><em>Your avatar service of choice.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Riskiest Avatar Rating:', 'sexycomments') ?></th>
				<td>
					<select name="sexycomments_rating" id="sexycomments_rating">
						<option value="G" <?php echo ( $sexycomments_rating=='G' ) ? 'selected="1"':''; ?>>G</option>
						<option value="PG" <?php echo ( $sexycomments_rating=='PG' ) ? 'selected="1"':''; ?>>PG</option>
						<option value="R" <?php echo ( $sexycomments_rating=='R' ) ? 'selected="1"':''; ?>>R</option>
						<option value="X" <?php echo ( $sexycomments_rating=='X' ) ? 'selected="1"':''; ?>>X</option>
					</select>
					<br/>
					<small><em>Gravatar only: Works like movie ratings...what you choose is the 'riskiest' rating you will allow.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Max Avatar Size:', 'sexycomments') ?></th>
				<td>
					<input type="text" name="sexycomments_size" id="sexycomments_size" value="<?php echo $sexycomments_size; ?>"/>
					<br/>
					<small><em>This, of course, is in pixels.</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Default Avatar Image:', 'sexycomments') ?></th>
				<td>
					<input type="text" name="sexycomments_default_avatar" id="sexycomments_default_avatar" value="<?php echo $sexycomments_default_avatar; ?>" size="50"/>
					<br/>
					<small><em>Default: <?php bloginfo('wpurl'); ?>/wp-content/plugins/sexycomments/images/default.gif</em></small>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Trackback/Pingback Image:', 'sexycomments') ?></th>
				<td>
					<input type="text" name="sexycomments_trackback" id="sexycomments_trackback" value="<?php echo $sexycomments_trackback; ?>" size="50"/>
					<br/>
					<small><em>Default: <?php bloginfo('wpurl'); ?>/wp-content/plugins/sexycomments/images/trackback.gif</em></small>
				</td>
			</tr>
		</table>
		<div class="submit"><input type="submit" name="Options" value="<?php _e('Update Options', 'sexycomments') ?> &raquo;" /></div>
		</fieldset>	

		<fieldset name="sexycomments_preview" class="options">
		<legend><?php _e('Ajaxified Comment Preview & Comment Reply-To', 'sexycomments') ?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Enable Comment Preview:', 'sexycomments') ?></th>
				<td>
				<select name="sexycomments_preview" id="sexycomments_preview">
					<option value="0" <?php echo ( $sexycomments_preview==0 ) ? 'selected="1"':''; ?>>No</option>
					<option value="1" <?php echo ( $sexycomments_preview==1 ) ? 'selected="1"':''; ?>>Yes</option>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Enable Comment Reply-To:', 'sexycomments') ?></th>
				<td>
				<select name="sexycomments_replyto" id="sexycomments_replyto">
					<option value="0" <?php echo ( $sexycomments_replyto==0 ) ? 'selected="1"':''; ?>>No</option>
					<option value="1" <?php echo ( $sexycomments_replyto==1 ) ? 'selected="1"':''; ?>>Yes</option>
				</select>
				</td>
			</tr>
			<!--<tr valign="top">
				<th width="33%" scope="row"><?php _e('Insert jQuery:', 'sexycomments') ?></th>
				<td>
					<input type="checkbox" name="sexycomments_include_jquery" id="sexycomments_include_jquery" value="1" <?php if($sexycomments_include_jquery) echo 'checked="checked"'; ?>/><br/>
					<small>Comment Preview requires jQuery.  If your theme already uses jQuery, make sure this box is not checked.  Otherwise, check this box :) <em>If your theme uses the Prototype/Script.aculo.us JS libraries...well...then you can't use the Comment Preview functionality of SexyComments.</em></div>
				</td>
			</tr>
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Insert jQuery Form Extension:', 'sexycomments') ?></th>
				<td>
					<input type="checkbox" name="sexycomments_jquery_form" id="sexycomments_jquery_form" value="1" <?php if($sexycomments_jquery_form) echo 'checked="checked"'; ?>/><br/>
					<small>Like the inclusion of jQuery, this jQuery extension is also needed for comment previews <strong>as well as Reply-Tos</strong>.  Once again, enable this if your theme isn't already including it.</div>
				</td>
			</tr>-->
		</table>
		<div class="submit"><input type="submit" name="Options" value="<?php _e('Update Options', 'sexycomments') ?> &raquo;" /></div>
		</fieldset>

		<fieldset name="sexycomments_credits" class="options">
		<legend><?php _e('Evil, Vile, Credit Hiding', 'sexycomments') ?></legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
			<tr valign="top">
				<th width="33%" scope="row"><?php _e('Hide SexyComment Credits?', 'sexycomments') ?></th>
				<td>
					<input type="checkbox" name="sexycomments_hide_credits" id="sexycomments_hide_credits" value="1" <?php echo ( $sexycomments_hide_credits ) ? 'checked="1"':''; ?>/>
					<br/>
					<small><em>Obviously, I'd prefer to not have the SexyComments credits removed...but it is your layout, so I threw this option in for ya.  This toggles a display:none; in the #sexycomments .sexycomments-footer</em></small>
				</td>
			</tr>
		</table>
		<div class="submit"><input type="submit" name="Options" value="<?php _e('Update Options', 'sexycomments') ?> &raquo;" /></div>
		</fieldset>	
	</form>
</div>

<div class="wrap">
	<h2><?=_e('Documentation')?></h2>
	<h3>Replacing Your Comments with SexyComments</h3>
	<div>
		Locate the template file in your theme that displays comments (typically comments.php).  Remove the comment output loop and replace with:<br/><br/>
		&lt;?php sexycomments::show($comments); ?&gt;
		<br/><br/>
	<h3>Enabling Comment Preview</h3>
	<div>
		Locate the template file(s) in your theme that displays the comment submission form.  Remove that comment form code and replace with:<br/><br/>
		&lt;?php sexycomments::form(); ?&gt;
		<br/><br/>
		<strong>NOTE:</strong> Be sure not to touch the section that generates the form for adding comments!  This plugin does <strong><em>not</em></strong> re-create the comment creation form.
		<br/><br/>
	</div>
	
	Visit <a href="http://borkweb.com/plugins/">BorkWeb Plugins</a> for further version releases of the Sexy Comments plugin.
</div>
<?php
	}//end optionspage
	
	function avatar($email,$url='',$name='')
	{
		$sexycomments_avatar_site = get_settings('sexycomments_avatar_site');
		$sexycomments_size = get_settings('sexycomments_size');
		$sexycomments_rating = get_settings('sexycomments_rating');
		$sexycomments_default_avatar = get_settings('sexycomments_default_avatar');
		$sexycomments_trackback = get_settings('sexycomments_trackback');
	
		$avatar='';
	
		switch ( $sexycomments_avatar_site )
		{
			case 'gravatar':
				$avatar = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($email);
				if($sexycomments_rating && $sexycomments_rating != '') {
					$avatar .= "&amp;rating=".$sexycomments_rating;
				}
				if($sexycomments_size && $sexycomments_size != '') {
					$avatar .="&amp;size=".$sexycomments_size;
				}
				if($sexycomments_default_avatar && $sexycomments_default_avatar != '') {
					$avatar .= "&amp;default=".urlencode($sexycomments_default_avatar);
				}
	
				$link="http://site.gravatar.com";
				break;
			case 'mybloglog':
				if($url != ""  &&  $url != "http://")
					$avatar = "http://pub.mybloglog.com/coiserv.php?href=" . $url . "&amp;n=". $email;
				elseif($safe_email)
					$avatar = "http://pub.mybloglog.com/coiserv.php?href=&amp;n=". $email;
				else
					$avatar = "http://pub.mybloglog.com/coiserv.php?href=mailto:" . $email . "&amp;n=". $email;
				break;
		}
		return '<a href="'.$link.'" class="avatar"><img src="'.$avatar.'" alt="Avatar"/></a>';
	}//end avatar
	
	function header(&$comments)
	{
		$sexycomments_comment_0 = get_settings('sexycomments_comment_0');
		$sexycomments_comment_1 = get_settings('sexycomments_comment_1');
		$sexycomments_comment_more = get_settings('sexycomments_comment_more');
		?>
		<h3><?php comments_number($sexycomments_comment_0, $sexycomments_comment_1, $sexycomments_comment_more );?> to &#8220;<?php the_title(); ?>&#8221;</h3>
		<?php
	}//end header
	
	function jquery(){
		?>
		<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.form.js"></script>
		<script type="text/javascript">
			jQuery.noConflict();
		</script>
		<?php
	}
	
	function loop(&$comments)
	{
		global $post;

		$sexycomments_theme = get_settings('sexycomments_theme');

		if(!file_exists(get_template_directory().'/../../plugins/sexy-comments/templates/'.$sexycomments_theme)) {
		?>
			<ol><li>The SexyComments Theme used to render comments does not exist or it has not yet been initialized.  Please check the <em>Comment Display Theme</em> setting in the SexyComments options.</li></ol>
		<?php
		} else {
		?>
			<ol>
				<?php
				$comment_count=1;
				foreach ($comments as $GLOBALS['comment']) { 			
					sexycomments::render_comment($sexycomments_theme,$comment_count);
					$comment_count++;
				} //end foreach comments
			?>
			</ol>
		<?php
		}//end else
	}//end loop
	
	function render_comment($theme='default',$comment_count=1)
	{
		global $post;
		$sexycomments_display_avatar = get_settings('sexycomments_display_avatar');
		$sexycomments_trackback = get_settings('sexycomments_trackback');
		$sexycomments_replyto = get_settings('sexycomments_replyto');
		$author_highlight = ( $GLOBALS['comment']->user_id == $post->post_author ) ? 'author' : '';
		$alternate = ( $comment_count%2 == 0 )?'even':'odd';
		?>
		<li class="<?php echo $alternate;  ?> <?php echo $author_highlight; ?> <?php comment_type(); ?>" id="comment-<?php comment_ID(); ?>">
			<?php
				if ( $GLOBALS['comment']->comment_type == 'pingback' || $GLOBALS['comment']->comment_type == 'trackback' ) {
					$regular_comment = false;
					
					if ( $sexycomments_display_avatar ) {
						$GLOBALS['comment']->avatar = '<img src="'.$sexycomments_trackback.'" class="avatar" alt="'.$GLOBALS['comment']->comment_type.'"/>';
					}
				} else {
					$regular_comment = true;
					
					if ( $sexycomments_display_avatar ) {
						$GLOBALS['comment']->avatar = sexycomments::avatar($GLOBALS['comment']->comment_author_email,$GLOBALS['comment']->comment_author_url,$GLOBALS['comment']->comment_author_name);
					}
				}//end else
				
				@include(get_template_directory().'/../../plugins/sexy-comments/templates/'.$theme);							
			?>
		</li>
		<?php
	}//end render_comment
	
	function footer()
	{
	?>
		<div class="sexycomments-footer"><a href="http://borkweb.com/plugins">SexyComments</a> by <a href="http://borkweb.com">BorkWeb</a></div>
	<?php
	}//end footer
	
	function none()
	{
	?>
		<p class="nocomments">Be the first to comment on this post!</p>
	<?php
	}//end none
	
	function disabled()
	{
	?>
		<p class="nocomments"></p>
	<?php
	}//end disabled
	
	function form() {
		global $post,$user_ID, $user_url, $user_identity, $user_email;
		
		if ('open' == $post->comment_status) { 
			$sexycomments_preview = get_settings('sexycomments_preview');
			
			if($sexycomments_preview) {
				?>
				<script type="text/javascript">
				if('function' == typeof(jQuery))
				{
					jQuery(document).ready(function(){
						jQuery('#comment-preview').hide();
						jQuery('#comment-preview-header').hide();
						jQuery('#preview-comment-button').click(function(){
							if(jQuery('#commentform textarea').val()=='' || jQuery('#commentform input[@name=email]').val()=='') {
								alert('You must enter both an e-mail and comment body before you preview your comment');
							} else {
								jQuery('#comment-preview li').html('<div style="margin:20px;text-align:center;"><img src="<?php get_bloginfo('wpurl'); ?>/wp-content/plugins/sexycomments/images/loading.gif"/></div>').parent().show();
								jQuery('#comment-preview-header').show();
								jQuery.post('<?php echo get_template_directory(); ?>/../../plugins/sexy-comments/sexycomments.php','ajax=1&'+jQuery('#commentform').formSerialize(),function(text){jQuery('#comment-preview li').html(text);});
							}
							return false;
						});
					});
				}//end if
				</script>
				<?php
			}//end if
		
		?>
			<h3 id="respond">Leave a Reply</h3>
			<?php
			if (get_option('comment_registration') && !$user_ID ) { 
			?>
				<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
			<?php
			} else { 
			?>
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
					<?php do_action('comment_form', $post->ID); ?>
					<?php
					if ( $user_ID ) { 
					?>
						<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p>
					<?php
					} else { 
					?>
						<p>
							<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
							<label for="author"><small>Name
							<?php
							if ($req) {
								_e('(required)');
							}
							?>
							</small></label>
						</p>
						<p>
							<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
							<label for="email"><small>Mail (will not be published) 
							<?php
							if ($req) {
								_e('(required)');
							} 
							?>
							</small></label>
						</p>
						<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" /><label for="url"><small>Website</small></label></p>
					<?php
					} ?>
					<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>
					<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
					<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
					<?php
					if($sexycomments_preview) {
						?>
						<input type="button" id="preview-comment-button" value="Preview"/> 
						<?php
					}//end if		
					?>
					<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" /></p>
					<?php do_action('comment_form', $post->ID); ?>
				</form>
				<?php
				if($sexycomments_preview) {
					?>
					<div id="sexycomments">
					<strong id="comment-preview-header">Comment Preview:</strong>
					<ol id="comment-preview">
						<li class="odd"></li>
					</ol>
					</div>
					<?php
				}//end if				 
			}
		}
	}//end form

	function preview() {
		global $current_user, $user_ID, $user_url, $user_identity, $user_email;
		
		$sexycomments_display_avatar = get_settings('sexycomments_display_avatar');

		$author	= ($_POST['author']) ? trim($_POST['author']) : 'Anonymous';

		$url	= trim($_POST['url']);
		$text	= trim($_POST['comment']);
		$email = trim($_POST['email']);

		require_once(get_template_directory().'/../../../wp-includes/pluggable.php');
		get_currentuserinfo();
		
		if ( $user_ID ) {
			$author	= addslashes($user_identity);
			$url	= addslashes($user_url);
			$email  = addslashes($user_email);
		}

		$regular_comment=true;
		
		$GLOBALS['comment']->comment_date = date('Y-m-d H:i:s');
		$GLOBALS['comment']->comment_content = stripslashes($text);
		$GLOBALS['comment']->comment_author = stripslashes($author);
		$GLOBALS['comment']->comment_author_email = stripslashes($email);

		if ( $url && 'http://' != $url ) {
			$GLOBALS['comment']->comment_author_url = stripslashes($url);
			$GLOBALS['comment']->comment_auther = stripslashes($author);
		}
		
		$author_highlight = ( $GLOBALS['comment']->user_id == $post->post_author ) ? 'author' : '';
		
		if ( $sexycomments_display_avatar ) {
			$GLOBALS['comment']->avatar = sexycomments::avatar($GLOBALS['comment']->comment_author_email,$GLOBALS['comment']->comment_author_url,$GLOBALS['comment']->comment_author_name);
		}
		$theme = get_option( 'sexycomments_theme' );

		include('templates/'.$theme);
	}//end preview

	function show(&$comments) {
		$sexycomments_disable_css = get_settings('sexycomments_disable_css');
		$sexycomments_replyto = get_settings('sexycomments_replyto');	

		if($sexycomments_replyto) {
			?>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.comment-replyto').click(function(){
					var text = jQuery(this).parent().children('.comment_body_container').html();
					var current = jQuery('textarea#comment').val();
					if(current!='') current = current + '\n\n';
					jQuery('textarea#comment').val(current+'<blockquote>'+text+'</blockquote>');
					return false;
				});
			});
			</script>
			<?php
		}//end if
		?>
		<div id="sexycomments">
			<a name="comments"></a>
			<?php
			if ($comments) { 
				if( !$sexycomments_disable_css ) {
					sexycomments::css();
				}//end if
		
				sexycomments::header($comments);
				sexycomments::loop($comments);
				sexycomments::footer();
			}
			else 
			{ // this is displayed if there are no comments so far
				if ( $post->comment_status == 'open' ) {
					sexycomments::none();
				} else { 
					sexycomments::disabled();
				} //end if
			}
			?>
		</div>
		<?php
	}//end show
}//end class sexycomments

function sexycomments_print(&$comments) {
	//deprecated
	sexycomments::show($comments);
}

//
// register WordPress hooks
//
if ( function_exists('add_action') ) {
	add_action('wp_head',array('sexycomments','jquery'));
	add_action('admin_menu', array('sexycomments','add_menu'));
	add_action('activate_sexycomments/sexycomments.php', array('sexycomments','install'));
}
// end register WordPress hooks

if ($_POST['ajax'] && $_POST['comment'] ) {
	$wp_config = preg_replace('|wp-content.*$|','', __FILE__) . 'wp-config.php';
	require_once($wp_config);
	//get_currentuserinfo();
	sexycomments::preview();
	exit;
}
?>