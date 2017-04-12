<?php

namespace App\Helpers;

use App\Models\Book;

class RecommendHelper {

    public static function getManuallySetTopBook($limit=1){
        $books = Book::where('type','<>', 1)->orderBy('weight', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getManuallySetTopTextbook($limit=1){
        $books = Book::where('type','=', 1)->orderBy('weight', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getNewBooks($limit=4){
        $books = Book::orderBy('created_at', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getHotBooks($limit=4){
        $books = Book::orderBy('weight', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getBookRecommend($user, $limit=10){
        $books = Book::inRandomOrder()->limit(10)->get();
        return $books;
    }

    public static function getTextbookRecommend($user, $limit=10){
        $books = Book::inRandomOrder()->limit(10)->get();
        return $books;
    }
    
    public static function classifyUserDepartment($user){

    }


}

