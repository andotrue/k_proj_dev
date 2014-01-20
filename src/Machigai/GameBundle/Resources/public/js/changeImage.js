var image_path = null;
var image_id = null;
function changeImage(imageId){
	var pre_src = $('#'+imageId).attr('src');
	var src = pre_src.replace(/_off/g,'');
	image_path = src;
	image_id = imageId;
	$('#'+imageId).attr("src",src);
	setTimeout("originalImage();", 200);
	setTimeout("jump(image_id);",50)
}
function originalImage(){
	var pre_src = $('#'+image_id).attr('src');
	var src = image_path.replace(/.png/g,'_off.png');
	$('#'+image_id).attr("src",src);
}
function jump(jumpTo){
	if(0 < $("#h"+jumpTo).size()){
		jump = ($("#h"+jumpTo).attr("href"));
		location.href = jump;
	}
}