<?php

namespace App\Models;

use App\Helpers\CrossDomainHelper;

class Courseware
{
	public static function getCourseware($book_id){
		$book = Book::find($book_id);

		$kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar", "http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
		$real_url    = null;
		$old_url     = $book->kj_url;
		foreach($kj_url_list as $kj_url)
			if(CrossDomainHelper::url_exists($kj_url, $real_url)){
				$book->kj_url = $real_url;
				break;
			}
		if($book->kj_url != $old_url) {
			$book->update();
		}

		if(substr($book->department->code,0,3) == '804'){
			return 'http://www.tupwk.com.cn/downpage/index.asp';
		}
		return $book->kj_url;
	}

	public static function getCoursewarePassword($isbn, $department_code){
		$isbn_len = strlen($isbn);

		if($department_code[0] == '1'){ #（理工分社）：密码是书号后五位，再加一个6，例如9787302419181，密码是419186
			return substr($isbn,$isbn_len-5,5).'6';
		}
		if($department_code[0] == '2' || $department_code[0] == 6){ #（计算机分社、高职分社）：书号后6位两两相加，例如450269，密码是4+5,0+2,6+9，最后是9215
			return (string)((int)$isbn[$isbn_len-6]+(int)$isbn[$isbn_len-5]). (string)((int)$isbn[$isbn_len-4]+(int)$isbn[$isbn_len-3]). (string)((int)$isbn[$isbn_len-2]+(int)$isbn[$isbn_len-1]);
		}
		if($department_code[0] == '3'){
			#（经管分社）先取后6位，最前面加“tup”,然后后2位相加，如果相加结果是1位数，则直接替换后2位;，如果相加结果是2位数，则用个位数替换后2位，然后进位;
			# 书号是：9787302455929 则密码是tup45601
			# 书号是：9787302455923 则密码是tup45595
			# 书号是：9787302455929 则密码是tup45601
			$isbn_len = strlen($isbn);
			if($isbn_len < 6){
				return '输入的isbn无效';
			}
			$prefix_4 = intval(substr($isbn, $isbn_len-6,4));
			$last_0   = intval(substr($isbn, $isbn_len-1, 1));
			$last_1   = intval(substr($isbn, $isbn_len-2, 1));
			$last_sum = $last_0 + $last_1;
			if($last_sum / 10 > 0){
				$prefix_4 += 1;
			}
			$prefix_4 %= 10000;
			$last_sum %= 10;
			$pass = "tup".(string)($prefix_4).(string)($last_sum);
			return $pass;
		}
		if($department_code[0] == '4'){#（外语分社）无密码
			return '此课件无密码';
		}
		if(substr($department_code,0,3) == '802'){ #（文源公司）密码是书号倒数第6-倒数第2，例如9787302419181，密码是41918
			return substr($isbn,$isbn_len-6,5);
		}
		if(substr($department_code,0,3) == '803'){#（金地公司）课书号后6位两两相加，书号最后两位相加（进位），不足四位就前面加2，例如450269，密码是4+5,0+2,6+9，最后是9215
			$pass = (string)((int)$isbn[$isbn_len-6]+(int)$isbn[$isbn_len-5]). (string)((int)$isbn[$isbn_len-4]+(int)$isbn[$isbn_len-3]). (string)((int)$isbn[$isbn_len-2]+(int)$isbn[$isbn_len-1]);
			if(strlen($pass) < 4){
				$pass = $pass.'2';
			}
			return $pass;
		}
		if(substr($department_code,0,3)=='804'){# （文康公司）统一显示课件下载地址为：http://www.tupwk.com.cn/downpage/index.asp，没有密码
			return '此课件无密码';
		}

		return '此课件无密码，如有问题，请在后台联系管理员';
	}
}
