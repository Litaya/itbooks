<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 22/02/2017
 * Time: 9:36 AM
 */

namespace App\Libraries;


class PermissionManager
{

	/**
	 * @param $permission_string string 权限字符串, 形如 BOOK_CURD_803
	 */
	static public function resolve($permission_string){
		$permission_string = trim(strtolower($permission_string));
		$permissions = array();
		$book_department_permissions = array();
		$book_district_permissions = array();
		$department_permissions = array();
		$user_permission = array();
		$permission_array = explode("|",$permission_string);
		foreach ($permission_array as $permission){
			$permission_info = explode('_',$permission);

			if($permission_info[0] == 'book'){

				// 获取curd权限
				$curd_permission = self::curdPermission($permission_info[1]);

				// 权限按照department划分
				if($permission_info[2][0] == "d"){
					$department_id = intval(substr($permission_info[2],1,strlen($permission_info[2]-1)));
					$book_department_permissions[$department_id] = $curd_permission;
				}else if($permission_info[2][0] == "p"){
					$district_id = intval(substr($permission_info[2],1,strlen($permission_info[2]-1)));
					array_push($book_district_permissions,$district_id);
				}
			}

			if($permission_info[0] == 'department'){
				$curd_permission = self::curdPermission($permission_info[1]);
				$department_id = intval($permission_info[2]);
				$department_permissions[$department_id] = $curd_permission;
			}

			if($permission_info[0] == "user"){
				$curd_permission = self::curdPermission($permission_info[1]);
				$user_permission = $curd_permission;
			}

		}
		$permissions["book"]["department"] = $book_department_permissions;
		$permissions["book"]["district"] = $book_district_permissions;
		$permissions["department"] = $department_permissions;
		$permissions["user"] = $user_permission;

		return $permissions;
	}

	static private function curdPermission($curd){
		$curd_permission = array();
		//获取curd权限
		if(strstr($curd,'c')){
			$curd_permission['c']=1;
		}else{
			$curd_permission['c']=0;
		}

		if(strstr($curd,'u')){
			$curd_permission['u']=1;
		}else{
			$curd_permission['u']=0;
		}

		if(strstr($curd,'r')){
			$curd_permission['r']=1;
		}else{
			$curd_permission['r']=0;
		}

		if(strstr($curd,'d')){
			$curd_permission['d']=1;
		}else{
			$curd_permission['d']=0;
		}
		return $curd_permission;
	}
}