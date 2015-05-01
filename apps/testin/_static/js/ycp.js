// JavaScript Document
$(function(){
	<!--header nav 下划线HOVER事件 start-->
	$("#header .navbox .nav li:eq(0)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'40px',
			width:'50px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(1)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'118px',
			width:'75px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(2)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'225px',
			width:'75px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(3)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'333px',
			width:'60px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(4)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'423px',
			width:'75px'
	  },200);
	},function(){
	});
	$("#header .navbox .nav li:eq(5)").hover(function(){
		$("#header .navbox .hr").stop(false,true).animate({
			left:'530px',
			width:'90px'
	  },200);
	},function(){
	});
	<!--header nav 下划线HOVER事件  end-->	
	//关闭欢迎语
	$("#welcome .exit").click(function(){
		$("#welcome").stop(false,true).slideUp();
	});	
	//nav切换效果	
	$("#main_left .subMenuBox li").click(function(){
		$("#main_left .subMenuBox li a").css("color","#afdefc");
		$(this).find("a").css("color","#fff");
		$('#main_left .title p').removeClass("p_active");
		$('#main_left .title .icon').removeClass("icon_active");
		$(this).closest('.title').find('p').addClass("p_active");
		$(this).closest('.title').find('.icon').addClass("icon_active");
	});		
	$("#main_left .subscribe_btn").click(function(){
		alert('未知模块，你联系UI！');
	});		
})
//时间显示
	startTime();
	function startTime(){	
		var now= new Date();
		var dayNames = new Array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
		var year=now.getFullYear();
		var month=now.getMonth()+1;
		var date=now.getDate();
		var day=dayNames[now.getDay()];
		month=checkTime(month);
		date=checkTime(date); 
		$(".year").text(year);
		$(".month").text(month);
		$(".date").text(date);
		$(".day").text(day);
		t=setTimeout('startTime()',500);
	}	
	function checkTime(i){
		if (i<10) 
		  {i="0" + i}
		  return i
	}




