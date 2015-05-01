<?php
/**
 *      年级列表
 *
 */
class CpSubjectModel extends Model {

	protected $tableName = 'cp_subject';
	protected $fields =	array (0=>'id',1=>'subject_id',2=>'subject_name');
		

    /**
     * 获取年级列表，后台可以根据用户组查询
     *
     * @param integer $limit
     *        	结果集数目，默认为20
     * @return array 用户列表信息
     */
    public function getSubjectList($limit = 12) {

        // 查询数据
        $list = $this->findPage ( $limit );

        return $list['data'];
    }



}
