	<?php // Do not delete these lines
					if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
						die ('Please do not load this page directly. Thanks!');
				
					if (!empty($post->post_password)) { // if there's a password
						if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
							?>
	
	<p class="alert">This post is password protected. Enter the password to view comments.</p>
	<?php
							return;
						}
					}
				?>
	<div id="comment-section">
	  <?php if ($comments) : ?>
	  <h4 id="comments" class="section-title"><?php printf(__('%1$s %2$s to &#8220;%3$s&#8221;','unnamed'), '<span id="comments">' . get_comments_number() . '</span>', (1 == $post->comment_count) ? __('Response','unnamed'): __('Responses','unnamed'), the_title('', '', false)); ?></h4>
	  <hr />
	  <ol id="commentlist">
		<?php foreach ($comments as $comment) : ?>
		<li id="comment-<?php comment_ID(); ?>"> <span class="gravatar">
		  <?php /* Avatar support for wp 2.5 */ if ( function_exists('get_avatar') && get_option('show_avatars') ) { echo get_avatar( $comment, 32 ); } ?>
		  </span> <span class="comment-header"><a href="#comment-<?php comment_ID(); ?>" class="counter" title="<?php _e('Permanent Link to this Comment','unnamed'); ?>"><?php echo $comment_index; ?></a>
		  <?php comment_author_link() ?>
		  </span>
		  <div class="comment-content">
			<?php comment_text() ?>
		  </div>
		  <div class="comment-footer"> <span class="metacmt">
			<?php _e('Comment on ','unnamed') ?>
			<a href="#comment-<?php comment_ID() ?>" title="Permalink to Comment">
			<?php comment_date(__('M jS, Y','unnamed')) ?>
			<?php _e(' at ','unnamed') ?>
			<?php comment_time() ?>
			</a></span>&nbsp;&nbsp;
			<?php if ( $user_ID ) { edit_comment_link(__('Edit','unnamed'),'<span class="metaedit">','</span>'); } ?>
		  </div>
		  <?php if ( ! $comment->comment_approved ): ?>
		  <p class="alert"> <strong>
			<?php _e('Your comment is awaiting moderation.','unnamed'); ?>
			</strong> </p>
		  <?php endif; ?>
		</li>
		<?php endforeach; /* end for each comment */ ?>
	  </ol>
	  <!-- END #commentlist -->
	  <?php else : // this is displayed if there are no comments so far ?>
	  <?php if ('open' == $post->comment_status) : ?>
	  <!-- If comments are open, but there are no comments. -->
	  <ol id="commentlist">
		<li id="leavecomment">
		  <?php _e('No Comments','unnamed'); ?>
		</li>
	  </ol>
	  <?php else : // comments are closed ?>
	  <!-- If comments are closed. -->
	  <p class="alert">
		<?php _e('Comments are currently closed.','unnamed'); ?>
	  </p>
	  <?php endif; ?>
	  <!-- END .comments 1 -->
	  <?php endif; ?>
	  <?php if ('open' == $post->comment_status) : ?>
	  <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	  <p class="alert"><?php printf(__('You must <a href="%s">login</a> to post a comment.','unnamed'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink()); ?></p>
	  <?php else : ?>
	  <div id="respond">
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		  <?php if ( $user_ID ) : ?>
		  <p class="alert"><?php printf(__('Logged in as %s.','unnamed'), '<a href="' . get_option('siteurl') . '/wp-admin/profile.php">' . $user_identity . '</a>') ?><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','unnamed'); ?>">
			<?php _e('Logout','unnamed'); ?>
			&raquo;</a></p>
		  <?php elseif ($comment_author != "") : ?>
		  <p class="alert"><?php printf(__('Welcome back <strong>%s</strong>.','unnamed'), $comment_author) ?></p>
		  <?php endif; ?>
		  <?php if ( !$user_ID ) : ?>
		  <p>
			<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
			<label for="author"><strong>
			<?php _e('Name','unnamed'); ?>
			</strong>
			<?php if ( $req ): _e('(Required)','unnamed'); endif; ?>
			</label>
		  </p>
		  <p>
			<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
			<label for="email"><strong>
			<?php _e('Mail','unnamed'); ?>
			</strong>
			<?php _e('(Will not be published)','unnamed'); ?>
			<?php if ( $req ): _e('(Required)','unnamed'); endif; ?>
			</label>
		  </p>
		  <p>
			<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			<label for="url"><strong>
			<?php _e('Website','unnamed'); ?>
			</strong></label>
		  </p>
		  <?php endif; ?>
		  <p>
			<textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
		  </p>
		  <p>
			<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','unnamed'); ?>" />
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			<?php do_action('comment_form', $post->ID); ?>
		  </p>
		</form>
	  </div>
	  <?php endif; // If registration required and not logged in ?>
	  <!-- END .comments #2 -->
	  <?php endif; // if you delete this the sky will fall on your head ?>
	  <div class="clear"></div>
	</div>
	<?php include (TEMPLATEPATH . '/navigation.php'); ?>
