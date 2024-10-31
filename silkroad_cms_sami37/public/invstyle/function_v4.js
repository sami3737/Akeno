
function ajaxReload(){
	if(xhr && xhr.readystate != 4){
		xhr.abort();
	}
}



function toggleSupportName(){
	if(jQuery('#idSupportName').css('display') == 'none'){
		jQuery('#idSupportName').show('blind');
		jQuery('#idSupportPlus').removeClass('fa-plus').addClass('fa-minus');
	}else{
		jQuery('#idSupportName').hide('blind');
		jQuery('#idSupportPlus').removeClass('fa-minus').addClass('fa-plus');
	}
}

function reloadCoins(){
	ajaxReload();
	jQuery('#reloadCoins .refresh').toggleClass('loader').toggleClass('fa-refresh');
	xhr = jQuery.ajax({
		url : "/panel/ajax/info/",
		type: "POST",		
		dataType: "json",
		data: {serverid: 1, vipcoins: 1},
		success : function(data){
			jQuery('.classWebPoints').text(getFormatNumber(data.webcoins));
			jQuery('#reloadCoins .refresh').toggleClass('loader').toggleClass('fa-refresh');
		},
		error: function(e) {
			jQuery('#reloadCoins .refresh').toggleClass('loader').toggleClass('fa-refresh');
	    }
	});
}

function getFormatNumber(number) {
	number = '' + number;
	if (!number.length > 3) {
		return number;
	}
	var mod = number.length % 3;
	var output = (mod > 0 ? (number.substring(0,mod)) : '');
	for (i=0 ; i < Math.floor(number.length / 3); i++) {
		if ((mod == 0) && (i == 0)){
			output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
		}else{
			output+= '.' + number.substring(mod + 3 * i, mod + 3 * i + 3);
		}
	}
	return (output);
}

(function($){
	$.fn.setCursorToTextEnd = function() {
		moveCursorToEnd(this);
	};
})(jQuery);

function moveCursorToEnd(input) {
	var originalValue = input.val();
	input.val('');
	input.blur().focus().val(originalValue);
}






