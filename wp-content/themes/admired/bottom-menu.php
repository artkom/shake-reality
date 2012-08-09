<?php
/**
 * Bottom Primary Menu.
 *
 * @since admired 1.0
 */ 
global $options;
$options = get_option('admired_theme_options'); ?>
	<div id="nav-bottom-menu">
		<div id="nav-bottom-wrap">
			<nav id="nav-menu2" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Main menu', 'admired' ); ?></h3>
				<?php /*  Allow screen readers to skip the navigation. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'admired' ); ?>"><?php _e( 'Skip to primary content', 'admired' ); ?></a></div>
				<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'admired' ); ?>"><?php _e( 'Skip to secondary content', 'admired' ); ?></a></div>
				<?php /* Our navigation menu. */ ?>
				<?php if ( isset ($options['admired_remove_superfish']) &&  ($options['admired_remove_superfish']!="") )
						wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) );
					else
						wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'menu_class' => 'sf-menu','fallback_cb' => 'admired_page_menu'  ) );?>
			<?php /* Search in menu. */ ?>
			<?php if ( isset ($options['admired_search_placement'])&&  ($options['admired_search_placement'] != "Menu") ) {
						echo '';
					} else{
						get_search_form(); } ?>
			</nav><!-- #nav-menu2 -->
		</div>
	</div>