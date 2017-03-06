<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConferenceRegister;
use Excel;
use URL;
use DB;
use PDO;

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

}
