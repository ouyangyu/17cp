<?php
/**
 *      年级列表
 *
 */
class CpGradeModel extends Model {

	protected $tableName = 'cp_grade';
	protected $fields =	array (0=>'grade_id',1=>'grade_name',2=>'grade_sort');
		

    /**
     * 获取年级列表，后台可以根据用户组查询
     *
     * @param integer $limit
     *        	结果集数目，默认为20
     * @return array 用户列表信息
     */
    public function getGradeList($limit = 12, $order = "grade_sort DESC") {



        // 查询数据
        $list = $this->order ( $order )->findPage ( $limit );



        return $list['data'];
    }



}
