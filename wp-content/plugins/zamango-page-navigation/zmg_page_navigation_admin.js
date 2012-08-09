/*
 * $Date: 2010/03/04 12:30:32 $
 * $Revision: 1.0 $
 */

/******************************************************************************/
function zmg_pn_s_h_hints()
{
    if (jQuery('#zmg-page-navigation-tooltips').attr('checked'))
        jQuery('#zmg_pn_ttip_tag_div').show();
    else
        jQuery('#zmg_pn_ttip_tag_div').hide();
}

/******************************************************************************/
jQuery(document).ready(function(){
    jQuery('#zmg-page-navigation-tooltips').click(zmg_pn_s_h_hints);

    jQuery('#zmg-page-navigation-css').focus(function(){
        jQuery('#zmg-page-navigation-is_zmg_css-1').attr('checked', '');
        jQuery('#zmg-page-navigation-is_zmg_css-0').attr('checked', 'checked');
    });
});

