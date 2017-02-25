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
		$departments = Department::where('type',1)->get();

		Log::info(Department::whereIn('id',array_keys($request->session()->get('permission')['department']))->first());
		$valid_departments =Department::whereIn('id',array_keys($request->session()->get('permission')['department']))->get();

//		Log::info($valid_departments);
//		foreach($departments as $department){
//			if(PermissionManager::hasDepartmentPermission($request,$department->id)){
//				array_push($valid_departments,$department);
//			}
//		}
		return view('admin.department.index',['departments'=>$valid_departments]);
//		return view('admin.department.index',compact('departments'));
	}

	public function showDepartment(Request $request,$department_id){

		PermissionManager::hasDepartmentPermission($request,$department_id);

		$department = Department::where('id',$department_id)->first();
		$offices    = Department::where('code','like',"$department->code%")->whereIn('type',[2,3])->get();

		return view('admin.department.show',compact('department','offices'));
	}

	public function createDepartment(Request $request){

		$request->session()->flash('notice_message','添加失败');
		$request->session()->flash('notice_status','danger');

		if($request->has('office-code')){
			$this->validate($request,[
				'department-id' => 'required',
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

			if (PermissionManager::hasDepartmentPermission($request,$request->get('department-id'))){
				Department::create([
					'code' => $request->get('office-code'),
					'name' => $request->get('office-name'),
					'type' => $type
				]);
				$request->session()->flash('notice_message','添加成功');
				$request->session()->flash('notice_status','success');
			}
		}
		return redirect()->route('admin.department.show',['id'=>$request->get('department-id')]);
	}

	public function updateDepartment(Request $request, $department_id){

		$this->validate($request,[
			'department-code'=>'required',
			'department-name'=>'required'
		]);

		$request->session()->flash("notice_message","操作失败");
		$request->session()->flash("notice_status",'danger');

		$department = Department::where('id',$department_id)->first();
		$department['code'] = $request->get('department-code');
		$department['name'] = $request->get('department-name');
		$result = $department->save();
		if($result){
			$request->session()->flash("notice_message","修改成功!");
			$request->session()->flash("notice_status",'success');
			return redirect()->route('admin.department.show',['id'=>$department_id]);
		}else{
			return redirect()->route('admin.index');
		}

	}

	public function deleteOffice(Request $request,$department_id){
		$request->session()->flash("notice_message","删除失败");
		$request->session()->flash("notice_status",'danger');

		if($request->has('office-id')){
			$office_id  = $request->get('office-id');
			Department::where('id',$office_id)->delete();
			$request->session()->flash("notice_message","成功删除$office_id");
			$request->session()->flash("notice_notice_status",'success');
		}
		return redirect()->route('admin.department.show',['id'=>$department_id]);
	}
}
