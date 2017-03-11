<?php
namespace app\Helpers;

use App\Models\Resource;
use App\Models\User;
use App\Models\Book;

class Observer {

    private static $log_file_path = "D:\\Code\\itbooks\\storage\\logs\\userbehavior.log";
    private static $log_file_handle = NULL;


    public static function start(){
        self::$log_file_handle = fopen(self::$log_file_path, "a") or die("unable to open log file");
    }


    public static function stop(){
        if(self::$log_file_handle !== NULL){
            fclose(self::$log_file_handle);
            self::$log_file_handle = NULL;
        }
    }


    public static function observe($behavior, $request){
        if(self::$log_file_handle === NULL)
            self::start();

        if($behavior == "navigate"){
            self::recordNavigation($request);
        }

        if($behavior == "kejiandownload"){
            self::recordKejianDownload($request);
        }

        if($behavior == "resourcedownload"){
            self::recordResourceDownload($request);
        }

        self::stop();
    }


    private static function recordNavigation($request){
        $user = $request->user;
        if($user){
            self::write_log("[NAVGT] USER:".$user->username." URL:".$request->url);
        }
    }

    private static function recordKejianDownload($request){
        $user = $request->user;
        $book = Book::find($request->book_id);
        
        self::write_log("[KJDWN] USER:".$user->username." BOOK:".$book->name);
    }


    private static function recordResourceDownload($request){
        $user = $request->user;
        $resource = Resource::find($request->resource_id);
        $owner_book = $resource->ownerBook;
        $owner_user = $resource->ownerUser;
        
        self::write_log("[RSDWN] USER:".$user->username." RES:".$resource->id);
    }

    private static function write_log($str){
        fwrite(self::$log_file_handle, $str);
        fwrite(self::$log_file_handle, "\r\n");
    }

}