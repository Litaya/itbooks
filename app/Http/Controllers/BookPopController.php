<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookPop;
use App\Helpers\CrossDomainHelper;

class BookPopController extends Controller
{
    public function BookPop(Request $request)
    {
      $name=$request->dropdownOfPop;
      if($name==1)
        $books=BookPop::searchSale()->paginate(10);
      else
      {
        $books=BookPop::searchFav()->paginate(10);
        $name=0;
      }
      self::grabImages($books);
      return view("book_pop.show")->withBooks($books)->withName($name);
    }
    private function grabImages(&$books){
  		for($i=0;$i<count($books);$i++) {
  			if(empty($books[$i]->img_upload)){
  				$books[$i]->img_upload = asset('test_images/book_empty.jpg');
  				$imurl = "http://www.tup.com.cn/upload/bigbookimg/".$books[$i]->product_number.".jpg";
  				if(CrossDomainHelper::url_exists($imurl, $imurl)){ $books[$i]->img_upload = $imurl; $info_changed = true; }
  			}
  		}
  	}
}
