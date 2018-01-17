function setCookie(cname, cvalue, exdays, path = '/') {
    var d = new Date();
    var expires = '';
    if (exdays) {
    	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    	expires = ';expires='+d.toUTCString();
    }
    
    document.cookie = cname + '=' + cvalue + expires + ';path=' + path;
}

function getCookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

function ajaxNewsletterSubscribe() {
	var subscribeURL = $('.js-popup-newsletter-form').data('ajax-submit-url'),
		subscribeForm = $('.js-emailsubscription .js-subscription-form');

	if (subscribeURL) {
		subscribeForm.submit(function(event) {
			event.preventDefault();
			var $form = $(this);

			$.ajax({
		        type: 'POST',
		        url: subscribeURL,
				data: $form.serialize()+'&submitNewsletter=1',
		        dataType: 'json',
		        success: function(data) {
					if (data.nw_error) {
						$form.find('.newsletter-message').html('<p class="alert alert-danger">' + data.msg + '</p>');
					} else {
						$form.find('.newsletter-message').html('<p class="alert alert-success">' + data.msg + '</p>');
					}
		        },
		        error: function(XMLHttpRequest)
				{
					alert("Response Text:\n" + XMLHttpRequest.responseText);
				}
		    });

		  	return false;
		});
	}
}

function aoneNewsletterLoad()
{
	var popNewsletterModal = $('#aone-popup-newsletter-modal'),
		popNewsletter = popNewsletterModal.find('.js-aone-popupnewsletter'),
		cookie_expire = popNewsletter.data('hidepopup-time'),
		save_time = popNewsletter.data('save-time'),
		aclosed = getCookie('aonehidepopupnewsletter' + save_time);

	if (aclosed === '') {
      	setTimeout(function(){
      		popNewsletterModal.modal('show');
      	}, 3000);
	}

	popNewsletterModal.on('hidden.bs.modal', function () {
		var path = prestashop.urls.base_url.substring(prestashop.urls.shop_domain_url.length);
		setCookie('aonehidepopupnewsletter' + save_time, '1', parseInt(cookie_expire), path);
    });

	popNewsletter.find('.js-nothanks').click(function() {
		popNewsletterModal.modal('hide');
		return false;
	});

	ajaxNewsletterSubscribe();
}

$(window).load(function () {
	aoneNewsletterLoad();
});
