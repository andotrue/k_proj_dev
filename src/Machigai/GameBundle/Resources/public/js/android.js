$(document).ready(function(){
	var agent = navigator.userAgent;
	if(agent.search(/Android/) != -1){
		$("a").each(function(){
			if( $(this).attr("href") &&
				$(this).attr("href").search(/\/top$/) != -1 &&
				window["droid"]){
				$(this).attr("href", "javascript:droid.goTop();");
			}
		});
	}
});

function goTop(){
    if(window["droid"]){
        droid.goTop();
    } else {
        window.location = '/top';
    }
}
