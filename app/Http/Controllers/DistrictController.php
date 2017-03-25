<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public static function getAllProvinces(){
        return District::where("parentid", "=", "0")->get();
    }

    public static function getAllCities(){
        return District::where("suffix", "=", "市")->orWhere("suffix", "=", "县")->get();
    }

    public static function getCitiesOf($province_id){
        //return District::where("parent", "=", $request->province_id)->get();
        $pid = province_id;
        $dst = District::find($pid);
        if($dst->suffix == "市")
            return [$dst];
        // else
        return District::where("parent", "=", $request->province_id)->get();
    }

    public function postFetchCitiesOf(Request $request){
        //return District::where("parent", "=", $request->province_id)->get();
        $pid = $request->province_id;
        $dst = District::find($pid);
        if($dst->suffix == "市")
            return response()->json($dst);
        // else
        return response()->json(District::where("parent", "=", $request->province_id)->get());
    }

}
