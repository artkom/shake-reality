jQuery.noConflict();
jQuery(document).ready(function() {								
	jQuery(".open").click(function(){
		jQuery(this).hide()
		jQuery(".close").show()
		jQuery("#toggle").slideDown(500);
		return false;
	});
	jQuery(".close").click(function(){
		jQuery(this).hide()
		jQuery(".open").show()
		jQuery("#toggle").slideUp(500);
		return false;
	});
});