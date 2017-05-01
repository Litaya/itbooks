<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertRequest extends Model
{
	public $table="cert_request";

	public function user(){
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
}
