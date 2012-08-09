<?php
/**
 * Flickr widget
 */
class admired_flickr_widget extends WP_Widget {
	function admired_flickr_widget() {
		$widget_ops = array('classname' => 'admired_flickr_feed', 'description' => __('Displays your flickr photos', "admired") );
		$this->WP_Widget('admired_flickr_feed', __('Admired - Flickr', "admired"), $widget_ops);	
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);	
		$UserID = $instance['UserID'];
		$Number = $instance['Number'];
		$link = $instance['link'];
		
		echo $before_widget;
	    if(!empty($title)) { echo $before_title . $title . $after_title; };
		
		$feed = "http://www.flickr.com/badge_code_v2.gne?count=" . $Number . "&display=latest&size=s&layout=x&source=user&user=" .$UserID;
		
		echo '<div id="flickr_tab">';
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=' . $Number . '&display=latest&size=s&layout=x&source=user&user=' .$UserID .'"></script>';
		echo '</div>';

		?>
	    <p class="flickr-link"><a href="http://flickr.com/photos/<?php echo $UserID; ?>"><?php echo $link; ?></a></p>
	  	<?php  
		
		echo $after_widget; 
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Flickr Photos', 'UserID' => '', 'link' => 'Photos on Flickr', 'Number' => '9' ) );
		$title = strip_tags($instance['title']);
		$UserID = strip_tags($instance['UserID']);
		$Number = strip_tags($instance['Number']);
		$link = strip_tags($instance['link']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', "admired"); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('UserID'); ?>"><?php _e('UserID', "admired"); ?>: <a href="http://get-flickr-id.ubuntu4life.com/" target="_blank"><?php _e('GetFlickr<font color=#FF0084>ID</font>', "admired"); ?></a>
				<input class="widefat" id="<?php echo $this->get_field_id('UserID'); ?>" name="<?php echo $this->get_field_name('UserID'); ?>" type="text" value="<?php echo esc_attr($UserID); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('Number'); ?>"><?php _e('Number to Display', "admired"); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('Number'); ?>" name="<?php echo $this->get_field_name('Number'); ?>" type="text" value="<?php echo esc_attr($Number); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link Text', "admired"); ?>: 
				<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
			</label>
		</p>
		<?php

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['UserID'] = strip_tags($new_instance['UserID']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['Number'] = strip_tags($new_instance['Number']);
		return $instance;
	}
}