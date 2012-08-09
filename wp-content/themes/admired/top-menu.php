<?php
/**
 * Top Secondary Menu
 *
 * @since admired 1.0
 */ ?>	

	<nav id="nav-menu" role="navigation">
		<h3 class="assistive-text"><?php _e( 'Secondary menu', 'admired' ); ?></h3>
		<?php /*  Allow screen readers to skip the navigation menu. */ ?>
		<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'admired' ); ?>"><?php _e( 'Skip to primary content', 'admired' ); ?></a></div>
		<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'admired' ); ?>"><?php _e( 'Skip to secondary content', 'admired' ); ?></a></div>
		<?php wp_nav_menu( array( 'theme_location' => 'secondary' ) ); ?>
	</nav><!-- #nav-menu -->