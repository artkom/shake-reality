<?php get_header(); ?>

<div id="content">
<?php include(TEMPLATEPATH."/l_sidebar.php");?>
<div id="contentleft">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	
	<p class="date"><b>Posted on</b> | <?php the_time('F j, Y'); ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
	
	  
	<?php the_content(__('Read more'));?><div style="clear:both;"></div>
	
	<div class="bt-links"><strong>Автор:</strong> <?php the_author(); ?><br /> <strong>Category:</strong> <?php the_category(', ') ?><br /><?php the_tags('<strong>Tags:</strong> ',' > '); ?></div>
	
	<!--
	<?php trackback_rdf(); ?>
	-->
	<?php do_action( 'social_connect_form' ); ?>
	<h3>Comments</h3>
	<?php comments_template(); // Get wp-comments.php template ?>
	
	<?php endwhile; else: ?>
	
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>

	</div>
	



</div>
<?php include(TEMPLATEPATH."/r_sidebar.php");?>
<!-- The main column ends  -->

<?php get_footer(); ?>