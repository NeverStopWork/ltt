function slideLeft(obj,time) {
	var time = parseInt(time);
	var objWidth = obj.width();
	var speed = parseInt(objWidth) / time;
	if(speed<1) speed = 1;
	speed = 10 * speed;
	var n = 0;
	var timer = setInterval(function(){
		var currentMar = obj.css('margin-left');
		var newMar = parseInt(currentMar)- speed * 1;
		obj.css('margin-left',newMar+'px');
		if(-parseInt(currentMar) > parseInt(objWidth) + 10) {
			clearInterval(timer);
		}
	},1);
}
/*吐丝*/
function toastr(msg,time,is_mask){
	var toastrString = '<div class="toastr">'+msg+'</div>';
	console.log(toastrString);
	$('body').append(toastrString);
	if(is_mask == undefined || is_mask == 0) {
		
	} else {
		$('.mask').css('display','block');
		$('.toastr').css('display','block');
	}
	console.log(time);
	if(time==undefined) time = 2;
	console.log(time);
	time *= 1000;
	if(time>0) {
		var timer = setTimeout(function(time){
			$('.mask').css('display','none');
			$('.toastr').css('display','none');
			console.log('=====');
		},time);
	}
}
function maskShow(){
	$('.mask').fadeIn(100); // 返回一个 mask的id，用于调用的时候关闭
}
function loaddingShow(){
	var time = Date.parse( new Date());
	var str = '<div class="loadEffect" id="loading'+time+'">';
	    str += 	'<div><span></span></div>';
	    str += 	'<div><span></span></div>';
	    str + 	'<div><span></span></div>';
		str += 	'<div><span></span></div>';
		str += '</div>';
	maskShow();
	$('body').append(str);
}
