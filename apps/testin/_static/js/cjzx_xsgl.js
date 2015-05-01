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
	
//表格奇偶颜色不同 	
	$("table tr:even").css({background: "#f3f3f3"});

/*//通过单击行 选中行
	$("table tbody tr").click(function(){
		$(this).toggleClass("trSelect");
		var checked = $(this).find(".check_btn").prop("checked");
		$(this).find(".check_btn").prop("checked",!checked);

	});*/
	
//通过单选框 选中行	
	$("tbody input").click(function(event){
		$(this).parent().parent().toggleClass("trSelect");
		event.stopPropagation();
	})

//全选	
	$("thead input").click(function(){
		var checked = $(this).prop("checked");
		$("tbody input").prop("checked",checked);
		if(checked){
			$("tbody tr").addClass("trSelect");
		}else{
			$("tbody tr").removeClass("trSelect");
		}
	})

	$(".add").click(function(){
		$(".addStudentBox").fadeIn(500);
		$(".importStudentBox").fadeOut(500);
	});
	$(".import").click(function(){
		$(".importStudentBox").fadeIn(500);
		$(".addStudentBox").fadeOut(500);
	});
	$(".exit").click(function(){
		$(".addStudentBox").fadeOut(500);
		$(".importStudentBox").fadeOut(500);
	});


    $("#selectgrade ").change(function() {
        //alert($(this).val()+$(this).find("option:selected").text());
        $("#gradeTitle").text($(this).find("option:selected").text());
        $("#classTitle").text('');
    });
    $("#selectclass ").change(function() {
        //alert($(this).val()+$(this).find("option:selected").text());
        $("#classTitle").text($(this).find("option:selected").text());
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




