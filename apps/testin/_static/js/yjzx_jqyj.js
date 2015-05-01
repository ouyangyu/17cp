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
	//关闭按钮
	$(".exit").click(function(){
		$(".addExamInformation").fadeOut(500);
	});
	//阅卷中心第一步
	$("#yjzx_jqyj_1 .addtestname").click(function(){
		$("#yjzx_jqyj_1 .addExamInformation").fadeIn(500);
	});
	//阅卷中心第二步
	$("#yjzx_jqyj_2 .save_btn").click(function(){
		$("#yjzx_jqyj_2 .standardAnswer").slideDown(1000);
		$("#yjzx_jqyj_2 .nextStep").slideDown(1000);
	});
	//阅卷中心第三步
	$("#yjzx_jqyj_3 .startRead_btn").click(function(){
		$("#yjzx_jqyj_3 .standardAnswer").slideDown(1000);
	});

})




