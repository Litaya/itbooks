<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;


class RecommenderController extends Controller
{
    public function getTestMessage(Request $request){
        $command = $request->command;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if($socket === false){
            return "socket_create() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
        }

        $success = @socket_connect($socket, "127.0.0.1", 9999);
        
        if($success){
            $args = $request->all();
            $segs = [];
            foreach($args as $k=>$v)
                array_push($segs, $k."=".$v);

            $msg = implode("&", $segs);
            socket_write($socket, $msg, strlen($msg));
            $buf = socket_read($socket, 1024);
            if($buf === false){
                return "socket_read() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
            }
            return "Server says: ".$buf;
        }

        return "socket_connect() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
    }

    public function getSimilarBooks(Request $request){
        $command = $request->command;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if($socket === false){
            return "socket_create() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
        }

        $success = @socket_connect($socket, "127.0.0.1", 9999);
        
        if($success){
            $args = $request->all();
            $segs = [];
            foreach($args as $k=>$v)
                array_push($segs, $k."=".$v);

            $msg = implode("&", $segs);
            socket_write($socket, $msg, strlen($msg));
            $buf = socket_read($socket, 1024);
            if($buf === false){
                return "socket_read() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
            }
            
            $id_list = explode(",", $buf);
            foreach($id_list as $i) $i = intval($i);
            
            if(array_key_exists("topk", $args)){
                $L = intval($args["topk"]);
                if($L > 5){
                    $id_shuffle = range(0, $L);
                    for($i=1;$i<$L;$i++){
                        $r = rand() % $i;
                        $tmp = $id_shuffle[$i];
                        $id_shuffle[$i] = $id_shuffle[$r];
                        $id_shuffle[$r] = $tmp;
                    }
                }
                $new_id_list = [];
                for($i = 0;$i<5;$i++){
                    $new_id_list[] = $id_list[$id_shuffle[$i]];
                }
                // $id_list = $new_id_list;
            }

            $books = Book::whereIn("id", $id_list)->get();
            $names = "";
            foreach($books as $book) $names = $names . $book->name . "<br>";

            return $names;
        }

        return "socket_connect() failed: reason: " . iconv("gbk", "utf-8", socket_strerror(socket_last_error())) . "\n";
    }
}
