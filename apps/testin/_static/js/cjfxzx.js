// JavaScript Document
$(function(){	
	
//表格奇偶颜色不同 	
	$("table tr:even").css({background: "#f3f3f3"});
	
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

	$(".exit").click(function(){
		$(".addBox").fadeOut(500);
	});

	//成绩分析报表切换
	$("#cjfxzx_dkcjbg .analysisReport .nav li:eq(0)").css({"background":"#35B558","color":"#fff"});
	$("#cjfxzx_dkcjbg .analysisReport .subContent").hide();
		$("#cjfxzx_dkcjbg .analysisReport .subContent:eq(0)").show();
	$("#cjfxzx_dkcjbg .analysisReport .nav li").hover(function(){
		var ind=$(this).index();
		$(this).css({"background":"#35B558","color":"#fff"}).siblings().css({"background":"#fff","color":"#333"});
		$("#cjfxzx_dkcjbg .analysisReport .subContent").hide();
		$("#cjfxzx_dkcjbg .analysisReport .subContent:eq("+ind+")").slideDown(500);
	},function(){
	});	
	//添加分题段
	$(".addQuestions_btn").click(function(){
		$(".addQuestions").fadeIn();
	});	
	//添加分数段
	$(".addScores_btn").click(function(){
		$(".addScores").fadeIn();
	});
})




