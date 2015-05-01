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
		$(this).css("background-position","78px -120px");
	},function(){
	});	
	
	$("#header .navbox .nav li.downLoad .pic").hover(function(){
	},function(){
		$(this).stop(false,true).slideUp(200);
		$("#header .navbox .nav li.downLoad").css("background-position","78px -90px");
	});

	
	//关闭欢迎语
	$("#welcome .exit").click(function(){
		$("#welcome").stop(false,true).slideUp();
	});
	//产品介绍切换
	$(".banner_nav li").hover(function(){
		$(this).find(".bg").addClass("dark");
		$(this).siblings().find(".bg").removeClass("dark");
		var ind=$(this).index();
		$(".nav_content li:eq("+ind+")").stop(false,true).fadeIn().siblings().fadeOut();
	},function(){
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
		var hour=now.getHours();
		var minute=now.getMinutes();
		var second=now.getSeconds();
		
		month=checkTime(month);
		date=checkTime(date);
		hour=checkTime(hour);
		minute=checkTime(minute);
		second=checkTime(second);
		if(hour < 6){$(".hello").text("凌晨好!")}   
		else if (hour < 9){$(".hello").text("早上好！")}   
		else if (hour < 12){$(".hello").text("上午好！")}   
		else if (hour < 14){$(".hello").text("中午好！")}   
		else if (hour < 17){$(".hello").text("下午好！")}   
		else if (hour < 19){$(".hello").text("傍晚好！")}   
		else if (hour < 22){$(".hello").text("晚上好！")}   
		else {$(".hello").text("夜里好!")}  
		$(".year").text(year);
		$(".month").text(month);
		$(".date").text(date);
		$(".day").text(day);
		$(".hour").text(hour);
		$(".minute").text(minute);
		$(".second").text(second);
		t=setTimeout('startTime()',500);
	}
	
	function checkTime(i){
		if (i<10) 
		  {i="0" + i}
		  return i
	}




