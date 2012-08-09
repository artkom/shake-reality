<?php

	/*** Default Values ***/


/* WP Twitter Consumer Keys for Twitter API. You can use your own keys instead by entering them on the admin page. */

global $fdx_updater_placeholders;
$fdx_updater_placeholders = "<br />
		   <small>Shortcodes:</small><br />
		   <code>#title#</code><small> (the title of your blog post) </small> <code>#url#</code><small> (the post URL)</small>
		   ";


/* Set defaults on first load. */

function fdx_updater_activate()
{
	// This won't overwrite the enries if the tokens and options are already set.

	$tokens_default = array(
		'request_key' => '',
		'request_secret' => '',
		'request_link' => '',
		'access_key' => '',
		'access_secret' => '',
		'auth1_flag' => '0',
		'auth2_flag' => '0',
		'auth3_flag' => '0',
				);
	
	add_option( 'fdx_updater_auth', $tokens_default, '', 'no' );
	
	$options_default = array(
		'newpost_update' => '1',
		'newpost_format' => 'New blog post: #title#: #url#',
		'edited_update' => '1',
		'edited_format' => 'Updated blog post: #title#: #url#',
		'limit_activate' => '0',
		'limit_to_category' => array(),
		'limit_to_custom_field_key' => '',
		'limit_to_custom_field_val' => '',
		'url_method' => 'default',
        'bitly_username' => '',
        'bitly_appkey' => '',
		'yourls_url' => 'http://',
		'yourls_username' => '',
		'yourls_passwd' => '',
		'yourls_token' => '',
				);
	
	add_option( 'fdx_updater_options', $options_default, '', 'no' );
	
	// Load any new option defaults into the original options array
	$current_options = get_option('fdx_updater_options');
	
	// Check all options from the default array exist in the current array. If not, load the default values.
	// checking this way should eliminate any old items that are not in the default array.
	foreach( $options_default as $key => $value )
	{
		if( !isset($current_options[$key]) )
		{
			$new_options[$key] = $options_default[$key];
		}
		else
		{
			$new_options[$key] = $current_options[$key];
		}
	}
	
	//zz.gd has closed, removed for version 3.1, if chosen - reset to default
	if( $current_options['url_method'] == 'zzgd' ) { $new_options['url_method'] == 'default'; }
	
	update_option( 'fdx_updater_options', $new_options );
}





/* Display the Admin Options Page */

function fdx_updater_options_page()
{
	$tokens = get_option('fdx_updater_auth');
	$options = get_option('fdx_updater_options');

	//style settings for form:
?>

<?php	
	
	//If bit.ly is selected, but no account information is present, show a warning
	if ( $options['url_method'] == 'bitly' && ( empty( $options['bitly_username'] ) || empty( $options['bitly_appkey'] ) ) )
	{
		echo "<div class='error'><p><strong>Bit.ly is selected, but Bit.ly account information is missing.</strong></p></div>";
	}
	
	//If YOURLS is selected, but no API address is entered, show a warning
	if ( $options['url_method'] == 'yourls' && $options['yourls_url'] == 'http://' )
	{
		echo "<div class='error'><p><strong>YOURLS is selected, but an API page address is missing.</strong></p></div>";
	}
	
	//Twitter Authorisation form
?>
   <div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>WP Twitter: Basic Settings and Connect</h2>
<div id="poststuff" class="metabox-holder">
<table style="width: 100%">
<tr>
<td style="padding-right: 20px;">

<ul id="accordion">
 <li><div class="postbox"><h3 class="hndle">Connect to Twitter<span class="espande">&nbsp;</span></h3></div> </li>
<ul style="margin-top: -23px">
 <div class="postbox">

<p><?php _e('<strong>WP Twitter</strong> uses <a href="https://dev.twitter.com/docs/auth/oauth/faq" target="_blank">OAuth</a> authentication to connect to Twitter. Follow the authentication process below to authorise <strong>WP Twitter</strong> access on your Twitter account.', 'wp-twitter') ?> </p>
 <form action="options.php" method="post">
<?php 		settings_fields('fdx_updater_auth');
		
		// Logic to display the correct form, depending on authorisation stage
		if( $tokens['auth1_flag'] != '1' )
		{
			update_option('fdx_updater_auth', $tokens);
			do_settings_sections('auth_1');
?>		  &nbsp;&nbsp;<input name="Submit" class="button-primary"  type="submit" value="<?php esc_attr_e('Register'); ?>" /> <strong style="color: red"><em>(<strong>WP Twitter</strong> does not have access to a Twitter account yet.)</em></strong>
<?php		}
		elseif( $tokens['auth1_flag'] == '1' && $tokens['auth2_flag'] != '1' )
		{

			//do registration and generate the register link
			$tokens = fdx_updater_register($tokens);
			update_option('fdx_updater_auth', $tokens);
		
			do_settings_sections('auth_2'); 
?>			<input name="Submit" class="button-primary"  type="submit" value="<?php esc_attr_e('Authorise'); ?>" />
<?php		} 
		else
		{
			if ( $tokens['auth2_flag'] == '1' && $tokens['auth3_flag'] != '1' )
			{
			//do authorisation
				$tokens = fdx_updater_authorise($tokens);
			}
			
			//do validation
			$verify = fdx_updater_verify($tokens);
			switch ($verify['exit_code']) 
			{
			case '1':
				echo "<p>Connection checked OK. <strong>WP Twitter</strong> can post to <a href='http://twitter.com/{$verify['user_name']}'>@{$verify['user_name']}</a></p>";
				$tokens['auth3_flag'] = '1'; //Will only validate until reset
				update_option('fdx_updater_auth', $tokens);
?>				<p class="submit" ><input name="Refresh" class="button-primary"  type="button" value="<?php esc_attr_e('Check again'); ?>" onClick="history.go(0)" /></p>
<?php 				break;
			case '2':
				echo "<div class='error'><p><strong>Not able to validate access to account, Twitter is currently unavailable. Try checking again in a couple of minutes.</strong></p></div>";
				$tokens['auth3_flag'] = '1'; //Will validate next time
				update_option('fdx_updater_auth', $tokens);
?>				<p class="submit" ><input name="Refresh" class="button-primary"  type="button" value="<?php esc_attr_e('Check again'); ?>" onClick="history.go(0)" /></p>
<?php				break;
			case '3':
				echo "<div class='error'><p><strong>WP Twitter does not have access to a Twitter account yet.</strong></p></div>";
				$tokens['auth3_flag'] = '0';
				update_option('fdx_updater_auth', $tokens);
				do_settings_sections('auth_2'); 
?>				<p class="submit" ><input name="Submit" class="button-primary"  type="submit" value="<?php esc_attr_e('Authorise'); ?>" /></p>
<?php				break;
			default:
				echo "<div class='warning'>WP Twitter is not currently authorised to use any account. Please reset and try again.</strong></p></div>";
				update_option('fdx_updater_auth', $tokens);
			}
		} 
?>
		</form>


<?php	// Button to reset OAuth process ?>
		<form action="options.php" method="post">
		<?php settings_fields('fdx_updater_auth'); ?>
        <h3>&nbsp;</h3>
		<p>Or restart the authorisation procedure: <input name="Submit" class="button-secondary"  type="submit" value="<?php esc_attr_e('Reset'); ?>" /></p>
			<div class="hid">	
				<?php do_settings_sections('auth_reset'); ?>
			</div>
			
		</form>
		  </div>
<?php	// WP Twitter Options form ?>



		<form action="options.php" method="post">

			<?php settings_fields('fdx_updater_options'); ?>


</ul>
<li><div class="postbox"><h3 class="hndle">Basic Settings<span class="espande">&nbsp;</span></h3></div> </li>
<ul style="margin-top: -23px">
<div class="postbox">
<?php do_settings_sections('new_post'); ?>

<?php do_settings_sections('edited_post'); ?>
</div>
 </ul>

<li><div class="postbox"><h3 class="hndle"><?php _e('Limit Updating', 'wp-twitter') ?><span class="espande">&nbsp;</span></h3></div> </li>
<ul style="margin-top: -23px">
<div class="postbox">
<p><?php _e('Twitter messages can be sent only when the post is a member of a [selected category], OR that have a specified Custom Field [title] OR [title AND value].', 'wp-twitter') ?></p>
<?php do_settings_sections('limit_tweets'); ?>
</div>
 </ul>

<li><div class="postbox"><h3 class="hndle"><?php _e('URL Shortener Account Settings', 'wp-twitter') ?><span class="espande">&nbsp;</span></h3></div> </li>
<ul style="margin-top: -23px">
<div class="postbox">
<p><?php _e('Choose your short URL service (account settings below)', 'wp-twitter') ?></p>
<?php do_settings_sections('short_url'); ?>
</div>
</ul>
</ul>






</td>

<?php include( FDX_WPTWITTER_DIR . '/inc/menu_esquerdo.php');?>
</form>
</div>
</div>




<?php
}

/* Set the Allowed Form Fields */

function fdx_updater_admin_init()
{
// Settings for OAuth procedure with Twitter
register_setting( 'fdx_updater_auth', 'fdx_updater_auth', 'fdx_updater_auth_validate' );

	// Consumer Key fields
    	add_settings_section('fdx_updater_consumer_keys', '', 'fdx_updater_auth_1', 'auth_1');
		add_settings_field('fdx_updater_auth1_flag', '', 'fdx_updater_auth1_flag', 'auth_1', 'fdx_updater_consumer_keys');

	// Register Keys switch
	add_settings_section('fdx_updater_register_keys', 'Register with Twitter:', 'fdx_updater_auth_2', 'auth_2');
		add_settings_field('fdx_updater_auth2_flag', '', 'fdx_updater_auth2_flag', 'auth_2', 'fdx_updater_register_keys');

	// Reset button fields
    	add_settings_section('fdx_updater_reset', '', 'fdx_updater_reset', 'auth_reset');
		add_settings_field('fdx_updater_auth1_reset', '', 'fdx_updater_auth1_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_auth2_reset', '', 'fdx_updater_auth2_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_auth3_reset', '', 'fdx_updater_auth3_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_req_key_reset', '', 'fdx_updater_req_key_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_req_sec_reset', '', 'fdx_updater_req_sec_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_req_link_reset', '', 'fdx_updater_req_link_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_acc_key_reset', '', 'fdx_updater_acc_key_reset', 'auth_reset', 'fdx_updater_reset');
		add_settings_field('fdx_updater_acc_sec_reset', '', 'fdx_updater_acc_sec_reset', 'auth_reset', 'fdx_updater_reset');

// Settings for WP Twitter
register_setting( 'fdx_updater_options', 'fdx_updater_options', 'fdx_updater_options_validate' );
		
	//Section 1: New Post published
	add_settings_section('fdx_updater_new_post', '', 'fdx_updater_new_post', 'new_post');
		add_settings_field('fdx_updater_newpost_update', 'Update when a post is published.', 'fdx_updater_newpost_update', 'new_post', 'fdx_updater_new_post');
		add_settings_field('fdx_updater_newpost_format', 'Tweet format for a new post:', 'fdx_updater_newpost_format', 'new_post', 'fdx_updater_new_post');
			
	//Section 2: Updated Post
	add_settings_section('fdx_updater_edited_post', '', 'fdx_updater_edited_post', 'edited_post');
		add_settings_field('fdx_updater_edited_update', 'Update when a post is edited.', 'fdx_updater_edited_update', 'edited_post', 'fdx_updater_edited_post');
		add_settings_field('fdx_updater_edited_format', 'Tweet format for an updated post:', 'fdx_updater_edited_format', 'edited_post', 'fdx_updater_edited_post');
	
	// Section 3: Limit tweets to posts with certain custom field/value pair or part of a specific category
      	add_settings_section('fdx_updater_limit_tweets', '', 'fdx_updater_limit_tweets' ,'limit_tweets');
 	  	add_settings_field('fdx_updater_limit_to_category', 'If no categories are checked, limiting by category will be ignored, and all categories will be Tweeted.', 'fdx_updater_limit_to_category', 'limit_tweets', 'fdx_updater_limit_tweets');
		add_settings_field('fdx_updater_limit_to_customfield', 'Send tweets for posts with this Meta [Title] OR [Title AND Value]', 'fdx_updater_limit_to_customfield', 'limit_tweets', 'fdx_updater_limit_tweets');

	//Section 4: Short Url service
		add_settings_section('fdx_updater_chose_url', '', 'fdx_updater_chose_url1', 'short_url');
}

/* Return Form components for the Allowed Form Fields */

// Consumer Keys form
function fdx_updater_auth_1()	{  }

function fdx_updater_auth1_flag()
	{ echo "<input id='fdx_updater_auth1_flag' type='hidden' name='fdx_updater_auth[auth1_flag]' value='1' />"; }

// Request link form
function fdx_updater_auth_2() 
	{ $tokens = get_option('fdx_updater_auth'); echo "<p>Now you need to tell Twitter you want to allow WP Twitter to be able to post using your account. <ol><li>Go to: <a href='{$tokens['request_link']}'>{$tokens['request_link']}</a></li><li>Follow the instructions at page to Allow access for (( WP Twitter ))</li><li>Return to this page to complete the process.</li></ol></p>"; }
function fdx_updater_auth2_flag() 
		{ echo "<input id='fdx_updater_auth2_flag' type='hidden' name='fdx_updater_auth[auth2_flag]' value='1' />"; }

// Hidden status' for OAuth reset button
function fdx_updater_reset()
	{ echo ''; }
function fdx_updater_auth1_reset()
	{ echo "<input id='fdx_updater_auth1_reset' type='hidden' name='fdx_updater_auth[auth1_flag]' value='0' />"; }
function fdx_updater_auth2_reset()
	{ echo "<input id='fdx_updater_auth2_reset' type='hidden' name='fdx_updater_auth[auth2_flag]' value='0' />"; }
function fdx_updater_auth3_reset()
	{ echo "<input id='fdx_updater_auth3_reset' type='hidden' name='fdx_updater_auth[auth3_flag]' value='0' />"; }
function fdx_updater_req_key_reset()
	{ echo "<input id='fdx_updater_req_key_reset' type='hidden' name='fdx_updater_auth[request_key]' value='' />"; }
function fdx_updater_req_sec_reset()
	{ echo "<input id='fdx_updater_req_sec_reset' type='hidden' name='fdx_updater_auth[request_secret]' value='' />"; }
function fdx_updater_req_link_reset()
	{ echo "<input id='fdx_updater_req_link_reset' type='hidden' name='fdx_updater_auth[request_link]' value='' />"; }
function fdx_updater_acc_key_reset()
	{ echo "<input id='fdx_updater_acc_key_reset' type='hidden' name='fdx_updater_auth[access_key]' value='' />"; }
function fdx_updater_acc_sec_reset()
	{ echo "<input id='fdx_updater_acc_sec_reset' type='hidden' name='fdx_updater_auth[access_secret]' value='' />"; }

//New Post published
function fdx_updater_new_post()
	{ echo ""; }
function fdx_updater_newpost_update()
	{ $options = get_option('fdx_updater_options'); echo "<input id='fdx_updater_newpost_update' type='checkbox' name='fdx_updater_options[newpost_update]' value='1'"; if( $options['newpost_update'] == '1' ) { echo " checked='true'"; }; echo " /> <code>Set the plugin behaviour for when a new post is published.</code>"; }
function fdx_updater_newpost_format()
	{ global $fdx_updater_placeholders; $options = get_option('fdx_updater_options'); echo "<input id='fdx_updater_newpost_format' type='text' size='60' maxlength='100' name='fdx_updater_options[newpost_format]' value='{$options['newpost_format']}' />" . $fdx_updater_placeholders; }

//Updated Post
function fdx_updater_edited_post()
	{ echo "<h3>&nbsp;</h3>"; }
function fdx_updater_edited_update()
	{ $options = get_option('fdx_updater_options'); echo "<input id='fdx_updater_edited_update' type='checkbox' name='fdx_updater_options[edited_update]' value='1'"; if( $options['edited_update'] == '1' ) { echo " checked='true'"; }; echo " /> <code>Set the plugin behaviour for when a previously published post is updated.</code>"; }
function fdx_updater_edited_format()
	{ global $fdx_updater_placeholders; $options = get_option('fdx_updater_options'); echo "<input id='fdx_updater_edited_format' type='text' size='60' maxlength='100' name='fdx_updater_options[edited_format]' value='{$options['edited_format']}' />" . $fdx_updater_placeholders; }

// Limit tweets to Categories and Custom Fields
function fdx_updater_limit_tweets()
	{ $options = get_option('fdx_updater_options');
       echo "<h3>Limit Twitter updates using the rules below? <input id='fdx_updater_limit_activate' type='checkbox' name='fdx_updater_options[limit_activate]' value='1'"; if( $options['limit_activate'] == '1' ) { echo " checked='true'"; }; echo " /></h3>";
    }

function fdx_updater_limit_to_category()
	{
	$options = get_option('fdx_updater_options');
	$categories=get_categories( array( 'orderby'=>'name', 'order'=>'ASC' , 'hide_empty'=>'0') );
	if ( !empty($categories) )
	{
		echo "</h3>";
			foreach($categories as $category)
             {  global $options; 
				echo "";
				echo "<input id='fdx_updater_limit_to_category_" . $category->name . "' type='checkbox' name='fdx_updater_options[limit_to_category][" . $category->name . "]' value='" . $category->cat_ID . "'";
				if( $options['limit_to_category'][$category->name] == $category->cat_ID ) { echo " checked='true'"; };
				echo " />";
				echo " <label for='fdx_updater_limit_to_category_" . $category->name . "'>" . $category->name . "</label>";
            	echo "&nbsp;&nbsp;";
			}
	}
	else
	{
		echo "No categories set. You must create categories before using them as limit criterion.";
	}

	}
function fdx_updater_limit_to_customfield()
	{
	$options = get_option('fdx_updater_options');
	echo "<input id='fdx_updater_limit_to_custom_field_key' type='text' size='20' maxlength='250' name='fdx_updater_options[limit_to_custom_field_key]' value='{$options['limit_to_custom_field_key']}' />";
	echo "<label for='fdx_updater_limit_to_custom_field_key'> <code>Custom Field Title (key)</code></label><br />";
	echo "<input id='fdx_updater_limit_to_custom_field_val' type='text' size='20' maxlength='250' name='fdx_updater_options[limit_to_custom_field_val]' value='{$options['limit_to_custom_field_val']}' />";
	echo "<label for='fdx_updater_limit_to_custom_field_val'> <code>Custom Field Value (leave blank to match any value)</code></label>";
	}



//Short Url service

function fdx_updater_chose_url1()
{ 	$options = get_option('fdx_updater_options');
	
	// Full length WordPress Permalink
	echo "<h3><input id='fdx_updater_chose_url' type='radio' name='fdx_updater_options[url_method]' value='permalink'";
	if( $options['url_method'] == 'permalink' ) { echo " checked='true'"; };
	echo " /><label for='fdx_updater_chose_url'> ".__('Wordpress default URL format', 'wp-twitter')." <code>Ex: http://domain.com/?p=123</code></label></h3>";

	//Bit.ly
	echo "<h3><input id='fdx_updater_chose_url' type='radio' name='fdx_updater_options[url_method]' value='yourls'";
	if( $options['url_method'] == 'yourls' ) { echo " checked='true'"; };
	echo " /> <label for='fdx_updater_chose_url'><a href='http://yourls.org/'>YOURLS.org</a>&nbsp;<code>".__('A free GPL URL shortener service', 'wp-twitter')."</code></label><small>";
		//Bit.ly Options
		echo "<p><label for='fdx_updater_yourls_url'>API page address:</label><input id='fdx_updater_yourls_url' type='text' size='60' name='fdx_updater_options[yourls_url]' value='{$options['yourls_url']}' /><code>Ex: http://domain.com/yourls-api.php</code><br />
           	<label for='fdx_updater_yourls_token'>Signature Token: </label><input id='fdx_updater_yourls_token' type='text' size='40' name='fdx_updater_options[yourls_token]' value='{$options['yourls_token']}' /><label> <em>(preferred)</em></p>
			   <p><label>When the YOURLS API is set to 'private' include either: Signature Token  <span class='red'>or</span>: Username & Password <em>(not recommended)</em></label></p>
				<p><label for='fdx_updater_yourls_username'>Username: </label><input id='fdx_updater_yourls_username' type='text' size='25' name='fdx_updater_options[yourls_username]' value='{$options['yourls_username']}' />
			<label for='fdx_updater_yourls_passwd'>&nbsp;&nbsp;&nbsp;Password: </label><input id='fdx_updater_yourls_passwd' type='text' size='25' name='fdx_updater_options[yourls_passwd]' value='{$options['yourls_passwd']}' /><label></label></p>
		  </small></h3>";


	//Bit.ly
	echo "<h3><input id='fdx_updater_chose_url' type='radio' name='fdx_updater_options[url_method]' value='bitly'";
	if( $options['url_method'] == 'bitly' ) { echo " checked='true'"; };
	echo " /> <label for='fdx_updater_chose_url'><a href='http://bit.ly'>Bit.ly</a></label>";
		//Bit.ly Options
    	echo "&nbsp;&nbsp;<small><label for='fdx_updater_bitly_username'>Username: </label><input id='fdx_updater_bitly_username' type='text' size='20' name='fdx_updater_options[bitly_username]' value='{$options['bitly_username']}' /> <label for='fdx_updater_bitly_appkey'>API Key: </label><input id='fdx_updater_bitly_appkey' type='text' size='50' name='fdx_updater_options[bitly_appkey]' value='{$options['bitly_appkey']}' /></small></h3>";

	// is.gd
	echo "<h3><input id='fdx_updater_chose_url' type='radio' name='fdx_updater_options[url_method]' value='is.gd'";
	if( $options['url_method'] == 'is.gd' ) { echo " checked='true'"; };
	echo " /> <label for='fdx_updater_chose_url'><a href='http://is.gd'>is.gd</a></label></h3>";

	// TinyURL
	echo "<h3><input id='fdx_updater_chose_url' type='radio' name='fdx_updater_options[url_method]' value='tinyurl'";
	if( $options['url_method'] == 'tinyurl' || $options['url_method'] == 'default' ) { echo " checked='true'"; };
	echo " /> <label for='fdx_updater_chose_url'><a href='http://tinyurl.com/'>TinyURL</a><code>(Default)</code></label></h3>";

	// ZZ.GD is now closed - option removed
}




/* Form validaton functions */

function fdx_updater_auth_validate($input) //n.b. else statements required for checkboxes
{
	$tokens = get_option('fdx_updater_auth');
	
	// The WordPress Settings API will overwrite arrays in the database with only the fields used in the form
	// To retain all the fields, the use the changed items to update the original array.
	if( isset( $input['request_key'] ) ) 		{ $tokens['request_key'] = 	$input['request_key']; }
	if( isset( $input['request_secret'] ) ) 	{ $tokens['request_secret'] = 	$input['request_secret']; }
	if( isset( $input['request_link'] ) ) 		{ $tokens['request_link'] = 	$input['request_link']; }
	if( isset( $input['access_key'] ) ) 		{ $tokens['access_key'] = 	$input['access_key']; }
	if( isset( $input['access_secret'] ) ) 		{ $tokens['access_secret'] = 	$input['access_secret']; }
	if( isset( $input['auth1_flag'] ) ) 		{ $tokens['auth1_flag'] = 	$input['auth1_flag']; }
	if( isset( $input['auth2_flag'] ) ) 		{ $tokens['auth2_flag'] = 	$input['auth2_flag']; }
	if( isset( $input['auth3_flag'] ) ) 		{ $tokens['auth3_flag'] = 	$input['auth3_flag']; }
	
	return $tokens;
}

function fdx_updater_options_validate($input) 
{
	$options = get_option('fdx_updater_options');
	
	// The WordPress Settings API will overwrite arrays in the database with only the fields used in the form
	// To retain all the fields, the use the changed items to update the original array.
	if( !empty( $input['newpost_update'] ) ) 	{ $options['newpost_update'] = 	$input['newpost_update']; } 	else { $options['newpost_update'] = '0'; }
	if( isset( $input['newpost_format'] ) ) 	{ $options['newpost_format'] = 	$input['newpost_format']; }
	if( !empty( $input['edited_update'] ) ) 	{ $options['edited_update'] = 	$input['edited_update']; } 	else { $options['edited_update'] = '0'; }
	if( isset( $input['edited_format'] ) ) 		{ $options['edited_format'] = 	$input['edited_format']; }
	if( !empty( $input['limit_activate'] ) ) 	{ $options['limit_activate'] = 	$input['limit_activate']; }  	else { $options['limit_activate'] = '0'; }
	if( !empty( $input['limit_to_category'] ) ) 	{ $options['limit_to_category'] = $input['limit_to_category']; } else { $options['limit_to_category'] = array(); }
	if( isset( $input['limit_to_custom_field_key'] ) ) { $options['limit_to_custom_field_key'] = $input['limit_to_custom_field_key']; }
	if( isset( $input['limit_to_custom_field_val'] ) ) { $options['limit_to_custom_field_val'] = $input['limit_to_custom_field_val']; }
	if( isset( $input['url_method'] ) ) 		{ $options['url_method'] = 	$input['url_method']; }
	if( isset( $input['bitly_username'] ) ) 	{ $options['bitly_username'] = 	$input['bitly_username']; }
	if( isset( $input['bitly_appkey'] ) ) 		{ $options['bitly_appkey'] = 	$input['bitly_appkey']; }
	if( !empty( $input['yourls_url'] ) ) 		{ $options['yourls_url'] = 	$input['yourls_url']; }		else { $options['yourls_url'] = 'http://'; }
	if( isset( $input['yourls_username'] ) ) 	{ $options['yourls_username'] = $input['yourls_username']; }
	if( isset( $input['yourls_passwd'] ) ) 		{ $options['yourls_passwd'] = 	$input['yourls_passwd']; }
	if( isset( $input['yourls_token'] ) ) 		{ $options['yourls_token'] = 	$input['yourls_token']; }
	
	return $options;
}

?>
