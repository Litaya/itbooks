<?php

namespace App\Helpers;

use File;
use Image;
use Storage;

class FileHelper {

	/*
	 * always returns and saves relative path.
	 * use storage_path and public_path only when doing IO with filesystem
	 * for the convinience of migration
	 */
	public static function bookFolder($book){
		$folder = "fileupload/book/book_".$book->id."/";
		if(!File::isDirectory(public_path($folder)))
			File::makeDirectory(public_path($folder), 0777, true);
		return $folder;
	}

	public static function userCertificateFolder($user){
		$folder = "fileupload/cert/user_".$user->id."/";
		if(!File::isDirectory(storage_path($folder)))
			File::makeDirectory(storage_path($folder), 0777, true);
		return $folder;
	}

	public static function userResourceFolder($user){
		$folder = "fileupload/resource/user_".$user->id."/";
		if(!File::isDirectory(storage_path($folder)))
			File::makeDirectory(storage_path($folder), 0777, true);
		return $folder;
	}

	public static function saveUserImage($user, $image, $action){
		if($action == "certificate"){
			$folder = FileHelper::userCertificateFolder($user);
			$filename = time() . "." . $image->getClientOriginalExtension();
			$location = $folder.$filename;
			Image::make($image)->save(storage_path($location));
			return $location;
		}
		if($action == "resource"){
			$folder = FileHelper::userResourceFolder($user);
			$filename = time() . "." . $image->getClientOriginalExtension();
			$location = $folder.$filename;
			Image::make($image)->save(storage_path($location));
			return $location;
		}
		return FALSE;
	}

	public static function saveResourceFile($file){
		$filename = $file->getClientOriginalName() .'_'. time() . '.' . $file->getClientOriginalExtension();
		$location = 'resource/'.$filename;
		Storage::disk('public')->put($location, file_get_contents($file));
		return $location;
	}

	public static function getResourceFile($src){
		return Storage::disk('public')->get($src);
	}

	public static function saveBookImage($book, $image){
		$folder = FileHelper::bookFolder($book);
		$filename = time() . "." . $image->getClientOriginalExtension();
		$location = $folder.$filename;
		Image::make($image)->save(public_path($location));
		return $location;
	}

	public static function getUserImage($user, $src){
		return storage_path($src);
	}

	public static function getBookImage($book, $src){
		return public_path($src);
	}

	public static function materialFolder(){
		$folder = "fileupload/material/";
		if(!File::isDirectory(storage_path($folder)))
			File::makeDirectory(storage_path($folder), 0777, true);
		return $folder;
	}

	/**
	 * store image from url
	 * @param $url string
	 * @param $filename string
	 * @return string
	 */
	public static function storeImageFromUrl($url, $filename){
		$hinfo    = get_headers($url);
		// $ext      = explode('/',explode(':',$hinfo[7])[1])[1]; #后缀
        $ext      = explode('=',explode('?',$url)[1])[1];

		$folder   = FileHelper::materialFolder()."images/";
        if(!File::isDirectory(storage_path($folder)))
            File::makeDirectory(storage_path($folder), 0777, true);

		if(trim($filename) == '') $filename = 'example';
		$filename = explode('.',$filename)[0].".".$ext;

		Image::make($url)->save(storage_path($folder.$filename));
		return '/image/'.$folder.$filename;
	}
}

