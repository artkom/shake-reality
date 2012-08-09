<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @since admired 1.0
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( "Sorry, I've looked everywhere but I can't find the page you're looking for. Please try again with some different keywords.", 'admired' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'If you followed the link from another website, I may have removed or renamed the page. You may want to try searching for the page.', 'admired' ); ?></p>
					<p><?php _e( 'Sorry for the inconvenience.', 'admired' ); ?></p>
					<?php get_search_form(); ?>

					<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => '404' ) ); ?>

					<div class="widget">
						<h2 class="widgettitle"><?php _e( 'Most Used Categories', 'admired' ); ?></h2>
						<ul>
						<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
						</ul>
					</div>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
					<div style="clear:both;">

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>