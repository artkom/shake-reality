<div class="sidecont">
<div id="feedtwitter"><div class="twit-rss">
					<a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/rss.png"  style="margin:0 4px 0 0;"  /></a>		<?php if(get_theme_option('facebook') != '') { ?><a rel="nofollow" href="<?php echo get_theme_option('facebook'); ?>" title="<?php echo get_theme_option('facebooktext'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png"  style="margin:0 4px 0 0; "  title="<?php echo get_theme_option('facebooktext'); ?>" /></a><?php } ?>
					<?php if(get_theme_option('twitter') != '') { ?><a rel="nofollow" href="<?php echo get_theme_option('twitter'); ?>" title="<?php echo get_theme_option('twittertext'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/twitter.png"  style="margin:0 4px 0 0; "  title="<?php echo get_theme_option('twittertext'); ?>" /></a><?php } ?>
				</div>
                
				<div id="topsearch" class="span-7 rightsector">
					<?php get_search_form(); ?> 
				</div></div>
		<div class="sidebar sidebar-left">
			<ul>
				<?php 
						if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Sidebar') ) : ?>
	
					<li><h2><?php _e('Недавние записи'); ?></h2>
				               <ul>
						<?php wp_get_archives('type=postbypost&limit=5'); ?>  
				               </ul>
					</li>
                    
				<?php wp_list_categories('hide_empty=1&show_count=0&depth=1&title_li=<h2>Рубрики</h2>'); ?>
				
					
					
					
					<?php include (TEMPLATEPATH . '/recent-comments.php'); ?>
				<?php if (function_exists('get_recent_comments')) { get_recent_comments(); } ?>
				
						
					
					
				<?php endif; ?>
			</ul>
			
		
		</div>
</div>