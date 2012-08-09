<?php
/**
 * Register Twitter widget.
*/
global $twitter_username;
global $twitter_tweetcount;
$twitter_username = '';
$twitter_tweetcount = 1;

class admired_widget_twitter extends WP_Widget{
	
	function admired_widget_twitter(){
		$widget_ops = array('classname' => 'admired-twitter', 'description' => __('Display your recent Twitter status.', 'admired'));
		$control_ops = array('id_base' => 'admired-twitter');
		$this->WP_Widget('admired-twitter', 'Admired Twitter Status', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance){	
		extract($args);
		
		// Selected settings
		global $twitter_username;
		global $twitter_tweetcount;
		$twitter_title = $instance['twitter_title'];
		$twitter_username = $instance['twitter_username'];
		$twitter_tweetcount = $instance['twitter_tweetcount'];
		
		echo $args['before_widget'].$args['before_title'].$twitter_title.$args['after_title'];?>
        	<ul id="twitter_update_list" style="list-style:none; margin-left:3px; overflow:hidden; word-wrap: break-word;">
            	<li>&nbsp;</li>
            </ul>
            <p id="tweetfollow" class="sidebar_ablock"><a href="http://twitter.com/<?php echo $twitter_username; ?>"><?php _e('Follow me on Twitter', 'admired') ?></a></p>
            
            <?php do_action('admired_twitter_widget'); ?>
        <?php echo $args['after_widget']; ?>
        
        <?php
		add_action('wp_footer', 'admired_add_twitter_script');
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['twitter_username'] = strip_tags($new_instance['twitter_username']);
		$instance['twitter_tweetcount'] = strip_tags($new_instance['twitter_tweetcount']);
		$instance['twitter_title'] = strip_tags($new_instance['twitter_title']);
		
		return $instance;
	}
	
	function form($instance){ // Defaults
		$defaults = array(
						'twitter_username' => 'wordpress',
						'twitter_tweetcount' => 5,
						'twitter_title' => __('Recent tweets', 'admired'),
						);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
        <p>
        	<label for="<?php echo $this->get_field_id('twitter_title'); ?>"><?php _e('Title:', 'admired'); ?></label>
			<input id="<?php echo $this->get_field_id('twitter_title'); ?>" type="text" name="<?php echo $this->get_field_name('twitter_title'); ?>" value="<?php echo $instance['twitter_title']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('twitter_username'); ?>"><?php _e('Twitter Username:', 'admired'); ?></label>
			<input id="<?php echo $this->get_field_id('twitter_username'); ?>" type="text" name="<?php echo $this->get_field_name('twitter_username'); ?>" value="<?php echo $instance['twitter_username']; ?>" class="widefat" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('twitter_tweetcount'); ?>"><?php _e('Number of tweets to display:', 'admired'); ?></label>
			<input id="<?php echo $this->get_field_id('twitter_tweetcount'); ?>" type="text" name="<?php echo $this->get_field_name('twitter_tweetcount'); ?>" value="<?php echo $instance['twitter_tweetcount']; ?>" size="1" />
        </p>
        <?php
	}
}

if (!function_exists('admired_add_twitter_script')) :
	function admired_add_twitter_script(){
		global $twitter_username;
		global $twitter_tweetcount;
		echo '
		<!-- BEGIN Twitter Updates script -->
		<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$twitter_username.'.json?callback=twitterCallback2&amp;count='.$twitter_tweetcount.'"></script>
		<!-- END Twitter Updates script -->
		';
	}
endif;

?>