<?php

namespace App\Http\Controllers;

use App\Message;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {

        if(Auth::user()->userable_type!='Admin')
        {
            $message = new Message("يجب ان تكون مدير");
                return response()->json([
                    "success" => false,
                    "message" => $message
                ], 400);
        }
        $tag=new Tag();
        $tag->name=$request->name;
        $tag->save();
        return response()->json([
            "success" => true,
            "message" => $tag,
        ], 200);

    }
}
