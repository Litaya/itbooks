<?php

namespace App\Http\Controllers;

use App\Libraries\WechatMessageSender;
use App\Models\Courseware;
use App\Models\UserInfo;
use App\Models\Wechat;
use App\Models\DownloadRecord;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Helpers\CrossDomainHelper;
use App\Helpers\RecommendHelper;
use Auth;
use Session;
use DB;

class BookController extends Controller
{

	public function index(Request $request)
	{

		if(empty($request->search)){
			$top_books = RecommendHelper::getManuallySetTopBook();
			$books_recommend = RecommendHelper::getBookRecommend(Auth::user());
			$hot_books = RecommendHelper::getHotBooks();
			$new_books = RecommendHelper::getNewBooks();

			self::grabImages($top_books);
			self::grabImages($books_recommend);
			self::grabImages($hot_books);
			self::grabImages($new_books);

			return  view("book.index")
				->withTopbooks($top_books)
				->withBooksrecommend($books_recommend)
				->withHotbooks($hot_books)
				->withNewbooks($new_books);
		}

		else{
			$search = $request->search;
			$books = Book::search($search)->paginate(10);
			return view("book.search")->withBooks($books);
		}
	}

	// GET
	public function show($id){
		$book = Book::find($id);

		$info_changed = false;

		if(empty($book->img_upload)){
			$imurl = "http://www.tup.com.cn/upload/bigbookimg/".$book->product_number.".jpg";
			if(CrossDomainHelper::url_exists($imurl, $imurl)){ $book->img_upload = $imurl; $info_changed = true; }
		}

		if($book->type==1 && empty($book->kj_url)){
			$kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar",
				"http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
			foreach($kj_url_list as $kj_url)
				if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; $info_changed = true; break; }
		}
		// if($info_changed) $book->update(); // got error 'Driver [mysql] is not supported'

		if(!empty($book->kj_url)) $book->kj_url = route("navigate", ["url"=>$book->kj_url]);

		$like = NULL; $read = NULL;
		if(Auth::check()) {
			$like = DB::select('select id from user_like_book where user_id = ? and book_id = ?', [Auth::id(), $book->id]);
			$like = count($like) > 0;
			$read = DB::select('select id from user_read_book where user_id = ? and book_id = ?', [Auth::id(), $book->id]);
			$read = count($read) > 0;
		}

		$similar = RecommendHelper::getSimilarBooks($book, 5);

		$wechat_app = Wechat::getInstance()->getApp();
		$wechat_js  = $wechat_app->js;

		$book_urls = [
			"index" => "http://www.tup.tsinghua.edu.cn/booksCenter/book_" . str_replace("-", "", $book->product_number) .".html",
			"preface" => "http://www.tup.com.cn/booksCenter/preface.html?id=" . str_replace("-", "", $book->product_number),
			"intro" => "http://www.tup.com.cn/booksCenter/bookbrief.html?id=" . str_replace("-", "", $book->product_number),
			"catalog" => "http://www.tup.com.cn/booksCenter/bookcatalog.html?id=" . str_replace("-", "", $book->product_number)
		];


		return view("book.show", ["book"=>$book, "userlike"=>$like, "userread"=>$read, "similar_books"=>$similar, "book_urls"=>$book_urls, 'wechat_js'=>$wechat_js]);
	}

	/*
	 * for api
	 */
	public function getBooksBySearch(Request $request,$search_string){
		$books = Book::where('type',1)->where(function($query){
			$query->where('isbn','like',"%$search_string%")
				->orWhere('name','like',"%$search_string%")
				->orWhere("authors","like","%$search_string%");
		})->paginate(5);
		return \GuzzleHttp\json_encode($books);
	}

	public function getTeachingBookBySearch(Request $request,$search_string){
		$books = Book::where('type',1)->where(function($query){
			$query->where('isbn','like',"%$search_string%")
				->orWhere('name','like',"%$search_string%")
				->orWhere("authors","like","%$search_string%");
		})->paginate(5);
		return \GuzzleHttp\json_encode($books);
	}

	/*
	 * for api
	 */
	public function getTeachingMaterialsBySearch(Request $request,$search_string){
		$books = Book::whereRaw("type = 1 and ((isbn like '%$search_string%') or (name like '%$search_string%') or (authors like '%$search_string%'))")
			->paginate(5);
		return \GuzzleHttp\json_encode($books);
	}

	public function updateKejian($id){
		if(!Auth::check()){
			return 'denied';
		}

		$book = Book::find($id);
		$kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar",
			"http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
		$real_url = null;
		$old_url = $book->kj_url;
		foreach($kj_url_list as $kj_url) if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; break; }
		if($book->kj_url != $old_url) {
			$book->update();
			Session::flash('success', '找到并更新了配套课件');
		}
		elseif($real_url === null)
			Session::flash('warning', '社网上找不到本书的配套课件');
		elseif($real_url == $old_url)
			Session::flash('warning', '当前课件链接已经是最新的');

		return 'success';
	}

	// getSearch
	public function search(Request $request){
		$search = $request->search;
		$books = Book::where('name', 'like', "%$search%")->orWhere('authors', 'liek', "%$search%")->paginate(10);
		return view("book.search")->withBooks($books);
	}

	/**
	 * method post, 必须携带book_id参数
	 * @param Request $request
	 * @return bool
	 */
	public function downloadCourseWare(Request $request){
		$this->validate($request,[
			'book_id' => 'required'
		]);

		$user      = Auth::user();
		$user_info = UserInfo::where('user_id',$user->id)->first();
		$book      = Book::where('id',$request->get('book_id'))->first();

		$openid    = $user->openid;
		$book_url  = url('/home')."?openid=$openid";

		if(empty($user_info) || empty($user_info->role)){
			$reply = "只有注册用户才可下载课件，<a href='http://www.itshuquan.com/userinfo/basic?openid=".$openid."'>点此注册</a>";
		}else{
			$code   = $book->department->code;
			$kj_url = Courseware::getCourseware($book->id);
			if(empty($kj_url)){
				$reply = "本书没有课件";
			}else{
				$record = new DownloadRecord;
				$record->user_id = $user->id;
				$record->book_id = $book->id;
				$record->save();
				
				$isbn  = $book->isbn;
				$pass  = Courseware::getCoursewarePassword($isbn,$code);
				$reply = "课件下载地址：$kj_url \n 课件密码：$pass";
			}
			$reply = $reply."\n<a href='".$book_url."'>更多图书资源</a>";
		}

		$result = WechatMessageSender::sendText($openid,$reply);
		return $result;
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
