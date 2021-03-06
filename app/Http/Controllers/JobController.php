<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Http\Resources\MinJobResource;
use App\Message;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function getJobByTags(Request $request)
    {
        $tags = $request->tags;
        $city = $request->city_id;

        $jobs = Job::where('registration_date', '>=', Carbon::today());
        if($tags!=null)
        {
            $jobs->whereIn('tag_id', $tags);
        }
        if($city!=null)
        {
            $jobs->where('city_id',$city);
        }
        $jobs=$jobs->get();
        // return $jobs;
        return MinJobResource::collection($jobs);
    }
    public function show($id)
    {
        return new JobResource(Job::find($id));
    }
    public function requests(Request $request)
    {
        $jid=$request->job_id;
        return  DB::select("select userable_id,name from users u join volunteers_jobs vj on (vj.volunteer_id=u.userable_id ) WHERE  vj.job_id=$jid and vj.status='pending'and userable_type like '%V%'");
    }
    public function store(Request $request)
    {

        if(Auth::user()->userable_type!='App\Models\Organization')
        {
            $message = new Message("يجب ان تكون مبادره");
                return response()->json([
                    "success" => false,
                    "message" => $message
                ], 400);
        }
        $job = new Job();
        $job->name = $request->name;
        $job->description = $request->description;
        $job->end_date = $request->end_date;
        $job->registration_date = $request->registration_date;
        $job->tag_id = $request->tag_id;
        $job->city_id = $request->city_id;
        $profile_picture = $request->img;

        $file_name = "";
        if ($profile_picture == null) {
            $file_name = "default.png";
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
        $job->media = '/img/' . $file_name;
        $job->organization_id=Auth::user()->userable_id;
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }


        $job->save();
        return response()->json([
            "success" => true,
        ], 200);
    }
    public function volunteers(Request $request)
    {
        //update volunteers_jobs set status='rejected' where status='pending' and job_id=(select id from jobs where registration_date< now())

        $volunteers=DB::table('jobs')
        ->join('volunteers_jobs','jobs.id','=','volunteers_jobs.job_id')
        ->join('volunteers','volunteers.id','=','volunteers_jobs.volunteer_id')
        ->join('users','volunteers.id','=','users.userable_id')
        ->join('cities','cities.id','=','jobs.city_id')
        ->where('jobs.id',"$request->job_id")
        ->where('users.userable_type',"App\Models\Volunteer")
        ->where('volunteers_jobs.status','!=','rejected')
        ->selectRaw('volunteers.id ,volunteers.NID, users.name,volunteers.mobile,users.email,IFNULL(volunteers_jobs.stars,-1) as rating,volunteers_jobs.status,cities.name as city_name')
        ->get();
        return $volunteers;
    }
    public function update(Request $request)
    {

        $job = Job::find($request->job_id);
        $job->name = $request->name;
        $job->description = $request->description;
        $job->end_date = $request->end_date;
        $job->registration_date = $request->registration_date;
        $job->tag_id = $request->tag_id;
        $job->city_id = $request->city_id;
        $profile_picture = $request->img;

        $file_name = "";
        if ($profile_picture == null || $profile_picture=='') {
            // $file_name = "default.png";
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
            $job->media = '/img/' . $file_name;

        }
        $job->organization_id=Auth::user()->userable_id;
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }


        $job->save();
        return response()->json([
            "success" => true,
        ], 200);
    }
}
