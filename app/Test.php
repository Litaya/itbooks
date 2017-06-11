<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public static function courseWare($isbn){
    	$isbn_len = strlen($isbn);
	    $prefix_4 = intval(substr($isbn, $isbn_len-6,4));
	    $last_0   = intval(substr($isbn, $isbn_len-1, 1));
	    $last_1   = intval(substr($isbn, $isbn_len-2, 1));
	    $last_sum = $last_0 + $last_1;
	    if($last_sum / 10 > 0){
		    $prefix_4 += 1;
	    }
	    $prefix_4 %= 10000;
	    $last_sum %= 10;
	    $password = "tup".(string)($prefix_4).(string)($last_sum);
	    return $password;
    }
}
