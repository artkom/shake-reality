<?php
/**
 * The Footer widget areas.
 *
 * @since admired 1.0
 */
?>

<?php
	/* Check if footer widget area is active.
	 ---------------------------------------*/
	if (   ! is_active_sidebar( 'sidebar-3'  )
		&& ! is_active_sidebar( 'sidebar-4' )
		&& ! is_active_sidebar( 'sidebar-5'  )
	)
		return;
	// If active.
?>
	<div id="supplementary" <?php admired_footer_sidebar_class(); ?>>
		<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
		<div id="first" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-3' ); ?>
		</div><!-- #first .widget-area -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
		<div id="second" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
		</div><!-- #second .widget-area -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
		<div id="third" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-5' ); ?>
		</div><!-- #third .widget-area -->
		<?php endif; ?>
	</div><!-- #supplementary -->