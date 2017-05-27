<?php

namespace App\Helpers;

use App\Models\Book;
use App\Models\Like;
use App\Models\BookRequest;


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
        $books = self::getRecommendation($user, $limit);
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
    
    public static function getRecommendation($user, $limit = 5){
        $L = 10;
        $indexes = self::cmdGetRecommend($user);
        if($indexes !== false){
            $L = count($indexes);

            $id_list = self::PickRandom($indexes, $limit);
            if($L < $limit){
                $nexts = Book::orderBy("weight", "desc")->orderBy('publish_time', 'desc')->limit($limit)->get();
                foreach($nexts as $b){
                    if(!in_array($b->id, $id_list)){
                        array_push($id_list, $b->id);
                        $L++;
                        if($L == $limit) break;
                    }
                }
            }
            $books = Book::whereIn("id", $id_list)->get();
        }

        /** Recommend service failed or cold start, fallback to default method **/
        else
        {
            $books = Book::orderBy("weight", "desc")->orderBy('publish_time', 'desc')->limit($L * 5)->get();
            $L = count($books);
            $limit = min($L, $limit);

            $books = self::PickRandom($books, $limit);
        }

        return $books;
    }


    private static function PickRandom($arr, $limit, $ordered=true){
        $L = count($arr);
        if($limit > $L) 
            return $arr;

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


    private static function cmdGetRecommend($user){
        $records = [];
        $bookreqs = $user->bookRequests;
        $likes = $user->bookLikes;
        foreach($bookreqs as $r){
            if(!in_array($r->book_id, $records))
                array_push($records, (string)$r->book_id);
        }

        foreach($likes as $r){
            if(!in_array($r->book_id, $records))
                array_push($records, (string)$r->book_id);
        }

        if(count($records) == 0) return false;
        else $records = self::PickRandom($records, 10);
        
        $request = ["command" => "GET RECOMMEND", "records" => implode(",", $records)];
        $response = self::SocketRequest($request);
        if(!$response["success"]) return false;
        if(strtoupper(substr($response["data"], 0, 4)) == "BUSY") return false;

        $book_id_list = explode(",", $response["data"]);
        foreach($book_id_list as $i) $i = intval($i);

        return $book_id_list;
    }

}

