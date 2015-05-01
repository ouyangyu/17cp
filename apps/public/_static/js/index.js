// JavaScript Document
$(function(){
	<!--header nav 下划线HOVER事件 start-->
	$("#header .navbox .nav li:eq(0)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'69px',
			width:'30px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(1)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'168px',
			width:'58px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(2)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'293px',
			width:'58px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(3)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'420px',
			width:'70px'
	  },200);
	},function(){
	});
	<!--header nav 下划线HOVER事件  end-->
	//二维码
	$("#header .navbox .nav li.downLoad").hover(function(){
		$(this).find(".pic").stop(false,true).slideDown();
		$(this).css("background-position","78px -1932px");
	},function(){
	});	
	
	$("#header .navbox .nav li.downLoad .pic").hover(function(){
	},function(){
		$(this).stop(false,true).slideUp(200);
		$("#header .navbox .nav li.downLoad").css("background-position","78px -1902px");
	});
})