<?php
/**
 *      年级列表
 *
 */
class CpPeriodModel extends Model {

	protected $tableName = 'cp_period';
	protected $fields =	array (0=>'period_id',1=>'period_name',2=>'startyear',3=>'endyear',4=>'syear_code');
		
	/**
     * 获取用户列表，后台可以根据用户组查询
     * @param integer $limit
     *        	结果集数目，默认为2
     * @return array xu列表信息
     */
    public function getPeriodList($limit = 2) {
        // 查询数据
        $list = $this->where ( null )->findPage ( $limit );
        return $list['data'];
    }
}
