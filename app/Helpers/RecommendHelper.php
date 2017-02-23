<?php

namespace App\Helpers;

use App\Models\Book;

class RecommendHelper {
    public static function getBookRecommend($user, $limit=4){
        $books = Book::inRandomOrder()->limit(4)->get();
        return $books;
    }
}

