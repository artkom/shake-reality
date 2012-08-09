<form method="get" name="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" style="width: 95%;" />
	<span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="<?php _e('Search', 'kubrick'); ?>"/>
	</span>
</div>
</form>

