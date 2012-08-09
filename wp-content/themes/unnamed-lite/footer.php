<div class="content-bottom"></div>
	</div>
</div>


<hr />
<div id="footer">
	<p>
	<?php printf(__('%1$s is powered by %2$s and %3$s'), get_bloginfo('name'), '<a href="http://wordpress.org/" title="WordPress.org">WordPress '.get_bloginfo('version').'</a>', '<a href="http://xuyiyang.com/wordpress-themes/unnamed/" title="WordPress Theme: Unnamed">Unnamed Lite 1.0</a>'.' by <a href="http://xuyiyang.com/">Xu Yiyang</a>')?><br />
	<a href="<?php if ( get_option('unnamed_rss') != '') { echo (get_option('unnamed_rss')); } else { echo bloginfo('rss2_url'); } ?>"><?php _e('Entries (RSS)')?> </a><?php _e(' and ')?><a href="<?php bloginfo('comments_rss2_url'); ?>"> <?php _e('Comments (RSS)')?></a>
	</p>
	<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
</div>
<?php wp_footer(); ?>

</body>
</html>