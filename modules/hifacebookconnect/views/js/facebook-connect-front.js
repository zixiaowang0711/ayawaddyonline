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

function loaderOpening (){
	$('body').append('<div class="hi_spinner"><img src="'+sc_fb_loader+'"></div>');
	var top = $('body').scrollTop();
	$('.hi_spinner').css('top', top+'px');
	$('body').addClass('pf_overflow');
}
function loaderClose (){
	$('.hi_spinner').hide();
	$('body').removeClass('pf_overflow');
}

/*Facebook login start*/
function FbLogin() {
	FB.api('/me?fields=email,first_name,last_name,gender', function(response) {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: hi_sc_fb_front_controller_dir,
			data: {
				action : 'get_facebook_info',
				user_fname: response.first_name,
				user_lname: response.last_name,
				email: response.email,
				user_data_id: response.id,
				gender: response.gender
			},
			beforeSend: function(){
				loaderOpening();
			},
			success: function(response){
				if(response.activate_die_url != ''){
					 window.location.href = response.activate_die_url;
				} else {
					if(redirect == "no_redirect")
						location.reload();
					if(redirect == "authentication_page")
						window.location.href = authentication_page;
				}
			}
		});
	});
}

function fb_login(e){
	FB.login(function(response) {
		if (response.authResponse) {
			access_token = response.authResponse.accessToken;
			user_id = response.authResponse.userID;
			FbLogin();
		}
	},
	{
		scope: 'public_profile,email'
	});
}
/*Facebook login end*/

$(document).ready(function() {
	$(".onclick-btn").click(function(){
		return false;
	});
});
