<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/1/26
 * Time: 下午1:13
 */

namespace App\Helpers\ExportInvoice;

use App\Dao\DepartmentDao;
use App\Libraries\PermissionManager;
use App\Models\Admin;
use App\Models\BookRequest;
use App\Models\Department;
use App\Models\User;

use Auth;
use Excel;
use Illuminate\Support\Facades\Log;
use Session;

class ExportInvoiceOrderByDepartmentHelper extends ExportInvoiceHelper{
	public function constructExportRecords(User $user)
	{
		$records = BookRequest::leftJoin('book','book.id','=','book_id')
			->where('status',0)->select(
				'book.isbn as isbn',
				'book.price as price',
				'book.name as book_name',
				'receiver',
				'phone',
				'book.department_id as department_id',
				'address')->orderBy('receiver')->orderBy('phone')->get();
		if(count($records) == 0){
			return [];
		}

		// 根据用户权限过滤记录
		if(PermissionManager::getAdminRole() != 'SUPERADMIN'){

			$user  = Auth::user();
			$admin = Admin::where('user_id',$user->id)->get();
			if(count($admin) == 0){
				Session::flash('warning','用户角色错误');
				return redirect()->route('admin.bookreq.index');
			}
			$admin = $admin[0];
			$department = Department::where('id',$admin->department_id)->first();
			$code  = $department->code;
			$departments = Department::where('code','like',"$code%")->get();
			$department_ids = array();
			foreach ($departments as $department){
				array_push($department_ids,$department->id);
			}
			$records_filtered = array();
			foreach ($records as $record){
				if(in_array($record->department_id, $department_ids)){
					array_push($records_filtered,$record);
				}
			}
		}else{
			# 如果用户角色为管理员，将records按照公司与部门分开。
			$records_filtered = $this->RecordsFilter($records);
		}
		return $records_filtered;
	}

	public function exportRecords($records)
	{
		$filename = date('Y-m-d').'_发行单_'.time();
		$export = Excel::create($filename, function ($excel) use ($records){
			$excel->sheet('发行单',function ($sheet) use ($records){
				$sheet->setAutoSize(true);
				$sheet->row(1,["ISBN","定价","数量","书名","分社","姓名","电话","地址"]);
				$current = ['receiver'=>'','phone'=> '','address' => ''];
				foreach ($records as $record) {
					# 将分社与公司用空行分开
					if ($record == 'separate'){
						$sheet->appendRow([
							" ", " ", " "," "," "," "," "," "
						]);
						$current = ['receiver'=>'','phone'=> '','address' => ''];
						continue;
					}
					# 如果department为空，则department_name为空
					$department = Department::where('id',$record->department_id)->first();
					$department_name = '';
					if ($department != null){
						$subDept         = DepartmentDao::getSubDepartment($department);
						$department_name = $subDept->name;
					}
					# 同一个receiver，只在第一行显示
					$receiver   = $record->receiver;
					$phone      = $record->phone;
					$address    = $record->address;
					if ($receiver == $current['receiver'] && $phone == $current['phone'] && $address == $current['address']){
						$receiver = '';
						$phone    = '';
						$address  = '';
					}else{
						$current['receiver'] = $receiver;
						$current['phone']    = $phone;
						$current['address']  = $address;
					}
					$sheet->appendRow([
						$record->isbn." ",
						$record->price,
						1,
						$record->book_name,
						$department_name,
						$receiver,
						$phone,
						$address
					]);
				}
				$sheet->setColumnFormat(array(
					'A' => '@',
					'B' => '0.00',
					'C' => '0',
					'D' => '@',
					'E' => '@',
					'F' => '@',
					'G' => '@',
					'H' => '@',
				));
			});
		})->store('xlsx')->export('xlsx');
	}

	/**
	 * 将记录按照department为公司与部门分开，code第一个字为8的是公司，其他的是部门
	 * @param $records
	 * @return array
	 */
	private function RecordsFilter($records){
		$records_department = array();
		$records_company    = array();
		foreach ($records as $record){
			$department = Department::where('id',$record->department_id)->first();
			$dept_code  = $department->code;
			if (substr($dept_code,0,1)== '8'){
				array_push($records_company,$record);
			}else{
				array_push($records_department,$record);
			}
		}
		array_push($records_department,'separate');
		return array_merge($records_department,$records_company);
	}
}