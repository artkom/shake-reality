<?php
/**
 * The template for displaying all pages.
 *
 * @since admired 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop', 'page' ); ?>

					<?php if ( comments_open()):
					comments_template( '', true ); 
					endif; ?>
				<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>		
<?php get_footer(); ?>