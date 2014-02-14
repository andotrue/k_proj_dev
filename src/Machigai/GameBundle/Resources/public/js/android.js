$(document).ready(function(){
	var agent = navigator.userAgent;
	if(agent.search(/Android/) != -1){
		$("a").each(function(){
			if( $(this).attr("href") &&
				$(this).attr("href").search(/\/top$/) != -1 &&
				window["droid"]){
				$(thi).attr("href", "javascript:droid.goTop();");
			}
		});
		var pre_src_path = $('#toTopImage').attr('src');
		if(pre_src_path){
			var src_path = pre_src_path.replace(/button_back_totop_off/g,'button_toapptop_off');
			$('#toTopImage').attr("src",src_path);
			$('#toTopImage').width("60%");

			var pre_src_path2 = $('#preToTopImage').attr('src');
			var src_path2 = pre_src_path2.replace(/button_back_totop_off/g,'button_toapptop_off');
			$('#preToTopImage').attr("src",src_path2);
		}
	}
});

function goTop(){
    if(window["droid"]){
        droid.goTop();
    } else {
        window.location = '/top';
    }
}

//AndroidではsyncTokenをPreferenceManagerに渡す処理, webapp版ではなにもしない。
function registerUserIfAndroid(syncToken){
    if(window["droid"]){
        droid.registerUser(syncToken);
    }
}
//AndroidではsyncTokenをPreferenceManagerから削除する処理, webapp版ではなにもしない。
function logout(){
    if(window["droid"]){
        droid.logout();
    }
}


