<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 22/02/2017
 * Time: 9:36 AM
 */

namespace App\Libraries;

use App\Models\Book;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionManager
{

	/**
	 * @param $permission_string string 权限字符串, 形如 BOOK_CURD_803
	 */
	static public function resolve($permission_string){
		$permission_string = trim(strtolower($permission_string));

		$isSuperAdmin = 0;
		if($permission_string == "all"){
			$isSuperAdmin = 1;
		}

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
		$permissions["super_admin"] = $isSuperAdmin;

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

//	static public function hasDepartmentPermission($request,$department_id){
//		if(Auth::check()){
//
//			//超级管理员
//			if($request->session()->get('permission')['super_admin']){
//				return true;
//			}
//
//			$departments = [];
//			$departments_from_permission = array_keys($request->session()->get('permission')['department']);
//
//			foreach($departments_from_permission as $id){
//
//				$department = Department::where('id',$id)->first();
//				$sub_departments = Department::where('code','like',$department->code."%")->get();
//				foreach ($sub_departments as $sub_department){
//					array_push($departments,$sub_department->id);
//				}
//			}
//
//			if(in_array($department_id,$departments)){
//				return true;
//			}
//
//		}
//		return false;
//	}

	// 获取登录用户管辖的编辑室(type=3),返回code数组
	static public function getAuthorizedDepartments($type,$department_code=""){
		if (self::isSuperAdmin()) {
			return Department::where('type', $type)->get();
		}
		return [];
	}

	// 获取登录用户管辖的省、直辖市、自治州,返回id数组
	static public function getAuthorizedProvinces(){}

	static public function hasPermission($entity,$operation="",$entity_id = null){
		switch ($entity){
			case 'book':
				return self::hasBookPermission($operation,$entity_id);
			case 'bookreq':
				return self::hasBookReqPermission($operation,$entity_id);
			case 'user':
				return self::hasUserPermission($operation);
			case 'department':
				return self::hasDepartmentPermission($operation,$entity_id);
			default:
				break;
		}
		return true;
	}

	// 判断登录用户是否对某本书有操作权限。
	static private function hasBookPermission($operation,$book_id){
		switch (self::getAdminIdentity()) {
			case 'SUPER_ADMIN':
				return true;
			case 'DEPARTMENT_ADMIN':
				return true;
			case 'REPRESENTATIVE':
				if(!empty($operation)){
					$book = Book::where('id',$book_id)->first();
					$district_id = $book->district_id;

					return false;
				}
				return true;
			default:
				break;
		}
		return false;
	}

	static private function hasBookReqPermission($operation,$bookreq_id){
		switch (self::getAdminIdentity()) {
			case 'SUPER_ADMIN':
				return true;
			case 'DEPARTMENT_ADMIN':
				return true;
			case 'REPRESENTATIVE':
				if(!empty($operation) && strstr('cud',$operation)){
					return false;
				}
				return true;
			default:
				break;
		}
		return false;
	}

	static private function hasDepartmentPermission($operation,$department_code){
		switch (self::getAdminIdentity()) {
			case 'SUPER_ADMIN':
				return true;
			default:
				break;
		}
		return false;
	}

	static private function hasUserPermission($operation){
		if(self::isSuperAdmin())
			return true;
		return false;
	}

	//判断登录用户是否是超级用户
	static public function isSuperAdmin(){
		if(!empty(session('permission'))){
			if(session('permission')['super_admin'] == 1)
				return 1;
		}
		return 0;
	}

	//获取登录用户的身份: SUPER_ADMIN|DEPARTMENT_ADMIN|REPRESENTATIVE
	static public function getAdminIdentity(){
		if (self::isSuperAdmin()){
			return 'SUPER_ADMIN';
		}
		$permission = session('permission');
		if(in_array('book',$permission)){
			if(!empty($permission['book']['department']))
				return 'DEPARTMENT_ADMIN';
			if(!empty($permission['book']['district']))
				return 'REPRESENTATIVE';
		}
		return 'UNKNOW';
	}

	static public function getAdminModules(){
		$modules = [];
		switch (self::getAdminIdentity()){
			case 'SUPER_ADMIN':
				$modules = ['BOOK','DEPARTMENT','USER','BOOKREQ'];
				break;
			case 'DEPARTMENT_ADMIN':
				$modules = ['BOOK','BOOKREQ'];
				break;
			case 'REPRESENTATIVE':
				$modules = ['BOOK','BOOKREQ'];
				break;
			default:
				break;
		}
		return $modules;
	}

}