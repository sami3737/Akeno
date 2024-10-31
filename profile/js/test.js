var xhr;
function switchPage(Type,Page){	

	
	ajaxReload();
	overOverLayer();	
	jQuery('#result').empty();
	xhr=jQuery.ajax({

		url: '/'+Type+'/page/'+Page,
		dataType:"html",
		success:function(data){
			
			jQuery('#result').html(data);
			overOverLayerClose();			
		},
		error:function(e){
			jQuery('#result').html('Bad Request');
			overOverLayerClose();
		}
	});
	overOverLayerClose();
}



function ajaxReload(){
	if(xhr && xhr.readystate != 4){
		xhr.abort();
	}
}

function overOverLayer(){
	overOverLayerClose();
	jQuery('<div id="ovd-overlay" class="ovd-overlay ovd-overlay-fixed"></div>').appendTo('body');
	jQuery('<div id="ovd-loading"><div></div></div>').appendTo('body');
}

function overOverLayerClose(){
	jQuery('#ovd-loading, #ovd-overlay').remove();
}

function adjustModalMaxHeightAndPosition(){
	jQuery('.modal').each(function(){
		if(jQuery(this).hasClass('in') == false){
			jQuery(this).show(); /* Need this to get modal dimensions */
		}
		var contentHeight = jQuery(window).height() - 60;
		var headerHeight = jQuery(this).find('.modal-header').outerHeight() || 2;
		var footerHeight = jQuery(this).find('.modal-footer').outerHeight() || 2;

		jQuery(this).find('.modal-content').css({
			'max-height': function () {
				return contentHeight;
			}
		});

		jQuery(this).find('.modal-body').css({
			'max-height': function () {
				return (contentHeight - (headerHeight + footerHeight));
			}
		});

		jQuery(this).find('.modal-dialog').addClass('modal-dialog-center').css({
			'margin-top': function () {
				return -(jQuery(this).outerHeight() / 2);
			},
			'margin-left': function () {
				return -(jQuery(this).outerWidth() / 2);
			}
		});
		if(jQuery(this).hasClass('in') == false){
			jQuery(this).hide(); /* Hide modal */
		}
	});
}

if (jQuery(window).height() >= 320){
	jQuery(window).resize(adjustModalMaxHeightAndPosition).trigger("resize");
}

function checkLength(sString) {
	sString = sString.toString();
	if (sString.length == 1) {
		sString = '0' + sString;
	}
	return sString;
}


function reloadCaptcha() {

	jQuery.getJSON('./captcha/captcha.php?gen',function(data)
	{
		sCaptcha=data['captcha'];

		jQuery('#captchaImage').attr('src',sCaptcha+'.png');

	});
}
