$(function(){
	//TODO:画像を差し込む
	$("body").append(
			'<div id="warningPopup" style="position:absolute;width:100%;height:100%; background-color: #555;"' +
			'><p style="color: #FFF; font-size: 12px; margin-top: 3em; text-align: center">このアプリケーションは縦向きでご利用ください</p></div>'
	);
	$('#warningPopup').hide();
	$(window).bind("orientationchange",function(){
		if(Math.abs(window.orientation) === 90){
			$('#warningPopup').center();
			$('#warningPopup').show();
		}else{
			$('#warningPopup').hide();
		}
	})
})
