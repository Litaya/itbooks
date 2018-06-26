<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromptController extends Controller
{
    public function courseware(){
		return view('prompt.courseware');
    }
}
