<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{

    // AJAX GET
    public function getProvinces(){
        $provinces = District::where("parentid", "=", "0")->get();
        $d_array = [];
        foreach($provinces as $d){
            $d_array[$d->id] = $d->name;
        }

        return response()->json($d_array);
    }



    // AJAX GET
    public function getCities(Request $request){
        $province = District::find($request->province_id);

        $d_array = [];
        if($province->suffix == "市"){
            $d_array[$province->id] = $province->name;
        }
        else {
            $cities = District::where("parentid", "=", $province->id)->get();
            
            foreach($cities as $c){
                $d_array[$c->id] = $c->name;
            }
        }
        return response()->json($d_array);
    }



}
