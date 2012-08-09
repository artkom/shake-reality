    <hr />
    <div id="sidebar">
      <ul>
        <?php include (TEMPLATEPATH . '/searchform.php'); ?>
      </ul>
      <?php /* Category Archive */ if (is_category()) { ?>
      <p class="intro"><?php printf(__('You are currently browsing the %1$s weblog archives for the %2$s category.','unnamed'), '<a href="' . get_settings('siteurl') .'">' . get_bloginfo('name') . '</a>', single_cat_title('', false) ) ?></p>
      <?php /* Day Archive */ } elseif (is_day()) { ?>
      <p class="intro"><?php printf(__('You are currently browsing the %1$s weblog archives for the day %2$s.','unnamed'), '<a href="' . get_settings('siteurl') .'">' . get_bloginfo('name') . '</a>', get_the_time(__('l, F jS, Y','unnamed'))) ?></p>
      <?php /* Monthly Archive */ } elseif (is_month()) { ?>
      <p class="intro"><?php printf(__('You are currently browsing the %1$s weblog archives for the month %2$s.','unnamed'), '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', get_the_time(__('F, Y','unnamed'))) ?></p>
      <?php /* Yearly Archive */ } elseif (is_year()) { ?>
      <p class="intro"><?php printf(__('You are currently browsing the %1$s weblog archives for the year %2$s.','unnamed'), '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', get_the_time('Y')) ?></p>
      <?php /* Search */ } elseif (is_search()) { ?>
      <p class="intro"><?php printf(__('You have searched the %1$s weblog archives for \'<strong>%2$s</strong>\'.','unnamed'),'<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', wp_specialchars($s)) ?></p>
      <?php /* Paged Archive */ } elseif (is_paged()) { ?>
      <p class="intro"><?php printf(__('You are currently browsing the %s weblog archives.','unnamed'), '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>') ?></p>
      <?php } elseif (function_exists('is_tag') && is_tag()) { ?>
      <p class="intro">
        <?php if (function_exists('single_tag_title')) { 
                    printf(__('You are currently browsing the %1$s weblog archives for \'%2$s\' tag.','unnamed'), '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', single_tag_title('', false) ); 
                } elseif (!function_exists('single_tag_title')) { 
                    printf(__('You are currently browsing the %1$s weblog archives for \'%2$s\' tag.','unnamed'), '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', get_query_var('tag') ); } ?>
      </p>
      <?php /* Permalink */ } elseif (is_single()) { ?>
      <p class="intro">
        <?php _e('This entry is filed under ','unnamed')?>
        <?php the_category(', ') ?>
        <?php _e('. ','unnamed')?>
        <?php _e('You can follow any responses to this entry through ','unnamed') ?>
        <?php comments_rss_link(__('RSS 2.0','unnamed')) ?>
        <?php _e('. ','unnamed')?>
        <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { /* Both Comments and Pings are open */ ?>
        <?php _e('You can <a href="#comment">leave a response</a>, or ','unnamed')?>
        <a href="<?php trackback_url(true); ?>" rel="trackback">
        <?php _e('trackback','unnamed')?>
        </a>
        <?php _e(' from your own site. ','unnamed')?>
        <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { /* Only Pings are Open */ ?>
        <?php _e('Responses are currently closed, but you can ','unnamed') ?>
        <a href="<?php trackback_url(true); ?> " rel="trackback">
        <?php _e('trackback','unnamed')?>
        </a>
        <?php _e(' from your own site. ','unnamed') ?>
        <?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { /* Comments are open, Pings are not */ ?>
        <?php _e('You can skip to the end and leave a response. Pinging is currently not allowed. ','unnamed') ?>
        <?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { /* Neither Comments, nor Pings are open */ ?>
        <?php _e('Both comments and pings are currently closed. ','unnamed') ?>
        <?php } edit_post_link(__('Edit this entry.','unnamed')); ?>
      </p>
      <?php } ?>
      <?php if (is_search() || (function_exists('is_tag') && is_tag())) { ?>
      <p class="intro">
        <?php _e('Longer entries are truncated. Click the headline of an entry to read it in its entirety.','unnamed'); ?>
      </p>
      <?php } ?>
      <?php /* Frontpage */ if (is_home() || (is_page() && get_option('unnamed_showsidebarpage') == 1) || (is_archive() && get_option('unnamed_showsidebarcat') == 1) || (is_single() && get_option('unnamed_showsidebarsingle') == 1) || ((function_exists('is_tag') && is_tag()) && get_option('unnamed_showsidebarcat') == 1) ) { ?>
      <div class="left-sidecolumn">
        <ul>
          <?php /* if the Sidebar Widgets plugin is enabled */ if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
          <?php /* Menu for subpages of current page */
                            /* Menu for subpages of current page. Code from the great K2: http://getk2.com */
                global $notfound;
                if (is_page() and ($notfound != '1')) {
                    $current_page = $post->ID;
                    while($current_page) {
                        $page_query = $wpdb->get_row("SELECT ID, post_title, post_status, post_parent FROM $wpdb->posts WHERE ID = '$current_page'");
                        $current_page = $page_query->post_parent;
                    }
                    $parent_id = $page_query->ID;
                    $parent_title = $page_query->post_title;
        
                    $page_menu = wp_list_pages('echo=0&sort_column=menu_order&title_li=&child_of='. $parent_id);
                    if ($page_menu) { ?>
          <li class="sb-pagemenu">
            <h2><?php echo $parent_title; ?>
              <?php _e('Subpages','unnamed'); ?>
            </h2>
            <ul>
              <?php echo $page_menu; ?>
              <?php if ($parent_id != $post->ID) { ?>
              <li> <a href="<?php echo get_permalink($parent_id); ?>"><?php printf(__('Back to %s','unnamed'), apply_filters('the_title',$parent_title) ); ?></a> </li>
              <?php } ?>
            </ul>
          </li>
          <?php } } ?>
          <?php if (is_home() || is_page() || is_single()) { ?>
          <li>
            <h2>
              <?php _e('Latest','unnamed'); ?>
            </h2>
            <ul>
              <?php wp_get_archives('type=postbypost&limit=10'); ?>
            </ul>
          </li>
          <?php } if(is_home()) {?>
          <?php wp_list_bookmarks(); ?>
          <?php } if (is_archive()) { ?>
          <li>
            <h2>
              <?php _e('Categories','unnamed'); ?>
            </h2>
            <ul>
              <?php if (function_exists('wp_list_categories')) {
                                        
                                        wp_list_categories('title_li=&show_count=1&hierarchical=0');
                                    
                                    } else {
                                    
                                        list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0, '', '', '', '', '');
                                    }
                                ?>
            </ul>
          </li>
          <?php if (function_exists('wp_tag_cloud')) { ?>
          <li>
            <h2>
              <?php _e('Tags','unnamed'); ?>
            </h2>
            <ul>
              <li>
                <?php wp_tag_cloud('smallest=8&largest=22&orderby=count');	?>
              </li>
            </ul>
          </li>
          <?php } } ?>
          <?php /* end for Sidebar Widgets */ endif; ?>
        </ul>
      </div>
      <div class="right-sidecolumn">
        <ul>
          <?php /* if the Sidebar Widgets plugin is enabled */ if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
          <?php if (is_home() || is_page() || is_single()) { ?>
          <li>
            <h2>
              <?php _e('Subscribe','unnamed'); ?>
            </h2>
            <ul class="feedlink">
              <li><img src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="RSS" /><a href="<?php if ( get_option('unnamed_rss') != '') { echo (get_option('unnamed_rss')); } else { echo bloginfo('rss2_url'); } ?>" title="<?php _e('RSS Feed for Blog Entries','unnamed'); ?>">
                <?php _e('Entries RSS','unnamed'); ?>
                </a></li>
              <li><img src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="RSS" /><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('RSS Feed for all Comments','unnamed');?>">
                <?php _e('Comments RSS','unnamed'); ?>
                </a></li>
            </ul>
          </li>
          <?php } elseif (is_archive()) { ?>
          <li>
            <h2>
              <?php _e('Archives','unnamed'); ?>
            </h2>
            <ul>
              <?php wp_get_archives('type=monthly&show_post_count=1'); ?>
            </ul>
          </li>
          <?php } ?>
          <?php /* end for Sidebar Widgets */ endif; ?>
        </ul>
      </div>
      <?php } ?>
    </div>
    <div class="clear"></div>