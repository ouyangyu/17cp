<?php
/**
 * Created by PhpStorm.
 * User: ouyangyu
 * Date: 15-5-1
 * Time: 上午11:45
 */

class IndexAction extends Action{

    /**
     * 控制器初始化
     * @return void
     */
    protected function _initialize() {
        //$this->appCssList[] = 'css/style.css';
        $this->appCssList[] = 'testin.css';
        //$this->appCssList[] = 'css/yjzx_jqyj.css';
        $tab_list = [
            ['field_key'=>'testSetting','field_name'=>'考试设置'],
            ['field_key'=>'stuManager','field_name'=>'学生管理']

        ];
        $tab_list_security = [
            ['field_key'=>'byMachine','field_name'=>'机器阅卷'],
            ['field_key'=>'byHand','field_name'=>'手工导入']
        ];
        $tab_list_preference = [
            ['field_key'=>'alone','field_name'=>'单科成绩'],
            ['field_key'=>'contrast','field_name'=>'成绩对比'],
            ['field_key'=>'groupExcellent','field_name'=>'群体优良率'],
            ['field_key'=>'teaQuality','field_name'=>'教师质量']
        ];

        $plist = D('CpPeriod')->getPeriodList();
        $glist = D('CpGrade')->getGradeList();
        $clist = D('CpClass')->getClassList();
        $this->assign('clist',$clist);
        $this->assign('plist',$plist);
        $this->assign('glist',$glist);
        $this->assign('tab_list',$tab_list);
        $this->assign('tab_list_security',$tab_list_security);
        $this->assign('tab_list_preference',$tab_list_preference);




    }

    public function index() {
        $this->appCssList[] = 'css/yjzx.css';


        $this->display();
    }


    public function stuManager() {
        $this->appCssList[] = 'css/cjzx_xsgl.css';


        $titleList['school_id'] = $GLOBALS['ts']['uid'];
        $type = t($_POST['type']);
        if($type == 'select') {
            $titleList['class_id'] = !empty($_POST['class_id']) ? intval($_POST['class_id']) : 1;
            $titleList['grade_id'] = !empty($_POST['grade_id']) ? intval($_POST['grade_id']) : 12;
            $_SESSION['class_id'] = $titleList['class_id'];
            $_SESSION['grade_id'] = $titleList['grade_id'];
        } else {
            if(empty($_SESSION['class_id']) || empty($_SESSION['grade_id'])) {
                $titleList['class_id'] = 1;
                $titleList['grade_id'] = 12;
            }else {
                $titleList['class_id'] = $_SESSION['class_id'];
                $titleList['grade_id'] = $_SESSION['grade_id'];
            }
        }
        $schoolFind = D('CpSchoolInfo')->getOneOrSave($titleList);
        if($schoolFind) {
            $studentPage = null;
            $studentPage = D('CpSchoolStu')->getStudentList($schoolFind['school_info_id']);
            $titleList['count_stu'] = $studentPage['count'];
            $this->assign('studentList',$studentPage['data']);
            $this->assign('page', $studentPage['html']);

        }

        //$this->class_id = $titleList['class_id'];
        //$this->grade_id = $titleList['grade_id'];
        $this->assign('titleList',$titleList);

        $this->display();
    }

    public function alone(){
        $this->appCssList[] = 'css/cjfxzx.css';

        $this->display();

    }

    public function assignPublic() {
        $packNameList = D('CpPack')->getPackNameList($GLOBALS['ts']['uid']);
        $subjectList = D('CpSubject')->getSubjectList();

        $this->assign('subjectList',$subjectList);
        $this->assign('packNameList',$packNameList);
    }

    public function byHand() {
        $this->appCssList[] = 'css/yjzx.css';

        $this->assignPublic();



        $this->display();

    }

    public function doByHand() {
        dump($_POST);die();
    }
    public function byMachine() {
        $this->appCssList[] = 'css/yjzx.css';
        $this->assignPublic();
        $this->display();

    }

    public function contrast() {
        $this->appCssList[] = 'css/cjfxzx.css';

        $this->display();
    }

    public function groupExcellent() {
        $this->appCssList[] = 'css/cjfxzx.css';

        $this->display();

    }

    public function teaQuality() {
        $this->appCssList[] = 'css/cjfxzx.css';

        $this->display();

    }

    public function testSetting(){
        $this->appCssList[] = 'css/cjzx_xsgl.css';
        $packList = D('CpPack')->getPackPage($GLOBALS['ts']['uid']);
        $this->assign('packList',$packList['data']);
        $this->assign('page',$packList['html']);
        $this->assign('count',$packList['count']);
        $this->display();
    }

    public function saveTest() {

        // 安全过滤
        $pack['pack_name'] = t($_POST['pack_name']);
        $pack['grade_id'] = $_POST['grade_id'];
        $pack['period_id'] = $_POST['period_id'];
        $pack['createdby'] = $GLOBALS['ts']['uid'];

        if(!empty($pack['pack_name'])) {
            $result = D('CpPack')->addPack($pack);
            if($result) {
                $_SESSION['grade_id'] = $pack['grade_id'];
                $message = "添加成功！";
            }else {
                $message = "添加失败！";
            }
        } else {
            $message = "请输入考试名称！";
        }


        $this->ajaxReturn(1,$message);
    }

    public function updateTest() {
        $pack_id = $_POST['pack_id'];
        $pack_name = t($_POST['pack_name']);
        if(!empty($pack_id)) {
            $pack = D('CpPack')->getPackByID($pack_id);
            if(!empty($pack) && ($pack['createdby'] == $GLOBALS['ts']['uid'])) {
                if($pack_name != $pack['pack_name']){
                    $pack['pack_name'] = $pack_name;
                    $pack['grade_id'] = isset($_POST['grade_id']) ? $_POST['grade_id'] : $pack['grade_id'];
                    $pack['period_id'] = isset($_POST['period_id']) ? $_POST['period_id'] : $pack['period_id'];
                    $result = D('CpPack')->updatePack($pack);
                    if($result)
                        $message = "修改成功！";
                }else {
                    $message = "未更改！";
                }
            } else {
                    $message = "非法请求！";
            }


        } else {
            $message = "非法请求！";
        }

        $this->ajaxReturn(1,$message);


    }

    public function importStudent() {

        if(!empty($_FILES['fileExcel']['tmp_name']) && !empty($_POST['class_id']) && !empty($_POST['grade_id'])) {
            $schoolInfo['class_id'] = intval($_POST['class_id']);
            $schoolInfo['grade_id'] = intval($_POST['grade_id']);
            $schoolInfo['school_id'] = $GLOBALS['ts']['uid'];
            $schoolInfo['dec'] = t($_POST['grade_name']).'*'.t($_POST['class_name']);
            $uploadResult = D('CpUtil')->upload("student");
            if($uploadResult['status']) {

                $schoolFind = D('CpSchoolInfo')->getOneOrSave($schoolInfo,true);

                $user['school_info_id'] = is_array($schoolFind) ? $schoolFind['school_info_id'] : $schoolFind;
                $user['school_info_number'] = $schoolInfo['school_id'].'*'.$schoolInfo['grade_id'].'*'.$schoolInfo['class_id'];
                $result = D('CpUtil')->readExcel($uploadResult['data'],['A'=>'student_id','B'=>'student_name']);

                if($result['status']) {
                    foreach( $result['data'] as $student) {
                        //dump($student);
                        $user['student_id'] = $student['student_id'];
                        $user['student_name'] = $student['student_name'];
                        $import = D('CpSchoolStu')->importStudentInfo($user);
                        //dump(M()->getLastsql());die();

                        $message = $import ? "上传成功！" : "上传失败！";
                    }
                } else {
                    $message = $result['data'];
                }

            } else {
                $message = $uploadResult['data'];
            }
        }else {
            $message = "请检查！";
        }
        echo "<script type='text/javascript'>alert('".$message."');window.location.href='".U('testin/Index/stuManager')."';</script>";

        //$this->redirect('testin/Index/stuManager');

    }
    public function download() {
        $type = t($_GET['type']);
        if($type == 'student') {
            $file = PUBLIC_PATH.'/studentExcel/xsxx.xls';
            $name = "学生信息模板";
            D('CpUtil')->download($file,$name);

        }
        if($type == 'answer') {
            $file = PUBLIC_PATH.'/answerExcel/scwjgs.xls';
            $name = "学生题目明细成绩模版.xls";
            D('CpUtil')->download($file,$name);
        }

    }

}