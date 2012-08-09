<?php 
/*
	Template Name: Navigation
*/ 
?>
<?php if (is_single()) { ?>
	<div class="navigation">
	  <div class="floatleft"><?php previous_post_link('&laquo; %link') ?></div>
	  <div class="floatright"><?php next_post_link('%link &raquo;') ?></div>
	  <div class="clear"></div>
	</div>
	<?php } else { ?>
	<div class="navigation">
	  <div class="floatleft"><?php next_posts_link('&laquo; '.__('Previous Entries','unnamed').''); ?></div>
	  <div class="floatright"><?php previous_posts_link(''.__('Next Entries','unnamed').' &raquo;'); ?></div>
	  <div class="clear"></div>
	</div>
<?php } ?>
