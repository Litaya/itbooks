<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ConferenceRegister;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\Department;
use App\Models\DownloadRecord;
use App\Models\User;

use App\Helpers\FileHelper;

use Input;
use Excel;
use URL;
use DB;
use PDO;
use Session;

use App\Helpers\CrossDomainHelper;

use App\Libraries\PermissionManager as PM;

class DatabaseController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function importBooks(Request $request){
        $this->validate($request, [
            "excel" => "required",
        ]);

        $key_map = [
            "isbn" => "isbn号",
            "name" => "书名",
            "price" => "定价",
            "department_id" => "编辑室",
            "product_number" => "产品编号",
            "editor_name" => "现负责人",
            "authors" => "作者",
            "type" => "图书类别",
            "publish_time" => "出版日期",
        ];

        $all_depts = Department::all();
        $dept_map = [];
        foreach($all_depts as $dept){
            $dept_map[(string)$dept->code] = $dept->id;
        }
        
        $all_books = Book::all();
        $isbn_set = [];
        foreach($all_books as $book){
            array_push($isbn_set, $book->isbn);
        }

        $file = Input::file('excel')->getRealPath();
        $results = Excel::load($file, function($reader){})->get();

        $n_total = count($results);
        $n_success = 0;
        $n_duplicate = 0;
        $errors = [];
        
        $i_row = 0;
        foreach($results as $row)
        {
            $i_row += 1;
            $book = [];
            
            foreach(array_keys($key_map) as $key){
                $book[$key] = $row[$key_map[$key]];
            }

            if(!array_key_exists($book['department_id'], $dept_map)){
                array_push($errors, "[ID:".$i_row."] 不存在的编辑室编号: ".$book['department_id']);
                continue;
            }
            
            $book['department_id'] = $dept_map[$book['department_id']];
            $book['type'] = ($book['type'] == "教材") ? 1 : 2;

            // check duplicate
            if(!in_array($book['isbn'], $isbn_set)){
                try{
                    DB::table("book")->insert($book);
                    $n_success += 1;
                } catch (\Exception $e) {
                    array_push($errors, $e->getMessage());
                }
            }
            else {
                // array_push($errors, "[ID:".$i_row."] 重复的书目: ".$book['isbn']);
                $n_duplicate += 1;
            }
        }

        $n_failure = $n_total - $n_success;

        // import log
        if(!is_dir(public_path('logs'))){
            mkdir(public_path('logs'), 0777);
        }
        $log_name = "logs/import_log_".time().".txt";
        $log_file = public_path($log_name);
        $log_handle = fopen($log_file, "w");
        if(count($errors) == 0){
            fwrite($log_handle, iconv("UTF-8", "GBK", "全部书目已经成功导入\r\n"));
        }
        foreach($errors as $error){
            fwrite($log_handle, "$error"."\r\n");
        }
        fclose($log_handle);

        Session::flash('success', "共$n_total 条记录，成功导入$n_success 条，重复$n_duplicate 条，失败$n_failure 条。");
        Session::flash('reportfile', asset("logs/import_log_".time().".txt"));
        return redirect()->route('admin.book.index');
    }

    public function testCheckUrl($product_number){
        $t;
        return var_dump(CrossDomainHelper::url_exists("http://www.tup.com.cn/upload/books/kj/".$product_number.".rar", $t)).'<br>'.$t;
    }

    public function exportConferenceRegisters($id){
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $crs = DB::select("select * from conference_register where conference_id = ?", [$id]);
        DB::setFetchMode(PDO::FETCH_CLASS);

        $exp = Excel::create("test", function($excel) use($crs) {

            $excel->sheet("会议报名", function($sheet) use($crs) {
                $sheet->setWidth([
                    'A' => 3,
                    'B' => 3,
                    'C' => 3,
                    'D' => 10,
                    'E' => 30,
                    'F' => 10,
                    'G' => 10,
                    'H' => 15,
                    'I' => 20,
                    'J' => 20,
                    'K' => 50,
                ]);
                $sheet->row(1, array_keys($crs[0]));
                foreach($crs as $cr) $sheet->rows(array(array_values($cr)));
            });
            
        });

        $exp->store('xlsx', public_path('excel/exports'));

        // return asset("excel/exports/text.xlsx");
        return response()->download(public_path('excel/exports/test.xlsx'));
    }

    public function exportBookRequestPackagingTable(){
        $ar = PM::getAdminRole();
        if($ar == "SUPERADMIN"){
            $requests = BookRequest::unhandled()
                                ->leftJoin('book', 'book.id', '=', 'book_request.book_id')
                                ->select('receiver', 'address', 'phone', 'book_request.created_at', 'book.name as bookname', 'book.isbn as isbn')
                                ->orderBy('book_request.created_at', 'desc')
                                ->get()->toArray();
        }
        else if($ar == "DEPTADMIN"){
            $requests = BookRequest::ofDepartmentCode(PM::getAdminDepartmentCode())
                                ->unhandled()
                                //->leftJoin('book', 'book.id', '=', 'book_request.book_id') //joined in scope
                                ->select('receiver', 'address', 'phone', 'book_request.created_at', 'book.name as bookname', 'book.isbn as isbn')
                                ->orderBy('book_request.created_at', 'desc')
                                ->get()->toArray();
        }

        if(count($requests) == 0){
            Session::flash('warning', '没有需要导出的样书申请信息');
            return redirect()->route("admin.bookreq.index");
        }


        $aggregate = [];
        
        for($i = 0; $i < count($requests); $i++){
            $requests[$i] = (array)$requests[$i];
            $key = $requests[$i]["receiver"] . "_@_" . $requests[$i]["address"] . "_@_" . $requests[$i]["phone"];
            $isbn = !empty($requests[$i]["isbn"]) ? $requests[$i]["isbn"] : "";
            if(strlen($isbn) > 6) $isbn = substr($isbn, strlen($isbn)-6, 6);
            $aggregate[$key][] = $requests[$i]["bookname"] . "(" . $isbn . ")";
        }

        $filename = date("Y-m-d")."快递打印单_".time();
        $export = Excel::create($filename, function($excel) use ($aggregate){
            $excel->sheet("快递信息", function($sheet) use ($aggregate){
                $sheet->setAutoSize(true);
                $sheet->row(1, ["收件人", "地址", "联系电话", "书名"]);
                foreach($aggregate as $key=>$booklist){
                    $rap = explode('_@_', $key);
                    array_push($rap, implode("\r\n", $booklist));
                    $sheet->appendRow($rap);
                }
            });
        })->store('xlsx')->export('xlsx');
        //->download("xlsx");
        
        return redirect()->route("admin.bookreq.index");
    }

    public function exportBookRequestBookTable(){
        $ar = PM::getAdminRole();
        if($ar == "SUPERADMIN"){
            $books = BookRequest::unhandled()
                                ->leftJoin('book', 'book.id', '=', 'book_id')
                                ->select('book.id', 'book.isbn as isbn', 'book.name as name', 'book.price as price', 'receiver')
                                ->get()
                                ->toArray();
        }
        else if($ar == "DEPTADMIN"){
            $books = BookRequest::unhandled()
                                ->leftJoin('book', 'book.id', '=', 'book_id')
                                ->leftJoin('department', 'department.id', '=', 'book.department_id')
					            ->whereRaw('department.code like \''.PM::getAdminDepartmentCode().'%\'')
                                ->select('book.id', 'book.isbn as isbn', 'book.name as name', 'book.price as price', 'receiver')
                                ->get()
                                ->toArray();
        }
        else{
            return redirect()->route("admin.index");
        }

        if(count($books) == 0){
            Session::flash('warning', '没有需要导出的样书申请信息');
            return redirect()->route("admin.bookreq.index");
        }
        for($i = 0; $i < count($books); $i++){
            $books[$i] = (array)$books[$i];
        }


        $dict = [];
        foreach($books as $item){
            $key = $item["isbn"];
            if(!array_key_exists($key, $dict)){
                $dict[$key] = [
                    "isbn" => $item["isbn"],
                    "name" => $item["name"],
                    "book_count" => 1,
                    "price" => $item["price"],
                    "receivers" => [$item["receiver"]]
                ];
            }
            else{
                if(!in_array($item["receiver"], $dict[$key]["receivers"])){
                    array_push($dict[$key]["receivers"], $item["receiver"]);
                    $dict[$key]["book_count"] += 1;
                }
            }
        }

        $books = $dict;

        $filename = date("Y-m-d")."库房发书单_".time();
        $export = Excel::create($filename, function($excel) use ($books){
            $excel->sheet("书目", function($sheet) use ($books){

                $sheet->setAutoSize(true);

                $sheet->mergeCells('A1:E1');
                $sheet->cells('A1:E1', function($cells) { $cells->setAlignment('center'); });
                $sheet->mergeCells('A2:E2');
                $sheet->cells('A2:E2', function($cells) { $cells->setAlignment('right'); });
                
                $sheet->row(1, ["社内领书结算单"]);
                $sheet->row(2, [date("Y年m月d日")]);
                $sheet->row(3, ["书代号", "书名", "数量", "定价", "赠书对象"]);
                foreach($books as $book){
                    $sheet->appendRow([
                            $book["isbn"]." ",
                            $book["name"],
                            $book["book_count"],
                            $book["price"],
                            implode(" ", $book["receivers"])
                        ]);
                }

                $sheet->setColumnFormat(array(
                    'A' => '@',
                    'B' => '@',
                    'C' => '0',
                    'D' => '0.00',
                    'E' => '@',
                ));
            });
        })->store('xlsx')->export('xlsx');
        //->download('xlsx');

        return redirect()->route("admin.bookreq.index");
    }

    public function exportDownloadRecord(){
        $ar = PM::getAdminRole();
        if($ar == "SUPERADMIN"){
            $records = DownloadRecord::leftJoin('book', 'book.id', '=', 'book_id')
                                ->leftJoin('user', 'user.id', '=', 'user_id')
                                ->leftJoin('user_info', 'user_info.user_id', '=', 'user.id')
                                ->select("download_record.user_id as user_id", 
                                         "download_record.book_id as book_id", 
                                         "book.name as bookname", 
                                         "book.isbn as isbn", 
                                         "user_info.realname as realname", 
                                         "user_info.phone as phone", 
                                         "user.email as email",
                                         "user.username as username",
                                         "user_info.workplace as workplace")
                                ->get();
        }
        else if($ar == "DEPTADMIN"){
            $records = DownloadRecord::leftJoin('book', 'book.id', '=', 'book_id')
                                ->leftJoin('department', 'department.id', '=', 'book.department_id')
                                ->leftJoin('user', 'user.id', '=', 'user_id')
                                ->leftJoin('user_info', 'user_info.user_id', '=', 'user.id')
					            ->whereRaw('department.code like \''.PM::getAdminDepartmentCode().'%\'')
                                ->select("download_record.user_id as user_id",
                                         "download_record.book_id as book_id",
                                         "book.name as bookname", 
                                         "book.isbn as isbn", 
                                         "user_info.realname as realname", 
                                         "user_info.phone as phone", 
                                         "user.email as email",
                                         "user.username as username",
                                         "user_info.workplace as workplace")
                                ->get();
        }
        else{
            return redirect()->route("admin.index");
        }

        if(count($records) == 0){
            Session::flash('warning', '没有需要导出的下载信息');
            return redirect()->route("admin.resource.index");
        }

        $filename = date("Y-m-d")."_下载记录_".time();
        $export = Excel::create($filename, function($excel) use ($records){
            $excel->sheet("下载记录", function($sheet) use ($records){

                $sheet->setAutoSize(true);
                $sheet->row(1, ["用户名", "真实姓名", "邮箱", "学校", "书代号", "书名"]);
                foreach($records as $record){
                    $sheet->appendRow([
                            $record->username,
                            $record->realname,
                            $record->email,
                            $record->workplace,
                            $record->isbn . " ",
                            $record->bookname,
                        ]);
                }

                $sheet->setColumnFormat(array(
                    'A' => '@',
                    'B' => '@',
                    'C' => '0',
                    'D' => '0.00',
                    'E' => '@',
                ));
            });
        })->store('xlsx')->export('xlsx');

        return redirect()->route("admin.resource.index");
    }

    public function exportAllTeachers(){
        $ar = PM::getAdminRole();
        if($ar == "SUPERADMIN"){
            $records = DB::select("select user.username as username, user.email as email, user_info.realname as realname,
                        d1.name as province, d2.name as city, user_info.address as address, user_info.workplace as workplace,
                        user.json_content as ujson, user_info.json_content as ijson,
                        user.created_at as created_at
                        from user
                        left join user_info on user.id = user_info.user_id
                        left join district as d1 on user_info.province_id = d1.id
                        left join district as d2 on user_info.city_id = d2.id
                        where user.certificate_as = 'TEACHER';");
        }
        else{
            return redirect()->route("admin.index");
        }

        if(count($records) == 0){
            Session::flash('warning', '没有需要导出的教师信息');
            return redirect()->route("admin.user.index");
        }

        $filename = date("Y-m-d")."_教师信息_".time();
        $export = Excel::create($filename, function($excel) use ($records){
            $excel->sheet("教师信息", function($sheet) use ($records){

                $sheet->setAutoSize(true);
                $sheet->row(1, [
                    "用户名", "真实姓名", "邮箱", "申请余量", "省", "市",
                    "地址", "学校名称", "院系名称", 
                    "职务", "职称", 
                    "教授课程1", "学生人数1", "教授课程2", "学生人数2", "教授课程3", "学生人数3"]);

                foreach($records as $record){
                    $ujson = json_decode($record["ujson"]);
                    $ijson = json_decode($record["ijson"]);
                    $sheet->appendRow([
                            !empty($record["username"]) ?   $record["username"] : "",
                            !empty($record["realname"]) ?   $record["realname"] : "",
                            !empty($record["email"]) ?      $record["email"] : "",
                            empty($ujson->teacher) ? "" : (empty($ujson->teacher->book_limit) ? "" : $ujson->teacher->book_limit),
                            !empty($record["province"]) ?   $record["province"] : "",
                            !empty($record["city"]) ?       $record["city"] : "",
                            !empty($record["address"]) ?    $record["address"] : "",
                            !empty($record["workplace"]) ?  $record["workplace"] : "",
                            !empty($ijson->department) ?    $ijson->department : "",
                            !empty($ijson->position) ?      $ijson->position : "",
                            !empty($ijson->jobtitle) ?      $ijson->jobtitle : "",
                            !empty($ijson->course_name_1) ? $ijson->course_name_1 : "",
                            !empty($ijson->number_stud_1) ? $ijson->number_stud_1 : "",
                            !empty($ijson->course_name_2) ? $ijson->course_name_2 : "",
                            !empty($ijson->number_stud_2) ? $ijson->number_stud_2 : "",
                            !empty($ijson->course_name_3) ? $ijson->course_name_3 : "",
                            !empty($ijson->number_stud_3) ? $ijson->number_stud_3 : "",
                        ]);
                }
            });
        })->store('xlsx')->export('xlsx');

        return redirect()->route("admin.user.index");
    }

	public function exportAllBookRequest(Request $request){
		$ar = PM::getAdminRole();
		if($ar == 'SUPERADMIN'){
			$book_requests = BookRequest::unhandled()
				->leftJoin('book', 'book.id', '=', 'book_id')
				->leftJoin('user_info', 'user_info.user_id', '=', 'user_id')
				->leftJoin('user', 'user.id', '=', 'user_id')
				->select('book.isbn as isbn','book.name as bookname','book.price as bookprice',
					'user_info.realname as realname','user_info.workplace as workplace' ,
					'user.email as email','status','message','phone', 'reciever','order_number');
			$filename = date("Y-m-d")."样书申请单_".time();
			$export = Excel::create($filename, function($excel) use ($book_requests){
				$excel->sheet("样书申请单", function($sheet) use ($book_requests){

					$sheet->setAutoSize(true);

					$sheet->row(1, ["书代号", "书名", "定价","申请人", "工作单位", "常用邮箱", "申请状态",
						"收件人",'联系方式', "运单号",'教材使用情况','备注']);
					foreach($book_requests as $book_request){
						$sheet->appendRow([
							$book_request["isbn"]." ",
							$book_request["bookname"],
							$book_request["bookprice"],
							$book_request["realname"],
							$book_request["workplace"],
							$book_request['email'],
							$book_request['status'] == 1?"申请成功":"申请失败",
							$book_request['reciever'],
							$book_request['phone'],
							$book_request['order_number'],
							json_decode($book_request[''],true)['book_plan'],
							json_decode($book_request[''],true)['remarks'],

							implode(" ", $book_request["receiver"])
						]);
					}

					$sheet->setColumnFormat(array(
						'A' => '@',
						'B' => '@',
						'C' => '0.00',
						'D' => '@',
						'E' => '@',
						'F' => '@',
						'G' => '0',
						'H' => '@',
						'I' => '@',
						'J' => '@',
						'K' => '@',
						'L' => '@',

					));
				});
			})->store('xlsx')->export('xlsx');
		}else{
			$request->session()->flash('notice_message','您没有操作权限！');
			$request->session()->flash('notice_status','danger');
		}
	}

}
