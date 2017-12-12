<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/12/12
 * Time: 上午11:36
 */

namespace App\Dao;

use App\Models\Department;

class DepartmentDao{

	/**
	 * 获取分社，类别为公司则获取第二级分类即公司名
	 */
	public static function getSubDepartment(Department $department){
		$code = $department->code;
		if(substr($code,0,1) == 8){
			return Department::where('code',substr($code,0,3))->first();
		}
		return Department::where('code',substr($code,0,1))->first();
	}
}