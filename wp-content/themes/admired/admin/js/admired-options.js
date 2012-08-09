
jQuery(document).ready(function($) {
		$("#tabs").tabs({
			cookie: {
				// store cookie for 7 days, without, it would be a session cookie
				expires: 7
			}
		});
	});
	
jQuery(document).ready(
	function($)
	{
		$('.ctext-color').ColorPicker({
			onBeforeShow: function () {
				el = this;
				$(el).ColorPickerSetColor(el.value);
			},
			onShow: function (colpkr) {
				$(colpkr).fadeIn(250);
				return false;
			},
			onSubmit: function (hsb, hex, rgb) {
				$(el).val('#' + hex);
				$(el).css('backgroundColor', '#' + hex);
			},
			onChange: function (hsb, hex, rgb) {
				$(el).val('#' + hex);
				$(el).css('backgroundColor', '#' + hex);
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(250);
				return false;
			}
		})
		.bind('keyup', function () {
			$(this).ColorPickerSetColor(this.value);
		});
	}
);