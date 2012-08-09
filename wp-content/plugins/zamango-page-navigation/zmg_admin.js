/*
 * $Date: 2010/02/12 16:48:45 $
 * $Revision: 1.0 $
 */

/******************************************************************************/
function zmg_show_help(id)
{
    jQuery('#' + id).fadeIn();
}

/******************************************************************************/
function zmg_hide_help(id)
{
    jQuery('#' + id).hide();
}

/******************************************************************************/
jQuery(document).ready(function(){

    if (jQuery('#zmg_adds').length == 0)
    {
        jQuery('#zmg_container').css('width', '100%');
    }
    else
    {
        if (zmg_adds.ok != "") return false;

        jQuery.each(zmg_adds.advertising, function (index, item) {

            var clone = jQuery('#zmg_adds_content .postbox').clone(true);

            jQuery(clone).attr('id', item.id);
            jQuery(clone).find('.hndle span').html(item.title);
            jQuery(clone).find('.inside').html('<p>' + item.content + '</p>');
            jQuery(clone).insertBefore('#zmg_adds_content');
        });
    }

    jQuery(".zmg_warning").each(function (i, item) {
        setTimeout(function () {
            jQuery(item).fadeOut();
        }, 3000);
    });
});


