<?php
/**
 *      年级列表
 *
 */
class CpClassModel extends Model {

	protected $tableName = 'cp_class';
	protected $fields =	array (0=>'class_id',1=>'class_name',2=>'class_sort');
		

    /**
     * 获取年级列表，后台可以根据用户组查询
     *
     * @param integer $limit
     *        	结果集数目，默认为20
     * @return array 用户列表信息
     */
    public function getClassList($limit = 12) {

        // 查询数据
        $list = $this->findPage ( $limit );

        return $list['data'];
    }



}
