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
        'owner_book_id',
        'access_role',
        'description',
        "credit",
        'type',
        'json_data',
        'created_at',
        'updated_at',
	];
}
