<?php
/**
 * The template for displaying  the Aside Post Format.
 *
 * @since admired 1.0
 */
 
global $options;
$options = get_option('admired_theme_options');
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<hgroup>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'admired' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<h3 class="entry-format"><?php _e( 'Aside', 'admired' ); ?></h3>
			</hgroup>

			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'admired' ) . '</span>', _x( '1', 'comments number', 'admired' ), _x( '%', 'comments number', 'admired' ) ); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php elseif ( isset ($options['admired_thumbnail_excerpt']) &&  ($options['admired_thumbnail_excerpt'] !="") ) : { ?>
		<div class="entry-summary">
			<?php if(has_post_thumbnail()) {
			echo '<span class="thumbnail"><a href="'; the_permalink(); echo '">';the_post_thumbnail(array(100,100)); echo '</a></span>';
			}
			the_excerpt(); } ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'admired' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'admired' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>
		<div style="clear:both;"></div>
		<footer class="entry-meta" style="clear:both;">
			<?php admired_posted_on(); ?>
			<?php if ( comments_open() ) : ?>
			<span class="sep"> | </span>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'admired' ) . '</span>', __( '<b>1</b> Reply', 'admired' ), __( '<b>%</b> Replies', 'admired' ) ); ?></span>
			<?php endif; ?>
			<?php edit_post_link( __( 'Edit', 'admired' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->
