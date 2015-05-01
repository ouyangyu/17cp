// 提交表单
$(document).ready(function() {

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
        $('#uploadStudent').submit(function(){
            $(this).ajaxSubmit({
                beforeSubmit:  checkStudentForm,
                success:       importStudentCallback,
                dataType: 'json'
            });
            //return false;
        });

        var checkStudentForm = function() {

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
