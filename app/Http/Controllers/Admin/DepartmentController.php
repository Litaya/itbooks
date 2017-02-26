<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\PermissionManager;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
	public function index(Request $request){
		$departments = PermissionManager::getAuthorizedDepartments(1);
		$orgnizations= []; //事业部
		$offices     = []; //编辑室
//		if(PermissionManager::isSuperAdmin()){
//			$departments = Department::where('type',1)->get();
//		}else{
//			$valid_departments =Department::whereIn('id',array_keys($request->session()->get('permission')['department']))->get();
//			foreach ($valid_departments as $department){
//				switch(Department::getDepartmentType($department->code)){
//					case 1:
//						array_push($departments,Department::where('code',$department->code)->first());
//						break;
//					case 2:
//						array_push($orgnizations,Department::where('code',$department->code)->first());
//						break;
//					case 3:
//						array_push($offices,Department::where('code',$department->code)->first());
//						break;
//					default:
//						break;
//				}
//			}
//		}
		return view('admin.department.index',compact('departments','orgnizations','offices'));
	}

	public function showDepartment(Request $request,$department_code){

		PermissionManager::hasPermission('department',"",$department_code);

		$department = Department::where('code',$department_code)->first();
		$offices    = Department::where('code','like',"$department_code%")->whereIn('type',[2,3])->get();

		return view('admin.department.show',compact('department','offices'));
	}

	public function createDepartment(Request $request){

		$request->session()->flash('notice_message','添加失败');
		$request->session()->flash('notice_status','danger');

		if(PermissionManager::hasPermission('department','c')){
			if($request->has('office-code')){
				$this->validate($request,[
					'department-code' => 'required',
					'office-code'=>'required|string',
					'office-name'=>'required|string',
				]);

				switch (strlen($request->get('office-code'))){
					case 1:
						$type = 1;
						break;
					case 3:
						$type = 2;
						break;
					case 5:
						$type = 3;
						break;
					default:
						$type = 0;
						break;
				}

				Department::create([
					'code' => $request->get('office-code'),
					'name' => $request->get('office-name'),
					'type' => $type
				]);
				$request->session()->flash('notice_message','添加成功');
				$request->session()->flash('notice_status','success');
			}

		}
		return redirect()->route('admin.department.show',['department_code'=>$request->get('department-code')]);
	}

	public function updateDepartment(Request $request, $department_code){

		$this->validate($request,[
			'department-code'=>'required',
			'department-name'=>'required'
		]);

		$request->session()->flash("notice_message","操作失败");
		$request->session()->flash("notice_status",'danger');

		if(PermissionManager::hasPermission('department','u',$department_code)){
			$department = Department::where('code',$department_code)->first();
			$department['code'] = $request->get('department-code');
			$department['name'] = $request->get('department-name');
			$result = $department->save();
			if($result){
				$request->session()->flash("notice_message","修改成功!");
				$request->session()->flash("notice_status",'success');
				return redirect()->route('admin.department.show',['id'=>$department_code]);
			}
		}
		return redirect()->route('admin.index');

	}

	public function deleteOffice(Request $request,$department_code){
		$request->session()->flash("notice_message","删除失败");
		$request->session()->flash("notice_status",'danger');

		if(PermissionManager::hasPermission('department','d',$department_code)){
			if($request->has('office-code')){
				$office_code  = $request->get('office-code');
				Department::where('code',$office_code)->delete();
				$request->session()->flash("notice_message","成功删除$office_code");
				$request->session()->flash("notice_notice_status",'success');
			}
		}
		return redirect()->route('admin.department.show',['id'=>$department_code]);
	}
}
