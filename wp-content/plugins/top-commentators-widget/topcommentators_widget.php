<?php
/* Plugin Name: Top Commentators Widget  
Description: Adds a sidebar widget to show the top commentators in your WP site. Adapted from Show Top Commentators plugin.  
Author: Lorna Timbah (WebGrrrl.net)
Version: 1.4.2
Author URI: http://webgrrrl.net 
Plugin URI: http://webgrrrl.net/archives/my-top-commentators-widget-quick-dirty.htm
*/   
add_action( 'widgets_init', 'topcomm_load_widgets' );

// start register widget
function topcomm_load_widgets() {
	register_widget( 'Topcomm_Widget' );
} // end register widget

// start class
class Topcomm_Widget extends WP_Widget {

	// start function Topcomm_Widget
	function Topcomm_Widget() {
		$widget_ops = array( 'classname' => 'topcomm', 'description' => __('Shows the top commentators in your blog.', 'topcomm') );
		$this->WP_Widget( 'topcomm-widget', __('Top Commentators Widget', 'topcomm'), $widget_ops, $control_ops );
	} // end function Topcomm_Widget

  // start function widget 
	function widget( $args, $instance ) {
		extract( $args );

		// variables
		$title = $instance['title'];
		$listDesc = $instance['listDesc'];
		// start name filter prep
		if($instance['excludeNames'] != "") {
			$excludeNames = trim($instance['excludeNames']);
			$excludeNames = explode(",", $excludeNames);
			for($i=0; $i<count($excludeNames); $i++) {
				$new_names .= " AND comment_author NOT IN ('" . trim($excludeNames[$i]) . "')";
			}
			$excludeNames = $new_names;
		} // end name filter prep
		$listPeriod = $instance['listPeriod'];
		// start list period setup
		if($listPeriod == "h") {
			$listPeriod = "DATE_FORMAT(comment_date, '%Y-%m-%d %H') = DATE_FORMAT(CURDATE(), '%Y-%m-%d %H')";
		} elseif($listPeriod == "d") {
			$listPeriod = "DATE_FORMAT(comment_date, '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')";
		} elseif($listPeriod == "w") {
			$listPeriod = "DATE_FORMAT(comment_date, '%Y-%v') = DATE_FORMAT(CURDATE(), '%Y-%v')";
		} elseif($listPeriod == "m") {
			$listPeriod = "DATE_FORMAT(comment_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')";
		} elseif($listPeriod == "y") {
			$listPeriod = "DATE_FORMAT(comment_date, '%Y') = DATE_FORMAT(CURDATE(), '%Y')";
		} elseif($listPeriod == "a") {
			$listPeriod = "1=1";
		} elseif(is_numeric($listPeriod)) {
			$listPeriod = "comment_date >= CURDATE() - INTERVAL $listPeriod DAY";
		// check if a range of date is entered, then generate the appropriate SQL statement
		} elseif(strpos($listPeriod, 'and') !== false) {
			$listPeriod = "comment_date BETWEEN $listPeriod";
		} else {
			$listPeriod = "comment_date >= CURDATE() - INTERVAL 30 DAY";
		} // end list period setup
		$listPeriod = ' AND ' . $listPeriod;
		$limitList = $instance['limitList'];
		$limitChar = $instance['limitChar'];
		$listNull = $instance['listNull'];
		// start url filter prep
		if($instance['filterUrl'] != "") {
			$filterUrl = trim($instance['filterUrl']);
			$filterUrl = explode(",", $filterUrl);
			for($i=0; $i<count($filterUrl); $i++) {
				$new_urls .= " AND comment_author_url NOT LIKE '%" . trim($filterUrl[$i]) . "%'";
			}
			$filterUrl = $new_urls;
		} // end url filter prep
		// start email filter prep
		if($instance['filterEmail'] != "") {
			$filterEmail = trim($instance['filterEmail']);
			$filterEmail = explode(",", $filterEmail);
			for($i=0; $i<count($filterEmail); $i++) {
				$new_emails .= " AND comment_author_email NOT LIKE '%" . trim($filterEmail[$i]) . "%'";
			}
			$filterEmail = $new_emails;
		} // end url filter prep
		$listType = $instance['listType'];
		// start list type setup
		if($listType == "num") {
			$listStart = "<ol>";
			$listEnd = "</ol>";
		} else {
			$listStart = "<ul>";
			$listEnd = "</ul>";
		} // end list type setup
		$makeLink = $instance['makeLink'];
		$targetBlank = $instance['targetBlank'];
		$noFollow = $instance['noFollow'];
		$showCount = $instance['showCount'];
		// start grouping setup
    if($instance['groupBy'] == "0") {
      $groupBy = "comment_author";
		} else {
			$groupBy = "comment_author_email";
		}
		$showInHome = $instance['showInHome'];
		$onlyWithUrl = $instance['onlyWithUrl'];
		if( $onlyWithUrl == '1' ) {
		  $onlyWithUrl = " AND comment_author_url != '' AND comment_author_url != 'http://'";
		} else {
      $onlyWithUrl = '';
    }
		$displayGravatar = $instance['displayGravatar'];
		$defaultGravatar = $instance['defaultGravatar'];
		$avatarSize = $instance['avatarSize'];
		$displayAward = $instance['displayAward'];
    $iconAward = $instance['iconAward'];
		$alignAward = $instance['alignAward'];

		// display widget in blog
 		$writeList = "\n" . $before_widget . "\n" . $before_title . $title . $after_title;
    // comment list                                          
    $writeList .= $listDesc . "\n";
    $writeList .= $listStart . "\n";
    global $wpdb;
    $commenters = $wpdb->get_results("SELECT COUNT($groupBy) AS comment_comments, comment_author, comment_author_url, comment_author_email 
      FROM $wpdb->comments
      WHERE comment_type != 'pingback'
      AND comment_author != ''
      AND comment_approved = '1' 
      $excludeNames
      $listPeriod
      $filterUrl
      $filterEmail
      $onlyWithUrl
      GROUP BY $groupBy
      ORDER BY comment_comments DESC, comment_author
      ");
    // start ifarray check
    if(count($commenters) > 0) {
      $commenters = array_slice($commenters,0,$limitList);
      // start foreach commenter
      foreach ($commenters as $k) {
        $url = $wpdb->get_var("SELECT comment_author_url FROM $wpdb->comments
          WHERE comment_author_email = '".addslashes($k->comment_author_email)."'
          AND comment_author_url != 'http://'
          AND comment_approved = 1
          ORDER BY comment_date DESC LIMIT 1
          ");
        $writeList .= '<li>';
        if(trim($url) != '') {
          // start makelink check
          if($makeLink == 1) {                        
            $writeList .= "<a href='" . $url . "'";
          if($noFollow == 1)
            $writeList .= " rel='nofollow'";
          if($targetBlank == 1)
            $writeList .= " target='_blank'";
          $writeList .= ">";
          } // end makelink check
        }
        $nCommentComments = $k->comment_comments;
        $nCommentComments = (int)$nCommentComments;
        // start comment count for award
        if($displayAward == '0') {
          $strDisplayAward = '';
        } elseif($nCommentComments >= $displayAward) {
          $strDisplayAward='<img class="tcwAward" src="' . $iconAward . '" alt="Top Commentator Award" title="Top Commentator Award" /> ';
        }
        if($alignAward==0) 
          $writeList .= $strDisplayAward;
        // start gravatar display check
        if($displayGravatar == 1)  {
          $image=md5(strtolower($k->comment_author_email));
          $defavatar=urlencode($defaultGravatar);
          $writeList .= '<img class="tcwGravatar" src="http://www.gravatar.com/avatar.php?gravatar_id='.$image.'&amp;size='.$avatarSize.'&amp;default='.$defavatar.'" alt ="'.$k->comment_author.'" title="'.$k->comment_author.'" /> ';
        } // end gravatar display check
        if($alignAward==1)
          $writeList .= $strDisplayAward;
        if(strlen($k->comment_author) > $limitChar) {
          $str = substr($k->comment_author, 0, $limitChar-3) . "...";
        } else {
          $str = $k->comment_author;
        }
        $writeList .= $str;
        if($showCount == 1)
          $writeList .= ' (' . $nCommentComments . ')';
        if(trim($url) != '') {
          if($makeLink == 1)
            $writeList .= "</a>";
        }
        if($alignAward == 2) 
          $writeList .= $strDisplayAward;
        $writeList .= "</li>\n";
        unset($url);
        ++$countList;
        $strDisplayAward = '';
      } // end foreach
    } else {
      $writeList .= "<li>" . $listNull . "</li>\n";
    } // end ifarray check
    $writeList .= $listEnd . "\n";
    $writeList .= $after_widget . "\n";
    if($showInHome == 1) {
    	if(is_home()) {
  		  echo $writeList;
      }
  	} else {
  	  echo $writeList;
  	}
	} // end function widget

  // start function update widget values
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['listDesc'] = $new_instance['listDesc'];
		$instance['excludeNames'] = strip_tags( $new_instance['excludeNames'] );
		if( $new_instance['listPeriodnum'] != '' ) {
      $instance['listPeriod'] = $new_instance['listPeriodnum'];
    } else {
		  $instance['listPeriod'] = $new_instance['listPeriod'];
		}
		$instance['limitList'] = $new_instance['limitList'];
		$instance['limitChar'] = $new_instance['limitChar'];
		$instance['listNull'] = strip_tags( $new_instance['listNull'] );
		$instance['filterUrl'] = $new_instance['filterUrl'];
		$instance['filterEmail'] = $new_instance['filterEmail'];
		$instance['listType'] = $new_instance['listType'];
		$instance['makeLink'] = $new_instance['makeLink'];
		$instance['targetBlank'] = $new_instance['targetBlank'];
		$instance['noFollow'] = $new_instance['noFollow'];
		$instance['showCount'] = $new_instance['showCount'];
		$instance['groupBy'] = $new_instance['groupBy'];
		$instance['showInHome'] = $new_instance['showInHome'];
		$instance['onlyWithUrl'] = $new_instance['onlyWithUrl'];
		$instance['displayGravatar'] = $new_instance['displayGravatar'];
		$instance['defaultGravatar'] = $new_instance['defaultGravatar'];
		$instance['avatarSize'] = $new_instance['avatarSize'];
		$instance['displayAward'] = $new_instance['displayAward'];
		if( $new_instance['iconAward'] == '' ) {
		  $instance['iconAward'] = 'https://lh3.googleusercontent.com/_gE22WSc7tcQ/TVZOTOGQ66I/AAAAAAAAABg/1mAYCyHmMpw/s800/medal_icon.jpg';
		} else {
      $instance['iconAward'] = $new_instance['iconAward'];
		}
		$instance['alignAward'] = $new_instance['alignAward'];		
    return $instance;
	} // end function update widget values

  // start function widget form
	function form( $instance ) {
		// default values setting
		$defaults = array( 'title' => 'Top Commentators', 'listDesc' => '', 'excludeNames' => 'admin', 'listPeriod' => 'm', 'limitList' => '10', 'limitChar' => '20', 'listNull' => 'Be the first to comment.', 'filterUrl' => '', 'filterEmail' => '', 'listType' => 'bul', 'makeLink' => '1', 'targetBlank' => '1', 'noFollow' => '0', 'showCount' => '1', 'groupBy' => '0', 'showInHome' => '1', 'onlyWithUrl' => '0', 'displayGravatar' => '1', 'defaultGravatar' => 'mm', 'avatarSize' => '20', 'displayAward' => '0', 'iconAward' => 'https://lh3.googleusercontent.com/_gE22WSc7tcQ/TVZOTOGQ66I/AAAAAAAAABg/1mAYCyHmMpw/s800/medal_icon.jpg', 'alignAward' => '2');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
    <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Change widget title:</label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width: 100%;" type="text" />
		</p>
    <p>
		<label for="<?php echo $this->get_field_id( 'listDesc' ); ?>">Add description below the title:</label>
		<input id="<?php echo $this->get_field_id( 'listDesc' ); ?>" name="<?php echo $this->get_field_name( 'listDesc' ); ?>" value="<?php echo $instance['listDesc']; ?>" style="width: 100%;" type="text" />
		<br /><small>Leave blank to exclude description</small>
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'excludeNames' ); ?>">Exclude these users:</label>
		<input id="<?php echo $this->get_field_id( 'excludeNames' ); ?>" name="<?php echo $this->get_field_name( 'excludeNames' ); ?>" value="<?php echo $instance['excludeNames']; ?>" style="width: 100%;" type="text" />
		<br /><small>Separate each name with a comma (,)</small>
    </p>
		<p>
		<label for="<?php echo $this->get_field_id( 'listPeriod' ); ?>">Reset period every:</label> 
		<select id="<?php echo $this->get_field_id( 'listPeriod' ); ?>" name="<?php echo $this->get_field_name( 'listPeriod' ); ?>">
		<option value="h"<?php if ( 'h' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>Hour</option>
		<option value="d"<?php if ( 'd' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>Day</option>
		<option value="w"<?php if ( 'w' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>Week</option>
		<option value="m"<?php if ( 'm' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>Month</option>
		<option value="y"<?php if ( 'y' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>Year</option>
		<option value="a"<?php if ( 'a' == $instance['listPeriod'] ) echo 'selected="selected"'; ?>>List all</option>
		</select>
		<br />Or specify number of days / enter range of date: <input style="width: 100%;" id="<?php echo $this->get_field_id( 'listPeriodnum' ); ?>" name="<?php echo $this->get_field_name( 'listPeriodnum' ); ?>" type="text" value="<?php
  	if( (int)$instance['listPeriod'] || (strpos($instance['$listPeriod'], 'and') !== false) )
   		 	echo $instance['listPeriod'];
  	?>" /><br /><small>E.g. <strong>100</strong> for # of days or <strong>20090301 and 20090531</strong> for date range</small></p>
		</p>
    <p>
		<label for="<?php echo $this->get_field_id( 'limitList' ); ?>">Limit number of names to:</label>
		<input id="<?php echo $this->get_field_id( 'limitList' ); ?>" name="<?php echo $this->get_field_name( 'limitList' ); ?>" value="<?php echo $instance['limitList']; ?>" style="width: 30px;" type="text" />
		<br /><small>Enter numbers only</small>
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'limitChar' ); ?>">Limit characters in names to:</label>
		<input id="<?php echo $this->get_field_id( 'limitChar' ); ?>" name="<?php echo $this->get_field_name( 'limitChar' ); ?>" value="<?php echo $instance['limitChar']; ?>" style="width: 30px;" type="text" />
		<br /><small>Enter numbers only</small>
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'listNull' ); ?>">Remarks for blank list:</label>
		<input id="<?php echo $this->get_field_id( 'listNull' ); ?>" name="<?php echo $this->get_field_name( 'listNull' ); ?>" value="<?php echo $instance['listNull']; ?>" style="width: 100%;" type="text" />
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'filterUrl' ); ?>">Filter the following full/partial URLs:</label>
		<input id="<?php echo $this->get_field_id( 'filterUrl' ); ?>" name="<?php echo $this->get_field_name( 'filterUrl' ); ?>" value="<?php echo $instance['filterUrl']; ?>" style="width: 100%;" type="text" />
		<br /><small>Separate each URL with a comma (,)</small>
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'filterEmail' ); ?>">Filter the following full/partial emails:</label>
		<input id="<?php echo $this->get_field_id( 'filterEmail' ); ?>" name="<?php echo $this->get_field_name( 'filterEmail' ); ?>" value="<?php echo $instance['filterEmail']; ?>" style="width: 100%;" type="text" />
		<br /><small>Separate each email with a comma (,)</small>
    </p>
		<p>
		<label for="<?php echo $this->get_field_id( 'listType' ); ?>">Display list as:</label> 
		<select id="<?php echo $this->get_field_id( 'listType' ); ?>" name="<?php echo $this->get_field_name( 'listType' ); ?>">
		<option value="bul"<?php if ( 'bul' == $instance['listType'] ) echo 'selected="selected"'; ?>>Bulleted</option>
		<option value="num"<?php if ( 'num' == $instance['listType'] ) echo 'selected="selected"'; ?>>Numbered</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'makeLink' ); ?>">Hyperlink each name?</label> 
		<select id="<?php echo $this->get_field_id( 'makeLink' ); ?>" name="<?php echo $this->get_field_name( 'makeLink' ); ?>">
		<option value="0"<?php if ( '0' == $instance['makeLink'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['makeLink'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'targetBlank' ); ?>">Open each link in a new window?</label> 
		<select id="<?php echo $this->get_field_id( 'targetBlank' ); ?>" name="<?php echo $this->get_field_name( 'targetBlank' ); ?>">
		<option value="0"<?php if ( '0' == $instance['targetBlank'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['targetBlank'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'noFollow' ); ?>">NoFollow each name if hyperlinked?</label> 
		<select id="<?php echo $this->get_field_id( 'noFollow' ); ?>" name="<?php echo $this->get_field_name( 'noFollow' ); ?>">
		<option value="0"<?php if ( '0' == $instance['noFollow'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['noFollow'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'showCount' ); ?>">Show number of comments for each commenter?</label> 
		<select id="<?php echo $this->get_field_id( 'showCount' ); ?>" name="<?php echo $this->get_field_name( 'showCount' ); ?>">
		<option value="0"<?php if ( '0' == $instance['showCount'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['showCount'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'groupBy' ); ?>">(Hijack-proof?) Group commentors based on:</label> 
		<select id="<?php echo $this->get_field_id( 'groupBy' ); ?>" name="<?php echo $this->get_field_name( 'groupBy' ); ?>">
		<option value="0"<?php if ( '0' == $instance['groupBy'] ) echo 'selected="selected"'; ?>>E-mail</option>
		<option value="1"<?php if ( '1' == $instance['groupBy'] ) echo 'selected="selected"'; ?>>User name</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'showInHome' ); ?>">Show in home page only?</label> 
		<select id="<?php echo $this->get_field_id( 'showInHome' ); ?>" name="<?php echo $this->get_field_name( 'showInHome' ); ?>">
		<option value="0"<?php if ( '0' == $instance['showInHome'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['showInHome'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'onlyWithUrl' ); ?>">Display only commentors with URL?</label> 
		<select id="<?php echo $this->get_field_id( 'onlyWithUrl' ); ?>" name="<?php echo $this->get_field_name( 'onlyWithUrl' ); ?>">
		<option value="0"<?php if ( '0' == $instance['onlyWithUrl'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['onlyWithUrl'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'displayGravatar' ); ?>">Display Gravatar?</label> 
		<select id="<?php echo $this->get_field_id( 'displayGravatar' ); ?>" name="<?php echo $this->get_field_name( 'displayGravatar' ); ?>">
		<option value="0"<?php if ( '0' == $instance['displayGravatar'] ) echo 'selected="selected"'; ?>>No</option>
		<option value="1"<?php if ( '1' == $instance['displayGravatar'] ) echo 'selected="selected"'; ?>>Yes</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'defaultGravatar' ); ?>">Use the following default Gravatar:</label> 
		<select id="<?php echo $this->get_field_id( 'defaultGravatar' ); ?>" name="<?php echo $this->get_field_name( 'defaultGravatar' ); ?>">
		<option value="404"<?php if ( '404' == $instance['defaultGravatar'] ) echo 'selected="selected"'; ?>>404</option>
		<option value="mm"<?php if ( 'mm' == $instance['defaultGravatar'] ) echo 'selected="selected"'; ?>>Mystery Man</option>
		<option value="identicon"<?php if ( 'identicon' == $instance['defaultGravatar'] ) echo 'selected="selected"'; ?>>Identicon</option>
		<option value="monsterid"<?php if ( 'monsterid' == $instance['defaultGravatar'] ) echo 'selected="selected"'; ?>>MonsterID</option>
		<option value="wavatar"<?php if ( 'wavatar' == $instance['defaultGravatar'] ) echo 'selected="selected"'; ?>>Wavatar</option>
		</select>
		Size:<input id="<?php echo $this->get_field_id( 'avatarSize' ); ?>" name="<?php echo $this->get_field_name( 'avatarSize' ); ?>" value="<?php echo $instance['avatarSize']; ?>" style="width: 30px;" type="text" />
		</p>
    <p>
		<label for="<?php echo $this->get_field_id( 'displayAward' ); ?>">Show an Award if comments are equal or greater than:</label>
		<input id="<?php echo $this->get_field_id( 'displayAward' ); ?>" name="<?php echo $this->get_field_name( 'displayAward' ); ?>" value="<?php echo $instance['displayAward']; ?>" style="width: 30px;" type="text" />
		<br /><small>Award image/icon appears if number greater than zero (0)</small>
    </p>
    <p>
		<label for="<?php echo $this->get_field_id( 'iconAward' ); ?>">Award icon/image location:</label>
		<input id="<?php echo $this->get_field_id( 'iconAward' ); ?>" name="<?php echo $this->get_field_name( 'iconAward' ); ?>" value="<?php echo $instance['iconAward']; ?>" style="width: 100%;" type="text" />
    </p>
		<p>
		<label for="<?php echo $this->get_field_id( 'alignAward' ); ?>">Align the Award icon:</label> 
		<select id="<?php echo $this->get_field_id( 'alignAward' ); ?>" name="<?php echo $this->get_field_name( 'alignAward' ); ?>">
		<option value="0"<?php if ( '0' == $instance['alignAward'] ) echo 'selected="selected"'; ?>>Left before Gravatar</option>
		<option value="1"<?php if ( '1' == $instance['alignAward'] ) echo 'selected="selected"'; ?>>Left after Gravatar</option>
		<option value="2"<?php if ( '2' == $instance['alignAward'] ) echo 'selected="selected"'; ?>>Right</option>
		</select>
		</p>
    <?php
  } // end function widget form
} // end class
?>