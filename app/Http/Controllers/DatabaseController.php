<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ConferenceRegister;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\Department;

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
                    array_push($errors, $e);
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
            $requests = BookRequest::acceptedButNotSent()
                                ->select('receiver', 'address', 'phone')
                                ->groupBy('receiver', 'address', 'phone')
                                ->get()->toArray();
        }
        else if($ar == "DEPTADMIN"){
            $requests = BookRequest::ofDepartmentCode(PM::getAdminDepartmentCode())
                                ->acceptedButNotSent()
                                ->select('receiver', 'address', 'phone')
                                ->groupBy('receiver', 'address', 'phone')
                                ->get()->toArray();
        }

        if(count($requests) == 0){
            Session::flash('warning', '没有需要导出的样书申请信息');
            return redirect()->route("admin.bookreq.index");
        }

        for($i = 0; $i < count($requests); $i++){
            $requests[$i] = (array)$requests[$i];
        }

        $filename = date("Y-m-d")."快递打印单_".time();
        $export = Excel::create($filename, function($excel) use ($requests){
            $excel->sheet("快递信息", function($sheet) use ($requests){
                $sheet->setAutoSize(true);
                $sheet->row(1, ["收件人", "地址", "联系电话"]);
                foreach($requests as $request){
                    $sheet->appendRow([
                            $request["receiver"],
                            $request["address"],
                            $request["phone"]
                        ]);
                }
            });
        })->store('xlsx')->export('xlsx');
        //->download("xlsx");
        
        return redirect()->route("admin.bookreq.index");
    }

    public function exportBookRequestBookTable(){
        $ar = PM::getAdminRole();
        if($ar == "SUPERADMIN"){
            $books = BookRequest::acceptedButNotSent()
                                ->leftJoin('book', 'book.id', '=', 'book_id')
                                ->select('book.id', 'book.isbn as isbn', 'book.name as name', 'book.price as price', 'receiver')
                                ->get()
                                ->toArray();
        }
        else if($ar == "DEPTADMIN"){
            $books = BookRequest::acceptedButNotSent()
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

}
