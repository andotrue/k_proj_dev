/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var DEBUG_FLG = false;

if(DEBUG_FLG){
	alert("debug mode!!");
}

$(function(){
	var userAgent = window.navigator.userAgent.toLowerCase();
	var ios7over = isiOS7Over();
	
	if (DEBUG_FLG || (userAgent.indexOf('/spass-app/') != -1 && ios7over)){
		// 全てのページの先頭に20pxの空白を挿入
		$("body").prepend("<div style='width: 100%; height: 20px'>&nbsp;</div>");
		
		$("*").each(function(){
			if($(this).css("position") == "absolute"){
				$(this).css("top", parseInt($(this).css("top"),10) + 20);
			}
		});
		$("footer").css("padding-top", "40px");
	}
});

function isiOS7Over() {  
	var ua = navigator.userAgent;
	if(/iPhone/.test(ua)) {
		ua.match(/iPhone OS (\w+){1,3}/g);
		var osv=(RegExp.$1.replace(/_/g, '')+'00').slice(0,3);
		if(osv >= 700) {
			return true;
		}
	} else if(/iPad/.test(ua)) {
		ua.match(/CPU OS (\w+){1,3}/g);
		var osv=(RegExp.$1.replace(/_/g, '')+'00').slice(0,3);
		if(osv >= 700) {
			return true;
		}
	}
	return false;
}
