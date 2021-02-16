<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function list()
    {
        $tags= Tag::all();
        return response()->json([
            "success" => true,
            "message" => $tags,
        ], 200);
    }
}
