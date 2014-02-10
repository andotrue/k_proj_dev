$(function(){
	//TODO:画像を差し込む
	$("body").append(
			'<div id="warningPopup" style="display: none;position:absolute;width:100%;height:100%; background-color: #555;"' +
			'><p style="color: #FFF; margin-top: 3em; text-align: center">このアプリケーションは縦向きでご利用ください</p></div>'
	);
	$(window).bind("load orientationchange",function(){
		if(Math.abs(window.orientation) === 90){
			var point = $(window).width() / 30;
			$('#warningPopup').css({"font-size": point + "px"});
			$('#warningPopup').center();
			$('#warningPopup').show();
		}else{
			$('#warningPopup').hide();
		}
	})
})
