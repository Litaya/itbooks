<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
	protected $fillable = ['id,permission_string','certificate_as'];
}
