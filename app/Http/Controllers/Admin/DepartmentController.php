<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\PermissionManager;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
	public function index(Request $request){
		$departments = Department::where('type',1)->get();

		if($request->session()->has('status')){
			$message = $request->session()->get('message');
			$status  = $request->session()->get('status');
			return view('admin.department.show',compact('departments','message','status'));
		}else{
			return view('admin.department.index',compact('departments'));
		}

	}

	public function showDepartment(Request $request,$id){
		$department = Department::where('id',$id)->first();
		$offices    = Department::where('code','like',"$department->code%")->whereIn('type',[2,3])->get();

		if($request->session()->has('status')){
			$message = $request->session()->get('message');
			$status  = $request->session()->get('status');
			return view('admin.department.show',compact('department','offices','message','status'));
		}else{
			return view('admin.department.show',compact('department','offices'));
		}
	}

	public function createDepartment(Request $request){

		$request->session()->flash('message','添加失败');
		$request->session()->flash('status','danger');

		if($request->has('office-code')){
			$this->validate($request,[
				'department-id' => 'required',
				'office-code'=>'required|string',
				'office-name'=>'required|string',
				'department-type' => 'required'
			]);
			if (PermissionManager::hasDepartmentPermission($request,$request->get('department-id'))){
				Department::create([
					'code' => $request->get('office-code'),
					'name' => $request->get('office-name'),
					'type' => $request->get('department-type')
				]);
				$request->session()->flash('message','添加成功');
				$request->session()->flash('status','success');
			}
		}
		return redirect()->route('admin.department.show',['id'=>$request->get('department-id')]);
	}

	public function updateDepartment(Request $request, $department_id){

		$this->validate($request,[
			'department-code'=>'required',
			'department-name'=>'required'
		]);

		$request->session()->flash("message","操作失败");
		$request->session()->flash("status",'danger');

		$department = Department::where('id',$department_id)->first();
		$department['code'] = $request->get('department-code');
		$department['name'] = $request->get('department-name');
		$result = $department->save();
		Log::info($result);
		if($result){
			$request->session()->flash("message","修改成功!");
			$request->session()->flash("status",'success');
			return redirect()->route('admin.department.show',['id'=>$department_id]);
		}else{
			return redirect()->route('admin.index');
		}

	}

	public function deleteOffice(Request $request,$department_id){
		$request->session()->flash("message","删除失败");
		$request->session()->flash("status",'danger');

		if($request->has('office-id')){
			$office_id  = $request->get('office-id');
			Department::where('id',$office_id)->delete();
			$request->session()->flash("message","成功删除$office_id");
			$request->session()->flash("status",'success');
		}


		return redirect()->route('admin.department.show',['id'=>$department_id]);
	}
}
