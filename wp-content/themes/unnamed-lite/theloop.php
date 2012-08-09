		<?php 
		/* 
			This is the loop, which fetches entries from your database.
			It is a delicate piece of machinery. Be gentle! 
		*/ 
		?> 
		<?php /* Headlines for archives */ if ((!is_single() && !is_home()) || is_paged()) { ?>
			<h2 class="pagetitle">
			<?php if (is_category()) {
				  printf(__('Archive for the \'%s\' Category', 'unnamed'), single_cat_title('', false));
				} elseif (is_day()) {
				  printf(__('Archive for %s', 'unnamed'), get_the_time(__('F jS, Y', 'unnamed')));
				} elseif (is_month()) {
				  printf(__('Archive for %s', 'unnamed'), get_the_time(__('F, Y', 'unnamed')));
				} elseif (is_year()) {
				  printf(__('Archive for %s', 'unnamed'), get_the_time(__('Y', 'unnamed')));
				} elseif (is_search()) {
				  printf(__('Search Results for \'%s\'','unnamed'), attribute_escape(stripslashes(get_query_var('s'))));
				} elseif (function_exists('is_tag') && is_tag()) {
				  if (function_exists('single_tag_title')) {
					printf(__('Tag Archive for \'%s\'','unnamed'), single_tag_title('', false));
				} else {
					printf(__('Tag Archive for \'%s\'','unnamed'), get_query_var('tag') );
				}
				} elseif (is_paged() && ($paged > 1)) {
				  printf(__('Archive Page %s', 'unnamed'), $paged);
				}
			?>
			</h2>
			<?php } ?>
			<?php if (!is_single() && is_paged()) include (TEMPLATEPATH . '/navigation.php'); ?> 
			
			<?php /* Start the loop */ if (have_posts()) { while (have_posts()) { the_post(); ?>
			<?php /* Permalink nav has to be inside loop */ if (is_single()) include (TEMPLATEPATH . '/navigation.php'); ?>
			
			<div id="post-<?php the_ID(); ?>" class="entry">
				<h3 class="entry-header"><a href="<?php the_permalink() ?>" rel="bookmark" title='Permanent Link to "<?php strip_tags(the_title()); ?>"'> <?php the_title(); ?></a></h3>
				<div class="entry-date"><?php printf(__('%1$s by %2$s ','unnamed'), the_time(__(' F jS, Y','unnamed')), get_the_author()) ?></div>			
				<?php if (is_search() || is_tag()) {
 					the_excerpt();
				} else {
  					the_content(sprintf(__("Continue reading '%s'", 'unnamed'), the_title('', '', false)));
				} ?>
				
				<?php wp_link_pages('before=<p><strong>' . __('Pages:','unnamed') . '</strong>&after=</p>'); ?>
				<!-- <?php trackback_rdf(); ?> -->
				
				<div class="entry-footer">
                <?php if(!is_single()) { ?>
				<?php edit_post_link(__('Edit','unnamed'),'<span class="metaedit">','</span>&nbsp;&nbsp;'); ?>
                <?php } ?>
				<?php the_tags(__('<span class="metatag">Tags: ','unnamed'), ', ', '.</span>'); ?>
                <?php if(!is_single()) { ?>
				<span class="metacat"><?php the_category(','); ?></span>
				<?php comments_popup_link('&nbsp;&nbsp;<span class="metacmt">'.__('Add a comment','unnamed').'</span>', '<span class="metacmt">1&nbsp;'.__('Comment','unnamed').'</span>', '<span class="metacmt">%&nbsp;'.__('Comments','unnamed').'</span>', '', '<span class="metacmt">'.__('Closed','unnamed').'</span>'); ?>
                <?php } ?>
				</div>
				
			</div>
			
			<?php  } /* End The Loop */ ?>
			<?php /* Insert Paged Navigation */ if (!is_single()) { include (TEMPLATEPATH.'/navigation.php'); } ?>
			
			<?php /* If there is nothing to loop */  } else { ?>
			
			<h2 class="center"><?php _e('Not Found','unnamed'); ?></h2>
			<div class="entry">
				<p><?php _e('Oh no! You\'re looking for something which just isn\'t here! Fear not however, errors are to be expected, and luckily there are tools on the sidebar for you to use in your search for what you need.','unnamed'); ?></p>
			</div>
			
			<?php /* End Loop Init  */ } ?>