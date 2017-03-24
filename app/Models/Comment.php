<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';

    public function user(){
    	$this->belongsTo('App\Models\User','user_id','id');
    }

    public function material(){
	    $this->belongsTo('App\Models\Material','target_id','id');
    }

    public function reply(){
    	$this->belongsTo('App\Models\User','reply_id','id');
    }
}
