<?php function admired_wp_head() {
    /* admired_wp_head is called as a result of the add_action('wp_head', 'admired_wp_head') call at the end of this file.
	This is really where almost all of the real work of this theme gets done. Essentially, this function outputs 
	<style> overrides of style.css to give the custom look as defined by the user.
    */
    global $options;
	$options = get_option('admired_theme_options');
	global $admired_themename, $admired_shortname, $version, $admired_settings, $admired_option_group, $admired_option_name;	// need these globals 

	// Google Fonts
	if ( isset ($options['admired_head_font_select'])&&  ($options['admired_content_font_select']) && ($options['admired_head_font_select'] !="web-safe") &&  ($options['admired_content_font_select']) !="web-safe" ) { ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(" ", "+",$options['admired_body_font']); ?>|<?php echo str_replace(" ", "+",$options['admired_head_font']); ?>' rel='stylesheet' type='text/css'>
	<?php } 
	if ( isset ($options['admired_head_font_select'])&& ($options['admired_head_font_select'] =='web-safe') &&  ($options['admired_content_font_select']) !="web-safe" ) { ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(" ", "+",$options['admired_body_font']); ?>' rel='stylesheet' type='text/css'>
	<?php } 
	if ( isset ($options['admired_content_font_select'])&& ($options['admired_content_font_select'] =='web-safe') &&  ($options['admired_head_font_select']) !="web-safe" ) { ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(" ", "+",$options['admired_head_font']); ?>' rel='stylesheet' type='text/css'>
	<?php } ?>
	
<style type="text/css">
<?php
    // depending on what options we've set from the admin panel, we will emit various bits of CSS in the output

	// ************************************************** General Appearance

	// Content Links
	$optColor = ($options['admired_link_color']);
	if ( isset ($options['admired_link_color'])&&  ($options['admired_link_color'] !="#1982d1") ) {
		echo("a { color: $optColor;}\n");
	}

	$optColor = ($options['admired_link_visited_color']);
	if ( isset ($options['admired_link_visited_color'])&&  ($options['admired_link_visited_color'] !="#11598F") ) {
		echo("a:visited{ color: $optColor;}\n");
	}

	$optColor = ($options['admired_link_hover_color']);
	if ( isset ($options['admired_link_hover_color'])&&  ($options['admired_link_hover_color'] !="#1982d1") ) {
		echo("a:hover{ color: $optColor;}\n");
	}

	$optColor = ($options['admired_content_title_link_color']);
	if ( isset ($options['admired_content_title_link_color'])&&  ($options['admired_content_title_link_color'] !="#222222") ) {
		echo(".entry-title, .entry-title a{ color: $optColor;}\n");
	}

	$optColor = ($options['admired_content_title_hover_color']);
	if ( isset ($options['admired_content_title_hover_color'])&&  ($options['admired_content_title_hover_color'] !="#1982d1") ) {
		echo(".entry-title a:hover,.entry-title a:focus,.entry-title a:active{ color: $optColor;}\n");
	}

	// Content bullets
	$img_Bullet = get_template_directory_uri() . '/images/bullets/';      
	$val = ($options['admired_content_bullet_color']);
	if ($val && $val != '' && $val != 'default') {
	if ($val == 'none' || $val == 'circle' || $val == 'disc' || $val == 'square')
	    echo("ul {list-style:$val;}\n");
	else 
	    echo ("ul{list-style:none;list-style-image: url(");
		echo ("$img_Bullet");
		echo ("$val.gif);}\n"); 
    }
	
	// Post calendar
	$optColor = ($options['admired_calendar_border_color']);
	if ( isset ($options['admired_calendar_border_color'])&&  ($options['admired_calendar_border_color'] !="#A0A0A0") ) {
		echo(".calendar{ border: 1px solid $optColor;}\n");
	}

	$optColor = ($options['admired_calendar_month_BG_color']);
	if ( isset ($options['admired_calendar_month_BG_color'])&&  ($options['admired_calendar_month_BG_color'] !="#BABABA") ) {
		echo(".calendar > .month{ background-color: $optColor;}\n");
	}

	$optColor = ($options['admired_calendar_day_text_color']);
	if ( isset ($options['admired_calendar_day_text_color'])&&  ($options['admired_calendar_day_text_color'] !="#FFFFFF") ) {
		echo(".calendar > .day{ color: $optColor;}\n");
	}
	
	$optColor = ($options['admired_calendar_day_BG_color']);
	if ( isset ($options['admired_calendar_day_BG_color'])&&  ($options['admired_calendar_day_BG_color'] !="#BABABA") ) {
		echo(".calendar > .day{ background-color: $optColor;}\n");
	}

	$optColor = ($options['admired_calendar_month_text_color']);
	if ( isset ($options['admired_calendar_month_text_color'])&&  ($options['admired_calendar_month_text_color'] !="#FFFFFF") ) {
		echo(".calendar > .month{ color: $optColor;}\n");
	}
	
	$optColor = ($options['admired_sticky_post_BG_color']);
	if ( isset ($options['admired_sticky_post_BG_color'])&&  ($options['admired_sticky_post_BG_color'] !="#F7F7F7") ) {
		echo(".home .sticky { background-color: $optColor;}\n");
	}
		
	// ********************** FONTS ********************
	$usefont = ($options['admired_body_font']);
	if ( isset ($options['admired_body_font']) && ($options['admired_content_font_select'] !="web-safe") ) {
		echo("body, input, textarea, .page-title span, .pingback a.url { font-family: $usefont;}\n");
	}
	
	$usefont = ($options['admired_content_area_font']);
	if ( isset ($options['admired_content_area_font'])&& ($options['admired_content_font_select'] =="web-safe") ) {
		echo("body, input, textarea, .page-title span, .pingback a.url { font-family: $usefont;}\n");
	}
	
	$usefont = ($options['admired_head_font']);
	if ( isset ($options['admired_head_font']) && ($options['admired_head_font_select'] !='web-safe') ) {
		echo("#site-title, #site-description { font-family: $usefont;}\n");
	}
	
	$usefont = ($options['admired_title_description_font']);
	if ( isset ($options['admired_title_description_font'])&&  ($options['admired_head_font_select'] =="web-safe") ) {
		echo("#site-title, #site-description { font-family: $usefont;}\n");
	}
	
	// ***************************  Header
	
	$HeaderLogo = ($options['admired_header_logo']);
	if ( isset ($options['admired_header_logo'])&&  ($options['admired_header_logo'] !="") ) {
		echo("#header-logo{ background: url($HeaderLogo) no-repeat;}\n");
	}
	
	/* options to change the primary menu bar colors */
	$optColor = ($options['admired_main_menubar_color']);
	if ( isset ($options['admired_main_menubar_color']) &&  ($options['admired_main_menubar_color'] !="#0281D4") ) {
	echo ("#nav-bottom-wrap {background: $optColor; border: 1px solid $optColor;} #nav-menu2 .menu-header li, div.menu li { border-right: 2px groove $optColor;} #nav-menu2 {text-shadow: none;}\n");}

	$optColor = ($options['admired_main_menubar_hoverbg_color']);
	if ( isset ($options['admired_main_menubar_hoverbg_color']) &&  ($options['admired_main_menubar_hoverbg_color'] !="#026BB0") ) {
		echo ("#nav-menu2 li:hover > a,#nav-menu2 a:focus {-ms-filter: 'progid:DXImageTransform.Microsoft.gradient(enabled=false)'; background: $optColor;}\n");
	}

	$optColor = ($options['admired_main_menubar_dropbg_color']);
	if ( isset ($options['admired_main_menubar_dropbg_color']) &&  ($options['admired_main_menubar_dropbg_color'] !="#0281D4") ) { 
		echo("#nav-menu2 ul ul a {background: $optColor; border-bottom: 2px groove $optColor;}\n");
	}
	
	$optColor = ($options['admired_main_menubar_dropbg_color_hover']);
	if ( isset ($options['admired_main_menubar_dropbg_color_hover']) &&  ($options['admired_main_menubar_dropbg_color_hover'] !="#026BB0") ) { 
		echo("#nav-menu2 ul ul :hover > a {background: $optColor;}\n");
	}

	$optColor = ($options['admired_main_menubar_text_color']);
	if ( isset ($options['admired_main_menubar_text_color']) &&  ($options['admired_main_menubar_text_color'] !="#FFFFFF") ) {
		echo ("#nav-menu2 a {color: $optColor;} #nav-menu2{text-shadow:none}\n");
	}
	
	/* options to change the secondary menu bar colors */
	
	$optColor = ($options['admired_menubar_color']);
	if ( isset ($options['admired_menubar_color']) &&  ($options['admired_menubar_color'] !="#292929") ) {
	echo ("#nav-menu {background-color: $optColor;}\n");
	}

	if ( isset ($options['admired_normal_menu_text'])) {
		echo("#nav-menu .menu-header,#nav-menu2 .menu-header, div.menu {font-weight: normal;}\n");
	}

	$optColor = ($options['admired_menubar_hoverbg_color']);
	if ( isset ($options['admired_menubar_hoverbg_color']) &&  ($options['admired_menubar_hoverbg_color'] !="#EFEFEF") ) {
		echo ("#nav-menu li:hover > a {background: $optColor;}\n");
	}

	$optColor = ($options['admired_menubar_dropbg_color']);
	if ( isset ($options['admired_menubar_dropbg_color']) &&  ($options['admired_menubar_dropbg_color'] !="#EFEFEF") ) { 
		echo("#nav-menu ul ul a{background-color: $optColor;}\n");
	}

	$optColor = ($options['admired_menubar_dropbg_color_hover']);
	if ( isset ($options['admired_menubar_dropbg_color_hover']) &&  ($options['admired_menubar_dropbg_color_hover'] !="#F9F9F9") ) { 
		echo("#nav-menu ul ul :hover > a {background: $optColor;}\n");
	}
	
	$optColor = ($options['admired_menubar_text_color']);
	if ( isset ($options['admired_menubar_text_color']) &&  ($options['admired_menubar_text_color'] !="#EEEEEE") ) {
		echo ("#nav-menu a {color: $optColor;}\n");
	}

	// SuperFish menu effects
	if ( isset ($options['admired_remove_superfish']) &&  ($options['admired_remove_superfish']!="") ) {
		echo("#nav-menu2 a{ padding: .4em 1.2125em;} \n"); // Have to fix the padding missing from style.css
	}
	else {
		echo(".sf-menu a.sf-with-ul { padding-right: 2.25em;} \n");
		echo("#nav-menu2 a{ line-height: 33px;} \n");
	}

	$img_SuperFish = get_template_directory_uri() . '/js/superfish/images/';
	$imgoption = ($options['admired_superfish_arrow_color']);
	if ( isset ($options['admired_superfish_arrow_color'])&&  ($options['admired_superfish_arrow_color'] !="White") ) { 
	    echo (".sf-sub-indicator{ background: url(");
		echo ("$img_SuperFish");
		echo ("$imgoption.png);}\n"); 
    }

	// Title and discription
	$optColor = ($options['admired_title_color']);
	if ( isset ($options['admired_title_color'])&&  ($options['admired_title_color'] !="#F7F7F7") ) {
		echo("#site-title a { color: $optColor;}\n");
	}
	
	$optColor = ($options['admired_title_hover_color']);
	if ( isset ($options['admired_title_hover_color'])&&  ($options['admired_title_hover_color'] !="#1982D1") ) {
		echo("#site-title a:hover, #site-title a:focus, #site-title a:active { color: $optColor;}\n");
	}

	$optColor = ($options['admired_description_color']);
	if ( isset ($options['admired_description_color'])&&  ($options['admired_description_color'] !="#C4C4C4") ) {
		echo("#site-description{ color: $optColor;}\n");
	}
	
	if ( isset ($options['admired_hide_title_discription'])&&  ($options['admired_hide_title_discription'] != "") ) {
		echo("#site-title, #site-description{ visibility: hidden;}\n");
	}
	if ( isset ($options['admired_search_placement'])&&  ($options['admired_search_placement'] !="Menu") ) {
		echo("#nav-menu2, #nav-menu2 .menu-header, div.menu { width: 91.61%; max-width: 1050px; margin-left: 10px; }\n");
	}

	//*********************************************************** Sidebar

	$optColor = ($options['admired_widget_title_bgcolor']);
	if ( isset ($options['admired_widget_title_bgcolor'])&&  ($options['admired_widget_title_bgcolor'] !="#66686E") ) {
		echo(".widget-title{ background: $optColor;}\n");
	}

	$optColor = ($options['admired_widget_title_txtcolor']);
	if ( isset ($options['admired_widget_title_txtcolor'])&&  ($options['admired_widget_title_txtcolor'] !="#FFFFFF") ) {
		echo(".widget-title{ color: $optColor;}\n");
	}

	// Sidebar bullets 
	$img_Bullet = get_template_directory_uri() . '/images/bullets/';      
	$val = ($options['admired_widget_bullet_color']);
	if ($val && $val != '' && $val != 'default') {
	if ($val == 'none' || $val == 'circle' || $val == 'disc' || $val == 'square')
	    echo(".widget-area ul li  {list-style:$val;}\n");
	else 
	    echo (".widget-area ul li {list-style:none;list-style-image: url(");
		echo ("$img_Bullet");
		echo ("$val.gif);}\n"); 
    }

	//*********************************************************** Comments

	$optColor = ($options['admired_guest_comment_color']);
	if ( isset ($options['admired_guest_comment_color'])&&  ($options['admired_guest_comment_color'] !="#F6F6F6") ) {
		echo(".commentlist > li.comment, .commentlist .children li.comment, .commentlist .pingback { background: $optColor;}\n");
	}
	
	$optColor = ($options['admired_author_comment_color']);
	if ( isset ($options['admired_author_comment_color'])&&  ($options['admired_author_comment_color'] !="#DDDDDD") ) {
		echo(".commentlist > li.bypostauthor, .commentlist .children > li.bypostauthor { background: $optColor;}\n");
	}
	
	$optColor = ($options['admired_leave_reply_color']);
	if ( isset ($options['admired_leave_reply_color'])&&  ($options['admired_leave_reply_color'] !="#DDDDDD") ) {
		echo("#respond { background: $optColor;}\n");
	}

	//*********************************************************** Footer
	if ( isset ($options['admired_remove_scroll_top']) &&  ($options['admired_remove_scroll_top'] != "")) {
		echo("#supplementary{padding-top: 1.625em;}\n");
	}

	if ( isset ($options['admired_hide_wp_link']) &&  ($options['admired_hide_wp_link']!="") ) {
		echo("#site-generator{display:none;}\n");
	}
	
	//************************************************************ Pagination
	$optColor = ($options['admired_pagination_text_color']);
	if ( isset ($options['admired_pagination_text_color'])&&  ($options['admired_pagination_text_color'] !="#FFFFFF") ) {
		echo(".pagination span, .pagination a { color: $optColor;}\n");
	}

	$optColor = ($options['admired_pagination_bg_color']);
	if ( isset ($options['admired_pagination_bg_color'])&&  ($options['admired_pagination_bg_color'] !="#878A92") ) {
		echo(".pagination span, .pagination a { background: $optColor;}\n");
	}
	
	$optColor = ($options['admired_pagination_hover_text_color']);
	if ( isset ($options['admired_pagination_hover_text_color'])&&  ($options['admired_pagination_hover_text_color'] !="#FFFFFF") ) {
		echo(".pagination a:hover{ color: $optColor;}\n");
	}

	$optColor = ($options['admired_pagination_hover_bg_color']);
	if ( isset ($options['admired_pagination_hover_bg_color'])&&  ($options['admired_pagination_hover_bg_color'] !="#686A71") ) {
		echo(".pagination a:hover{ background: $optColor;}\n");
	}
	
	$optColor = ($options['admired_pagination_current_text_color']);
	if ( isset ($options['admired_pagination_current_text_color'])&&  ($options['admired_pagination_current_text_color'] !="#333333") ) {
		echo(".pagination .current{ color: $optColor;}\n");
	}

	$optColor = ($options['admired_pagination_current_bg_color']);
	if ( isset ($options['admired_pagination_current_bg_color'])&&  ($options['admired_pagination_current_bg_color'] !="#686A71") ) {
		echo(".pagination .current{ background: $optColor;}\n");
	}
	
	// Social
	if ( isset ($options['admired_search_placement'])&&  ($options['admired_search_placement'] == "Header") ) {
		echo("div.admired-social { top: 47px;}\n");
	}
	if ( isset ($options['admired_show_social_icons'])&&  ($options['admired_show_social_icons'] != "") ) {
		echo("#branding #searchform { top: 0;}\n");
	}
	
?></style><!-- end of style section -->

<?php // **************** Custom CSS set by the user 
if ( isset ($options['admired_header_css']) &&  ($options['admired_header_css']!="") ) {
	$output = '<style type="text/css">'."\n";
	$output .= $options['admired_header_css'] . "\n";
	$output .= '</style><!-- end of custom css -->'."\n";
	echo stripslashes($output);
} 

			//* Superfish
if ( isset ($options['admired_remove_superfish']) &&  ($options['admired_remove_superfish']!="") ) {
	echo ' ';}
	else {
	echo("<script>
	jQuery(function(){jQuery('ul.sf-menu').superfish({animation: {opacity:'show',height:'show'}, speed: 300});});
	</script>\n");
	}
echo("\n<!-- End of Theme options -->\n");
}
add_action('wp_head', 'admired_wp_head');					/* action for post wp_head */
?>