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


function itemInfo(){
	jQuery( document ).tooltip({
		items: "[data-itemInfo], [title]",
		position: { my: "left+5 center", at: "right center"},
		content: function() {
			var element = jQuery( this );
			if(jQuery( this ).prop("tagName").toUpperCase() == 'IFRAME'){
				return;
			}
			if ( element.is( "[data-itemInfo]" ) ) {
				if(element.parent().parent().find('.itemInfo').html() == ''){
					return;
				}
				return element.parent().parent().find('.itemInfo').html();
			}
			if ( element.is( "[title]" ) ) {
				return element.attr( "title" );
			}
		}
	});
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
jQuery(document).ready(function() {
	itemInfo();
});

function checkLength(sString) {
	sString = sString.toString();
	if (sString.length == 1) {
		sString = '0' + sString;
	}
	return sString;
}

function tTimer(iEndTimeStamp, iTimeStamp, sElement) {

	iTimeStamp = iTimeStamp - Math.round(+new Date() / 1000) - iEndTimeStamp;
	if (iTimeStamp < 0) {
		jQuery('#'+sElement).html('00:00:00');
		return false;
	}
	diffDay = iTimeStamp / (3600 * 24 );
	diffDay = diffDay.toString();
	diffDay = diffDay.split(".");
	diffHour = iTimeStamp / 3600 % 24;
	diffHour = diffHour.toString();
	diffHour = diffHour.split(".");
	diffMin = iTimeStamp / 60 % 60;
	diffMin = diffMin.toString();
	diffMin = diffMin.split(".");
	diffSek = iTimeStamp % 60;
	diffSek = diffSek.toString();
	diffSek = diffSek.split(".");
	if(diffDay[0] != 0){
		jQuery('#'+sElement).html(diffDay[0] + ':' + checkLength(diffHour[0]) + ':' + checkLength(diffMin[0]) + ':' + checkLength(diffSek[0]));
		return true;
	}
	jQuery('#'+sElement).html(checkLength(diffHour[0]) + ':' + checkLength(diffMin[0]) + ':' + checkLength(diffSek[0]));
	return true;
}

function ServerTimer()
{

	if(!document.all&&!document.getElementById)
	{
		return;
	}
	var ServerTime = new Date();
	var Stunden = ServerTime.getHours();
    var Minuten = ServerTime.getMinutes();
    var Sekunden = ServerTime.getSeconds();
    ServerTime.setSeconds(Sekunden + 1);
    if (Stunden <= 9) {
        Stunden = "0" + Stunden;
    }

    if (Minuten <= 9) {
        Minuten = "0" + Minuten;
    }
    if (Sekunden <= 9) {
        Sekunden = "0" + Sekunden;
    }
	jQuery('#timerClock').text(Stunden.toString()+':'+Minuten.toString()+':'+Sekunden.toString());
	
}

function reloadCaptcha() {

	jQuery.getJSON('/captcha/gen',function(data)
		{
			sCaptcha=data['captcha'];

			jQuery('#captchaImage').attr('src','/tmp/'+sCaptcha+'.png');

		});
}
