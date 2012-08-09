		<li id="search">
		  <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
			<div>
			  <input type="text" id="s" name="s" class="searchinput" value="<?php echo attribute_escape(__('Search blog archives','unnamed')); ?>" onfocus="if (this.value == '<?php echo attribute_escape(__('Search blog archives','unnamed')); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo attribute_escape(__('Search blog archives','unnamed')); ?>';}" />
			  <input style="display:none;" type="submit" id="searchsubmit" value="<?php echo attribute_escape(__('Search','unnamed')); ?>" />
			</div>
		  </form>
		</li>