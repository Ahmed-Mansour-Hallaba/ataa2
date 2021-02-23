<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function list()
    {
        $cities= City::all();
        return response()->json([
            "success" => true,
            "message" => $cities,
        ], 200);
    }
}
