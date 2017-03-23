<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $table = "conference";

    protected $fillable = [
        "name",
        "description",
        "time",
        "json",
        "created_at",
        "updated_at"
    ];

    public function participants(){
        return $this->hasMany('App\Models\ConferenceRegister', 'conference_id', 'id');
    }
}
