	<div class="sidecont rightsector">
		<div class="sidebar sidebar-right">

			<ul>
				<?php 
						if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar') ) : ?>
					
					<li id="tag_cloud"><h2>Теги</h2>
                        <div><?php wp_tag_cloud('largest=16&format=flat&number=20'); ?></div>
						
					</li>

					<li><h2>Архивы</h2>
						<ul>
						<?php wp_get_archives('type=monthly'); ?>
						</ul>
					</li>
					
					<li> 
						<h2>Календарь</h2>
						<?php get_calendar(); ?> 
					</li>
	
				<?php endif; ?>
			</ul>

		</div>
		
	</div>