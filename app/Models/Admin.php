<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
	protected $fillable = ['role', 'user_id', 'department_id', 'district_id'];
}
