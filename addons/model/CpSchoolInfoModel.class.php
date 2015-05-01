<?php
/**
 *      年级列表
 *
 */
class CpSchoolInfoModel extends Model {

	protected $tableName = 'cp_school_info';
	protected $fields =	array (0=>'school_info_id',1=>'school_id',2=>'grade_id',3=>'class_id',4=>'dec');
		

    /**
     * 获取年级列表，后台可以根据用户组查询
     *
     * @param integer $limit
     *        	结果集数目，默认为20
     * @return array 用户列表信息
     */
    public function getOneOrSave($schoolInfo,$isSave = false) {

        $select = $this->where($schoolInfo)->find();
        if(empty($select) && $isSave) {
            $select = $this->add($schoolInfo);
        }

        return $select;
    }




}
