$(document).ready(function(){
	var agent = navigator.userAgent;
	if(agent.search(/Android/) != -1){
		$("a").each(function(){
			if( $(this).attr("href").search(/\/top$/) != -1 ){
				$(this).attr("href", "javascript:droid.goTop();");
			}
		});
	}
});