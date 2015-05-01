<?php
/**
 *      年级列表
 *
 */
class CpPackModel extends Model {

	protected $tableName = 'cp_pack';
	//protected $fields =	array (0=>'pack_id',1=>'pack_name',2=>'grade_sort');
    public function addPack($pack) {
        $pack['createdate'] = date("Y-m-d H:i:s" ,time());
        $pack['school_id'] = $pack['createdby'];
        $result = $this->add($pack);
        if (! $result) {
            return false;
        } else {
            return true;
        }
    }

    public function getPackList($uid ,$limit = 12, $order = "createdate DESC") {

        if(!empty($uid)){
            $map['createdby'] = $uid;
            // 查询数据

            $list = $this->where($map)->order ( $order )->findPage ( $limit );

            return $list['data'];
        }


    }

    public function getPackNameList($uid ,$limit = 12, $order = "createdate DESC") {

        if(!empty($uid)){
            $map['createdby'] = $uid;
            // 查询数据

            $result = M()->table('ts_cp_pack tcpk ,ts_cp_period tcpd ,ts_cp_grade tcgd')
                            ->field('tcpk.pack_id,tcpk.pack_name,tcpk.grade_id,tcgd.grade_name,tcpd.period_name')
                            ->where('tcpk.createdby="'.$uid.'" AND tcpk.grade_id = tcgd.grade_id AND tcpk.period_id = tcpd.period_id')
                            ->order('tcpk.'.$order)
                            ->select();
           // $list = $this->where($map)->order ( $order )->findPage ( $limit );

            return $result;
        }


    }

    public function getPackPage($uid, $limit = 8,$order = "createdate DESC") {

        if(!empty($uid)){
            $map['createdby'] = $uid;
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

    public function getPackByID($pack_id){
        if(!empty($pack_id)){
            $map['pack_id'] = $pack_id;
            // 查询数据
            $list = $this->where($map)->find();

            return $list;
        }
    }

    public function updatePack($pack) {
        if(is_array($pack)) {
            return $this->save($pack);
        }

        return false;
    }

}
