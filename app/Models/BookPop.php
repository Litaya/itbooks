<?php

namespace App\\Models;

use Illuminate\Database\Eloquent\Model;

class BookPop extends Model
{
    protected $table = 'book';

    protected $fillable = [
  		'favorite_num',
      'sales_volume'
  	];

    public static function searchFav(){
  		$books = BookPop::orderBy('favorite_num','desc');
  		return $books;
  	}

    public static function searchSale(){
  		$books = BookPop::orderBy('sales_volume','desc');
  		return $books;
  	}
}
