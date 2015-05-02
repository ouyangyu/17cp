// 提交表单
$(document).ready(function() {
        $('#doByHandForm').submit(function() {
            $(this).ajaxSubmit({
                beforeSubmit:  checkByHandForm,
                success:   doByHandCallback,
                dataType: 'json'
            });

            return false;
        });

    var checkByHandForm = function() {
        var re = /^[0-9]+.?[0-9]*$/;
        if($('#tqn_input').val().length == 0) {
            alert('请输入客观题数目！');
            $('#tqn_input').focus();
            return false;
        } else if(!re.test($('#tqn_input').val())) {
            alert('客观题数目要求为数字！');
            $('#tqn_input').focus();
            return false;
        }
        if($('#objn_input').val().length == 0) {
            alert('请输入客观题分数！');
            $('#objn_input').focus();
            return false;
        }else if(!re.test($('#objn_input').val())) {
            alert('客观题分数要求为数字！');
            $('#objn_input').focus();
            return false;
        }
        if($('#sjts_input').val().length == 0) {
            alert('请输入主观题分数！');
            $('#sjts_input').focus();
            return false;
        }else if(!re.test($('#sjts_input').val())) {
            alert('主观题分数要求为数字！');
            $('#sjts_input').focus();
            return false;
        }
        var total = parseInt($('#sjts_input').val()) + parseInt($('#objn_input').val());

        var file=$("#file_answer_input");
        if($.trim(file.val())==''){
            alert("请选择文件后再提交!");
            return false;
        }
        //alert(total);
        if(total == 100 || total == 120 || total == 150) {
            return true;
        } else{
            alert("请核对主观题分数和客观题分数！");
            return false;
        }
    };
    var doByHandCallback = function() {
        alert(i.info);
        window.location.href = U('testin/Index/alone');
    };





    $('#updatePackForm').submit(function() {
        $(this).ajaxSubmit({
            beforeSubmit:  checkUpdatePackForm,
            success:       updatePackCallback,
            dataType: 'json'
        });
        return false;
    });

    var checkUpdatePackForm = function() {
        if($('#pack_name_up_input').val().length == 0) {
            alert('请输入考试名称！');
            $('#pack_name_input').focus();
            return false;
        }

        return true;
    };

    var updatePackCallback = function(i) {
        // var i = eval("("+e+")");
        alert(i.info);
        window.location.href = U('testin/Index/testSetting');
    }
    $('#newpack_form').submit(function() {
        $(this).ajaxSubmit({
            beforeSubmit:  checkPackForm,
            success:       AddPackCallback,
            dataType: 'json'
        });
        return false;
    });

    var checkPackForm = function() {
        if($('#pack_name_input').val().length == 0) {
            alert('请输入考试名称！');
            $('#pack_name_input').focus();
            return false;
        }

        return true;
    };

    var AddPackCallback = function(i) {
        // var i = eval("("+e+")");
        alert(i.info);
        window.location.href = U('testin/Index/testSetting');
    }


    $('#importStudentButton').click(function() {

            var file=$("#fileExcelId");
            if($.trim(file.val())==''){
                alert("请选择文件后上传!");
                return false;
            } else {
                $('#uploadStudent').submit();
                return true;

            }
        var checkStudentForm = function() {
            $("#fileUpload").click(function(){
                var file=$("#file");
                if($.trim(file.val())==''){
                    alert("请选择文件");
                    return false;
                }
                document.forms[0].submit();
            });
            return true;
        };
        // 成功后的回调函数
        var importStudentCallback = function(i) {
            // var i = eval("("+e+")");
            alert(i.info);
            window.location.href = U('testin/Index/stuManager');

        };
    });



    $('#addStudentForm').submit(function(){
        $(this).ajaxSubmit({
            beforeSubmit:  checkAddStudentForm,
            success:       AddCallback,
            dataType: 'json'
        });
        return false;
    });
    var checkAddStudentForm = function() {
        if($('#student_name_input').val().length == 0) {
            $('#student_name_input').focus();
            return false;
        }
        return true;
    };
    // 成功后的回调函数
    var AddCallback = function(i) {
        // var i = eval("("+e+")");
        alert(i.info);
        window.location.href = U('testin/Index/stuManager');

    };
}); 
