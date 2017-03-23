<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRegister extends Model
{
    protected $table = "conference_register";

    protected $fillable = [
        "user_id",
        "conference_id",
        "created_at",
        "updated_at"
    ];
}
