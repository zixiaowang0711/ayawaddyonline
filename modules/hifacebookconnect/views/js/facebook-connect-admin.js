/**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*/

$(document).ready(function() {
    $('.delete').attr('onclick','').unbind('click');
	$(".delete").click(function(){
        $('.hisc_fb_delete_popup').click();
        var id = $(this).attr("href").match(/id=([0-9]+)/)[1];
        $('.delete_table').attr('data-id', id);
        $('.delete_full').attr('data-id', id);
        $(this).closest('tr').addClass('delete_'+id);
        return false;
    });
    
    $(".hisc_fb_delete_popup").fancybox({
        width: 10000,
        closeBtn: true,
        maxHeight : 600,
        fitToView : true,
        autoSize : true,
        closeClick : true,
        autoscale : true,
        padding: 0
    });

    $(".delete_table, .delete_full").click(function(){
        var action = $(this).attr("data-delete-type");
        var table_id = $(this).attr("data-id");
        $.ajax({
            type:'POST',
            url:hi_sc_fb_admin_controller_dir,
            data:{
                ajax: true,
                action: action,
                table_id: table_id,
            },
            beforeSend: function(){
                $(".hisc_fb_loader").show();
            },
            success: function(response){
                $(".hisc_fb_loader").hide();
                $(".delete_"+table_id).hide();
            }
        });
    });
});