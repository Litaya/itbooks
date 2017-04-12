<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConferenceRegister;
use App\Models\BookRequest;
use Excel;
use URL;
use DB;
use PDO;
use Session;

use App\Helpers\CrossDomainHelper;

class DatabaseController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function importBooks(){

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
        $requests = BookRequest::acceptedButNotSent()
                               ->select('receiver', 'address', 'phone')
                               ->groupBy('receiver', 'address', 'phone')
                               ->get()->toArray();

        if(count($requests) == 0){
            Session::flash('warning', '没有需要导出的样书申请信息');
            return redirect()->route("admin.bookreq.index");
        }

        for($i = 0; $i < count($requests); $i++){
            $requests[$i] = (array)$requests[$i];
        }

        $filename = date("Y-m-d")."快递打印单_".time();
        return Excel::create($filename, function($excel) use ($requests){
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
        })->download("xlsx");
    }

    public function exportBookRequestBookTable(){
        $books = BookRequest::acceptedButNotSent()
                               ->leftJoin('book', 'book.id', '=', 'book_id')
                               ->select('book.id', 'book.isbn as isbn', 'book.name as name', 'book.price as price', 'receiver')
                               ->get()
                               ->toArray();

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

        // return $books['9787302348290'];

        $filename = date("Y-m-d")."库房发书单_".time();
        return Excel::create($filename, function($excel) use ($books){
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
        })->export("xlsx")->download("xlsx");
    }

}
