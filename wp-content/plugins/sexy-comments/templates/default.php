<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="bio" valign="top">
			<?php
				if($regular_comment) {
					echo $GLOBALS['comment']->avatar;
					comment_author_link();
				} else {
					echo $GLOBALS['comment']->avatar;
					?>
					<em><?php echo $GLOBALS['comment']->comment_type; ?>:</em>
					<?php
				}
				?>
			<br clear="all"/>
			<small class="commentmetadata">
				Posted: <a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date('M jS, Y') ?> at <?php comment_time() ?></a>
				<?php if(function_exists('wp_get_current_user')) edit_comment_link('<br/> edit comment','',''); ?>
			</small>
		</td>
		<td class="comment" valign="top">
			<div class="comment-count"><?php echo $comment_count; ?></div>
			<?php
			if ($GLOBALS['comment']->comment_approved == '0') { 
			?>
				<div class="message">Your comment is awaiting moderation.</div>
			<?php
			} //end approved

			if($author_highlight) {
			?>
				<div class="message">Author Comment</div>
			<?php
			}//end if
			
			if(!$regular_comment)
			{
				comment_author_link();
			}//end if
			?>
			<div id="comment_body_<?php comment_ID(); ?>">
				<div class="comment_body_container"><?php comment_text(); ?></div>
				<?php
				if($sexycomments_replyto)
				{
					?>
					<a href="#" class="comment-replyto">Reply to this comment.</a>
					<?php
				}
				?>
			</div>
		</td>
	</tr>
</table>
