<?php
/*
Plugin Name: LJUsers
Plugin URI: http://jenyay.net/Soft/WPLjusers
Description: Insert Livejournal users from editor
Version: 1.2.0
Author: Jenyay
Author URI: http://jenyay.net
*/

/*  Copyright 2008  Jenyay  (email : jenyay.ilin@gmail.com)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class ljusers
{
	// ***** livejournal.com
	// Links to default images for livejournal's user
	var $default_ljuser;
	
	// Links to default images for livejournal's community
	var $default_ljcomm;

	// ***** liveinternet.ru
	var $default_liru_man;
	var $default_liru_girl;
	
	function ljusers()
	{
		// livejournal
		$this->default_ljuser = 'http://stat.livejournal.com/img/userinfo.gif';
		$this->default_ljcomm = 'http://stat.livejournal.com/img/community.gif';

		add_option('ljusers_userinfo', $this->default_ljuser);
		add_option('ljusers_community', $this->default_ljcomm);

		// liveinternet.ru
		$this->default_liru_man = 'http://i.li.ru/4Ek/i/all/pen/m2.gif';
		$this->default_liru_girl = 'http://i.li.ru/4Ek/i/all/pen/w2.gif';

		add_option('ljusers_liru_man', $this->default_liru_man);
		add_option('ljusers_liru_girl', $this->default_liru_girl);

		
		load_plugin_textdomain('ljusers', 'wp-content/plugins/ljusers/languages/');
		
		if (function_exists ('add_shortcode') )
		{
			// livejournal
			add_shortcode('ljuser', array (&$this, 'user_shortcode') );
			add_shortcode('ljcomm', array (&$this, 'community_shortcode') );

			// liveinternet
			add_shortcode('liruman', array (&$this, 'liru_man_shortcode') );
			add_shortcode('lirugirl', array (&$this, 'liru_girl_shortcode') );

		
			add_filter( 'mce_buttons_3', array(&$this, 'mce_buttons') );
			add_filter( 'mce_external_plugins', array(&$this, 'mce_external_plugins') );	
			
			add_action('admin_menu',  array (&$this, 'admin') );
		}
	}
	
	function admin ()
	{
		if ( function_exists('add_options_page') ) 
		{
			add_options_page( 'LJusers Options', 'LJUsers', 8, basename(__FILE__), array (&$this, 'admin_form') );
		}
	}
	
	function admin_form()
	{
		// livejournal
		$userinfo_url = get_option('ljusers_userinfo');
		$community_url = get_option('ljusers_community');

		// liveinternet
		$liru_man_img = get_option ('ljusers_liru_man');
		$liru_girl_img = get_option ('ljusers_liru_girl');
		
		if ( isset($_POST['submit']) ) 
		{	
		   if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			  die ( _e('Hacker?', 'ljusers') );
			
			check_admin_referer('ljusers_form');

			// ***** livejournal
			$userinfo_url = attribute_escape ($_POST['userinfo_url'] );
			$community_url = attribute_escape ($_POST['community_url'] );
			
			update_option('ljusers_userinfo', $userinfo_url);
			update_option('ljusers_community', $community_url);


			// ***** liveinternet
			$liru_man_img = attribute_escape ($_POST['liru_man_img'] );
			update_option('ljusers_liru_man', $liru_man_img);

			$liru_girl_img = attribute_escape ($_POST['liru_girl_img'] );
			update_option('ljusers_liru_girl', $liru_girl_img);
		}
		?>
		<div class='wrap'>
			<h2><?php _e('LJUsers Settings', 'ljusers'); ?></h2>
			
			<form name="ljusers" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=ljusers.php&amp;updated=true">
			
				<!-- Name ljusers_form uses in check_admin_referer -->
				<?php wp_nonce_field('ljusers_form'); ?>
				
				<table class="form-table">

					<!-- livejournal -->
					<tr valign="top">
						<th scope="row"><?php _e('Userinfo image url:', 'ljusers'); ?></th>
						
						<td>
							<input type="text" name="userinfo_url" size="80" value="<?php echo $userinfo_url; ?>" /> <img src="<?php echo $userinfo_url; ?>"> <br/>
							<?php _e('Default value: ', 'ljusers'); echo "<b>$this->default_ljuser</b>"?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Community image url:', 'ljusers'); ?></th>
						
						<td>
							<input type="text" name="community_url" size="80" value="<?php echo $community_url; ?>" /> <img src="<?php echo $community_url; ?>"><br/>
							<?php _e('Default value: ', 'ljusers'); echo "<b>$this->default_ljcomm</b>"?>
						</td>
					</tr>

					<!-- liveinternet -->
					<tr valign="top">
						<th scope="row"><?php _e('Liveinternet (man) image url:', 'ljusers'); ?></th>
						
						<td>
							<input type="text" name="liru_man_img" size="80" value="<?php echo $liru_man_img; ?>" /> <img src="<?php echo $liru_man_img; ?>"> <br/>
							<?php _e('Default value: ', 'ljusers'); echo "<b>$this->default_liru_man</b>"?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e('Liveinternet (girl) image url:', 'ljusers'); ?></th>
						
						<td>
							<input type="text" name="liru_girl_img" size="80" value="<?php echo $liru_girl_img; ?>" /> <img src="<?php echo $liru_girl_img; ?>"> <br/>
							<?php _e('Default value: ', 'ljusers'); echo "<b>$this->default_liru_girl</b>"?>
						</td>
					</tr>
				</table>
				
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="ljusers_userinfo,ljusers_community" />
		
				<p class="submit">
				<input type="submit" name="submit" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<?
	}
	
	function user_shortcode ($atts, $content = null)
	{
		$userinfo_url = get_option ('ljusers_userinfo');
		
		if ($content == null)
		{
			return "";
		}
		
		extract( shortcode_atts ( array('name' => null), $atts ) );

		if ($name == null)
		{
			$name = $content;
		}
		
		return "<span lj:user='$content' style='white-space: nowrap; display: inline !important;'><a href='http://$content.livejournal.com/profile'><img src='$userinfo_url' alt='[info]' width='17' height='17' style='vertical-align: bottom; border: 0; padding-right: 1px;vertical-align:middle; margin-left: 0; margin-top: 0; margin-right: 0; margin-bottom: 0;' /></a><a href='http://$content.livejournal.com/'><b>$name</b></a></span>";
	}

	// Shortcodes for liveinternet.ru (man)
	function liru_man_shortcode ($atts, $content = null)
	{
		$image_url = get_option ('ljusers_liru_man');
		
		if ($content == null)
		{
			return "";
		}
		
		extract( shortcode_atts ( array('name' => null), $atts ) );

		if ($name == null)
		{
			$name = $content;
		}

		return "<a href='http://www.liveinternet.ru/users/$content/'><img style='vertical-align: bottom; border: 0; padding-right: 1px;vertical-align:middle; margin-left: 0; margin-top: 0; margin-right: 0; margin-bottom: 0;' src='$image_url' width='20' height='16' border='0' /></a><a href='http://www.liveinternet.ru/users/$content/profile/' target='_blank'><b>$name</b></a>";
	}

	// Shortcodes for liveinternet.ru (girl)
	function liru_girl_shortcode ($atts, $content = null)
	{
		$image_url = get_option ('ljusers_liru_girl');
		
		if ($content == null)
		{
			return "";
		}
		
		extract( shortcode_atts ( array('name' => null), $atts ) );

		if ($name == null)
		{
			$name = $content;
		}

		return "<a href='http://www.liveinternet.ru/users/$content/'><img style='vertical-align: bottom; border: 0; padding-right: 1px;vertical-align:middle; margin-left: 0; margin-top: 0; margin-right: 0; margin-bottom: 0;' src='$image_url' width='20' height='16' border='0' /></a><a href='http://www.liveinternet.ru/users/$content/profile/' target='_blank'><b>$name</b></a>";
	}


	function community_shortcode ($atts, $content = null)
	{
		$community_url = get_option ('ljusers_community');
		
		if ($content == null)
		{
			return "";
		}
		
		extract( shortcode_atts ( array('name' => null), $atts ) );

		if ($name == null)
		{
			$name = $content;
		}
			
		return "<b><span lj:user='$content' style='white-space: nowrap;'><a href='http://community.livejournal.com/$content/profile'><img src='$community_url' alt='[info]' width='17' height='17' style='vertical-align: middle; border: 0; padding-right: 1px; margin-left: 0; margin-top: 0; margin-right: 0; margin-bottom: 0;' /></a><a href='http://community.livejournal.com/$content'><b>$name</b></a></span></b>";
	}
	
	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function mce_external_plugins($plugin_array) 
	{
		if (function_exists (plugins_url) )
        	$plugin_array['ljusers'] = plugins_url ('/ljusers/js/editor_plugin.js');
    	else
        	$plugin_array['ljusers'] = get_option('siteurl') . '/wp-content/plugins/ljusers/js/editor_plugin.js';

   		return $plugin_array;
	}
	
	function mce_buttons($buttons)
	{
		// Эти же имена должны быть в editor_plugin.js
		array_push($buttons, "ljusers", "ljcomm", "liruman", "lirugirl");
  		return $buttons;
	}
}

$ljusers = new ljusers();

?>
