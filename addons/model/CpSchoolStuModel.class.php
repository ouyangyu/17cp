<?php
/**
 *      年级列表
 *
 */
class CpSchoolStuModel extends Model {

	protected $tableName = 'cp_school_stu';
	protected $fields =	array (0=>'id',1=>'school_info_id',2=>'student_name',3=>'first_letter',4=>'search_key',5=>'student_id',6=>'school_info_number');


    public function getStudentList($schoolInfoId, $limit = 10,$order = "first_letter DESC") {

        if(!empty($schoolInfoId)){
            $map['school_info_id'] = $schoolInfoId;
            import('ORG.Util.Page');// 导入分页类
            $count      = $this->where($map)->count();// 查询满足要求的总记录数
            $page       = new Page($count,$limit);// 实例化分页类 传入总记录数
            // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
            $nowPage = $_GET['p'] ? $_GET['p'] : 1;
            $page->nowPage = $nowPage;
            //$nowPage = isset($_GET['p'])?$_GET['p']:1;
            $list = $this->where($map)->order($order)->limit(($nowPage-1)*$page->listRows.','.$nowPage*$page->listRows)->select();

            $pageList['data'] = $list;
            $pageList['count'] = $count;
            $pageList['html'] = $page->show();
            return $pageList;
            /*$show       = $Page->show();// 分页显示输出
            $this->assign('page',$show);// 赋值分页输出
            $this->assign('list',$list);// 赋值数据集*/

            /*// 查询数据
            $list = $this->where($map)->order ( $order )->findPage ( $limit );
                return $list;*/
        }


    }

    public function saveStudent($user) {
        if(is_array($user)) {
            // 添加昵称拼音索引
            $user ['first_letter'] = getFirstLetter ( $user ['student_name'] );
            // 如果包含中文将中文翻译成拼音
            if (preg_match ( '/[\x7f-\xff]+/', $user ['student_name'] )) {
                // 昵称和呢称拼音保存到搜索字段
                $user ['search_key'] = $user ['student_name'] . ' ' . model ( 'PinYin' )->Pinyin ( $user ['student_name'] );
            } else {
                $user ['search_key'] = $user ['student_name'];
            }
            $countstu = $this->getCount($user['school_info_id']);
            $user['student_id'] = '1717'.$countstu;
            // 添加用户操作
            $result = $this->add ( $user );
            if (! $result) {
                return false;
            } else
                return true;
        }else {
            return false;
        }

    }

    public function getCount($school_info_id) {

        if($school_info_id) {
            $map['school_info_id'] = $school_info_id;
            $count = $this->where($map)->count();
            return $count+1;
        }
        return 1;
    }



}
