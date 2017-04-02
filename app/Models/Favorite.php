<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorite';

    protected $fillable = [
    	'id',
	    'user_id',
	    'target_id',
	    'target_type'
    ];

    public function material(){
    	$this->belongsTo('App\Models\Material','target_id','id');
    }

	public function user(){
		$this->belongsTo('App\Models\User','user_id','id');
	}
}
