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
	$("#activate_submit").click(function(){
		var send_pass = $('#activate_submit').hasClass("send_pass");
		if (!send_pass) {
			var pass_erro = '0';
		} else {
			var pass_erro = '1';
		}
		$.ajax({
			type : "POST",
			url : hi_sc_fb_front_controller_dir,
			dataType: "json",
			data : {
				ajax : true,
				email : $("[name='email']").val(),
				password : $("[name='password']").val(),
				user_data_id : $("[name='user_data_id']").val(),
				user_fname : $("[name='user_fname']").val(),
				user_lname : $("[name='user_lname']").val(),
				gender : $("[name='gender']").val(),
				popup : $("[name='popup']").val(),
				pass_erro : pass_erro,
			},
			beforeSend: function(){
				loaderOpening();
			},
			success : function(response){
				if((response.error).length > 0){
					var error = '';
					loaderClose();
					$('.display_error').removeClass('hide');
					$.each(response.error, function(key, value) {
						error += (key+1)+'.' +value+'<br>'; 
					});
					$('.display_error').html(error);
					if (response.have_email) {
						if (!send_pass) {
							$('.link_my_account').removeClass('hide');
						} else {
							$('.link_my_account').addClass('hide');
						}
						
					}
				} else {
					$('.display_error').addClass('hide');
					window.onunload = refreshParent();
					function refreshParent() {
						if(redirect == "no_redirect") {
							if(response.popup) {
								window.opener.location.reload();
							} else {
								window.location.href = hi_sc_fb_base_url;
							}
						} else {
							if(response.popup) {
								window.opener.location.href = authentication_page;
							} else {
								window.location.href = authentication_page;
							}
						}
					}
					if(response.popup) {
						setTimeout(function(){
							self.close();
						}, 500)
					}
				}
			}
		});
		return false;
	});
	$("#link_my_account").click(function(){
		$('.fname, .lname, .display_error, .link_my_account').addClass('hide');
		$('.hidden_pass').removeClass('hide');
		$('.sc_back').removeClass('hide');
		$('#activate_submit').addClass('send_pass');
		return false;
	});
	$(".sc_back").click(function(){
		$('.fname, .lname').removeClass('hide');
		$('.hidden_pass, .display_error, .sc_back').addClass('hide');
		$('#activate_submit').removeClass('send_pass');
		return false;
	});
});
