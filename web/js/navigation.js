$('.nav-icon-animation').on('touchstart',function(){
	var iconObj = $(this);
	iconObj.removeClass("animated");
	iconObj.removeClass("tada");
	iconObj.addClass("animated");
	iconObj.addClass("tada");
});
var timerMask = null;
$('.mask').on('touchend',function(){
	$('.mask').css('display','none');
	$('.nav-items-box').removeClass('bounceInLeft');
	$('.nav-items-box').addClass('tada');
	timerMask = setTimeout(function(){
		slideLeft($('.nav-items-box'),1500);
		$('.nav-items-box').removeClass('tada');
		$('.nav-items-box').removeClass('animated');
	},100);
});

/*点击头像icon弹出菜单*/
$('.navigation-icon').on('touchstart',function(){
	$('.mask').css('display','block');
	$('.nav-items-box').css('margin-left','-1px');
	$('.nav-items-box').addClass('animated bounceInLeft');
});
