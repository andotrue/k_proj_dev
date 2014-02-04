$(function(){
	//TODO:画像を差し込む
	$("body").append('<img id="warningPopup" style="position:absolute;width:200px;height:150px;" src="{{ asset("/bundles/machigaigame/images/parts/.png")  }}" />');
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
