<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddLabel extends Model
{
    protected $table = "add_label";

	public function label(){
		return $this->belongsTo('App\Models\Label', 'label_id', 'id');
	}
	public function material(){
		return $this->belongsTo('App\Models\Material', 'target_id', 'id');
	}
}
