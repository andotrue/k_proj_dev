/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
	var userAgent = window.navigator.userAgent.toLowerCase();
	if (userAgent.indexOf('/spass-app/') != -1){
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



