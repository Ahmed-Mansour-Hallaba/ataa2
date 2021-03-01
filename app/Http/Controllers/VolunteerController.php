<?php

namespace App\Http\Controllers;

use App\Http\Resources\VolunteerMinResource;
use App\Http\Resources\VolunteerResource;
use App\Message;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VolunteerController extends Controller
{
    public function store(Request $request)
    {
        /*
        {
            "name":"mans",
            "email":"mans@123",
            "password":"123456",
            "mobile":"01201636485",
            "NID":"297011212121",
            "profile_picture":"encode64",

        }
        */
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
        $volunteer->img =  '/img/' . $file_name;

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
        if ($request->tags != null) {
            foreach ($request->tags as $tag) {
                DB::insert("INSERT INTO `taggables` (`tag_id`, `taggable_id`, `taggable_type`) VALUES ('$tag', '$volunteer->id', 'App\\\Models\\\Volunteer');");
            }
        }
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
        $volunteer = Volunteer::find(Auth::user()->userable_id);
        $volunteer->mobile = $request->mobile;
        $volunteer->NID = $request->NID;
        $profile_picture = $request->img;
        //'data:image'
        $file_name = "";
        if (substr($profile_picture, 0, 10) == 'data:image') {
            if ($volunteer->img != 'default.png')
                if (file_exists($volunteer->img))
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
            $volunteer->img =  '/img/' . $file_name;
        }

        $volunteer->save();
        $user = User::find($volunteer->user->id);
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
        // DB::update("update volunteers_jobs set status='rejected' where status='pending' and job_id=(select id from jobs where registration_date< now())");
        $volunteer = Volunteer::where('id', $id)->first();
        return response()->json([
            "success" => true,
            "message" => new VolunteerResource($volunteer)
        ], 200);
    }
    public function list()
    {
        $volunteers = Volunteer::all();
        return response()->json([
            "success" => true,
            "message" => VolunteerMinResource::collection($volunteers),
        ], 200);
    }
    public function request(Request $request)
    {
        $check = DB::table('volunteers_jobs')
            ->where('volunteer_id', Auth::user()->userable_id)
            ->where('job_id', $request->job_id)
            ->count();
        if ($check > 0) {
            $message = new Message("تم التسجيل مسبقا");
            return response()->json([
                "success" => false,
                "message" => $message
            ], 400);
        }
        DB::insert('insert into `volunteers_jobs` (`volunteer_id`, `job_id`, `status`) VALUES (?, ?, ?);', [Auth::user()->userable_id, $request->job_id, 'pending']);
        return response()->json([
            "success" => true,
        ], 200);
    }
}
