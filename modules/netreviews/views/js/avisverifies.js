/**
* 2012-2017 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.4.0
*  @license   NetReviews
*  @date      01/09/2017
*  International Registered Trademark & Property of NetReviews SAS
*/

if(typeof jQuery == 'undefined') {

			function loadScript(url, callback) {
					var script = document.createElement("script")
					script.type = "text/javascript";
					if (script.readyState) { //IE
						script.onreadystatechange = function () {
							if (script.readyState == "loaded" || script.readyState == "complete") {
								script.onreadystatechange = null;
								callback();
							}
						};
					} else { //Others
						script.onload = function () {
							callback();
						};
					}
					script.src = url;
					document.getElementsByTagName("head")[0].appendChild(script);
				}

			var jQueryIsLoaded=false;
			try {
				if(typeof jQuery === 'undefined')
					jQueryIsLoaded=false;
				else
					jQueryIsLoaded=true;
			} catch(err) {
				jQueryIsLoaded=false;
			}

			if(!jQueryIsLoaded){
				//https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js
				loadScript("http://www.avis-verifies.com/review/js/jquery-1.8.2.min.js");

			}


}


jQuery(document).ready(function($) {
		$('#AV_button').on( "click", function(){ //version  Prestashop 1.7
 			if ($(".nav-item a").length != "0"){		
	            $(".nav-item a").each(function() {
	            	var tagname =$(this)[0].innerText.toLowerCase();
	                if(tagname.match(/avis vérifiés/)|| tagname.match(/verified reviews/) ||  tagname.match(/echte bewertungen/) ||  tagname.match(/opiniones verificadas/) ||  tagname.match(/recensioni verificate/) ||  tagname.match(/opiniões verificadas/)) {
	                    $(this).trigger('click');
	                    $(document).scrollTop($("h1").offset().top);
	                }
	            })
 			}else{ //autres versions
					$("a[href='#idTabavisverifies']").click();
					$('html,body').animate({scrollTop: $("#idTabavisverifies").offset().top}, 'slow');		
 			}
		});

});



					


function switchCommentsVisibility(review_number){

	comment = $('div[review_number='+review_number+']');
	console.log(review_number);
	comment.toggle();

	//Swich entre "afficher les échanges" et "masquer les échanges"
	$('a#display'+review_number+'[review_number='+review_number+']').toggle();
	$('a#hide'+review_number+'[review_number='+review_number+']').toggle();
}



