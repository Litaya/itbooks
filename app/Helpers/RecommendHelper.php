<?php

namespace App\Helpers;

use App\Models\Book;

class RecommendHelper {

    public static function getManuallySetTopBook($limit=1){
        $books = Book::orderBy('weight', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getManuallySetTopTextbook($limit=1){
        $books = Book::where('type','=', 1)->orderBy('weight', 'desc')->orderBy('publish_time', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getNewBooks($limit=4){
        $books = Book::orderBy('publish_time', 'desc')->orderBy('weight', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getHotBooks($limit=4){
        $books = Book::orderBy('weight', 'desc')->orderBy('publish_time', 'desc')->limit($limit)->get();
        return $books;
    }

    public static function getBookRecommend($user, $limit=10){
        $books = Book::inRandomOrder()->limit($limit)->get();
        return $books;
    }

    public static function getTextbookRecommend($user, $limit=10){
        $books = Book::inRandomOrder()->limit($limit)->get();
        return $books;
    }


    public static function getSimilarBooks($book, $limit = 5){
        /** Load book id list from recommend service **/

        $L = 8;

        $indexes = self::cmdGetSimilar($book->name, $L);
        if($indexes !== false){                                 // succeed
            $L = count($indexes);
            $limit = min($L, $limit);

            $id_list = self::PickRandom($indexes, $limit);
            $books = Book::whereIn("id", $id_list)->get();
        }

        /** Recommend service failed, fallback to default method **/
        else {
            $books = Book::orderBy('weight', 'desc')->orderBy('publish_time', 'desc')->limit($L * 5)->get();
            
            $L = count($books);
            $limit = min($L, $limit);
            
            $books = self::PickRandom($books, $limit);
        }

        return $books;
    }
    

    private static function PickRandom($arr, $limit, $ordered=true){
        $L = count($arr);
        $id_shuffle = range(0, $L);                         // shuffle to get top 5
        for($i = 1; $i < $L; $i++){
            $r = rand() % $i;
            $tmp = $id_shuffle[$i];
            $id_shuffle[$i] = $id_shuffle[$r];
            $id_shuffle[$r] = $tmp;
        }
        $new_arr = [];
        $id_shuffle = array_slice($id_shuffle, 0, $limit);
        if($ordered) sort($id_shuffle);

        foreach($id_shuffle as $i)
            array_push($new_arr, $arr[$i]);
        
        return $new_arr;
    }


    private static function SocketRequest($args){
        $response = ["success" => false];
        try {
            $sock = new SSocket;
            $response["data"] = $sock->Request($args);
            $response["success"] = true;
        } catch (\Exception $e){
            $response["error"] = $e->getMessage();
        }
        return $response;
    }

    private static function cmdFitCluster($n_clusters){
        $response = self::SocketRequest(["command" => "FIT CLUSTER", "n_clusters" => "15"]);
        if(!$response["success"]) return false;
        return (strtoupper($response["data"]) == "TASK STARTED");
    }

    private static function cmdGetSimilar($name, $topk){
        $response = self::SocketRequest(["command" => "GET SIMILAR", "name"=>$name, "topk" => "20"]);
        if(!$response["success"]) return false;
        if(strtoupper(substr($response["data"], 0, 4)) == "BUSY") return false;
        
        $book_id_list = explode(",", $response["data"]);
        foreach($book_id_list as $i) $i = intval($i);

        return $book_id_list;
    }


}

