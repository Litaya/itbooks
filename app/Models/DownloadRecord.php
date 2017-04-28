<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadRecord extends Model
{
    protected $table = "download_record";
    protected $fillable = ["user_id", "book_id"];

    public function user(){
        return $this->hasOne("\App\Models\User", "user_id", "id");
    }

    public function book(){
        return $this->hasOne("\App\Models\Book", "book_id", "id");
    }
    
}
