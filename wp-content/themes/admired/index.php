<?php
/**
 * The main template file.
 *
 * @since admired 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php admired_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php /* Display navigation when applicable */ ?>
				<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<?php /* add page navigation to indexes */ ?>
				<?php if (function_exists("admired_pagination")) {
					admired_pagination($wp_query->max_num_pages);
				} 
				endif; ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'admired' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'admired' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>