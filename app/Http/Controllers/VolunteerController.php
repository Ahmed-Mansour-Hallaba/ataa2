<?php

namespace App\Http\Controllers;

use App\Http\Resources\VolunteerResource;
use App\Message;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VolunteerController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $volunteer = new Volunteer();
        $volunteer->mobile = $request->mobile;
        $volunteer->NID = $request->NID;
        $profile_picture = $request->img;

        $file_name = "";
        if ($profile_picture == null) {
            $file_name = "img/default.png";
        } else {
            $generate_name = uniqid() . "_" . time() . date("Ymd") . "_IMG";
            $base64Image = $profile_picture;
            $fileBin = file_get_contents($base64Image);
            $mimtype = mime_content_type($base64Image);
            if ($mimtype == "image/png") {
                $file_name = $generate_name . ".png";
            } else if ($mimtype == "image/jpeg") {
                $file_name = $generate_name . ".jpeg";
            } else if ($mimtype == "image/jpg") {
                $file_name = $generate_name . ".jpg";
            } else {
                $message = new Message("Profile image must be image file (png,jpeg,jpg)");
                return response()->json([
                    "success" => false,
                    "message" => $message
                ], 400);
            }
        }
        $volunteer->img = $file_name;

        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }

        // $volunteer->img=$request->img;

        $volunteer->save();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userable_id' => $volunteer->id,
            'userable_type' => 'App\Models\Volunteer'
        ]);

        DB::commit();
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }

        return response()->json([
            "success" => true,
            "message" => new VolunteerResource($volunteer)
        ], 200);
    }
    public function update(Request $request)
    {
        DB::beginTransaction();
        $volunteer = Volunteer::find($request->id);
        $volunteer->mobile = $request->mobile;
        $volunteer->mobile2 = $request->mobile2;
        $profile_picture = $request->img;

        $file_name = "";
        if ($profile_picture != null) {
            if($volunteer->img!='img/default.png')
                unlink($volunteer->img);
            $generate_name = uniqid() . "_" . time() . date("Ymd") . "_IMG";
            $base64Image = $profile_picture;
            $fileBin = file_get_contents($base64Image);
            $mimtype = mime_content_type($base64Image);
            if ($mimtype == "image/png") {
                $file_name = $generate_name . ".png";
            } else if ($mimtype == "image/jpeg") {
                $file_name = $generate_name . ".jpeg";
            } else if ($mimtype == "image/jpg") {
                $file_name = $generate_name . ".jpg";
            } else {
                $message = new Message("Profile image must be image file (png,jpeg,jpg)");
                return response()->json([
                    "success" => false,
                    "message" => $message
                ], 400);
            }
        }
        $volunteer->save();
        $user = User::find($volunteer->user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        DB::commit();
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }
        return response()->json([
            "success" => true,
            "message" => new VolunteerResource($volunteer)
        ], 200);
    }
    public function show($id)
    {
        $volunteer = Volunteer::where('id', $id)->first();
        return response()->json([
            "success" => true,
            "message" => new VolunteerResource($volunteer)
        ], 200);
    }
    public function list($pagination=null)
    {
         $volunteers= Volunteer::paginate(10)->withQueryString();
        return response()->json([
            "success" => true,
            "message" => VolunteerResource::collection($volunteers),
            "paginate"=> $volunteers
        ], 200);
    }
}