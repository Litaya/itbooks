<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resource';

	protected $fillable = [
        'title',
		'file_upload',
		'owner_user_id',
        'owner_book_id',  // 为NULL则表示资源与书籍无关，为0则表示与所有书籍均相关，即所有书籍资源页均会有该资源，为大于0的值则表示仅与该书籍相关
        'access_role',
        'description',
        "credit",
        'type',
        'json_data',
        'created_at',
        'updated_at',
	];

    public function ownerUser(){
        return $this->belongsTo('App\Models\User', 'owner_user_id', 'id');
    }

    public function books(){
    	$resource_books = ResourceBook::where('resource_id', $this->id)->get();
    	if (count($resource_books) == 0){
    		return [];
	    }else{
    		$books = [];
    		foreach ($resource_books as $resource_book){
    			$book = Book::where('id', $resource_book->book_id)->first();
    			array_push($books, $book);
		    }
		    return $books;
	    }
    }

    public function checkUserValidation(User $user, $book_id){
    	if(empty($user)){
    		return false;
	    }

    	$access_role = $this->attributes['access_role'];
    	$access_role_arr = explode('|', $access_role);

    	# 找到 user 的所有权限角色
	    $user_role = $user->certificate_as;
	    $user_role_arr = explode('|', $user_role);

	    $user_resource_roles = array("USER");
	    # teacher
	    if (in_array("TEACHER", $user_role_arr)){
	    	array_push($user_resource_roles, "TEACHER");

	    	if (!empty($book_id)){
	    		$order_feedbacks = OrderFeedback::where('book_id', $book_id)->where('user_id', $user->id)->where('status',1)->get();
	    		if (sizeof($order_feedbacks) != 0){
	    			array_push($user_resource_roles, 'TEACHER_WITH_ORDER');
			    }
		    }
	    }
	    # author
	    if (in_array("AUTHOR", $user_role_arr)){
	    	array_push($user_resource_roles, "AUTHOR");
	    }
	    # student
	    if (in_array("STUDENT", $user_role_arr)){
	    	array_push($user_resource_roles, "STUDENT");
	    }

	    foreach ($user_resource_roles as $role){
	    	if (in_array($role, $access_role_arr)){
	    		return true;
		    }
	    }
		return false;
    }
}
