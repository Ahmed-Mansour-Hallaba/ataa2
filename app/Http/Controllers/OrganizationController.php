<?php

namespace App\Http\Controllers;

use App\Http\Resources\MinJobResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Message;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $organization = new Organization();
        $organization->mobile = $request->mobile;
        $organization->mobile2 = $request->mobile2;
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
        $organization->img = '/img/' . $file_name;

        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }

        // $organization->img=$request->img;

        $organization->save();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userable_id' => $organization->id,
            'userable_type' => 'App\Models\Organization'
        ]);

        if ($request->tags != null) {
            foreach ($request->tags as $tag) {
                DB::insert("INSERT INTO `taggables` (`tag_id`, `taggable_id`, `taggable_type`) VALUES ('$tag', '$organization->id', 'App\\\Models\\\Organization');");
            }
        }
        DB::commit();
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }

        return response()->json([
            "success" => true,
            "message" => new OrganizationResource($organization)
        ], 200);
    }
    public function update(Request $request)
    {
        DB::beginTransaction();
        $organization = Organization::find(Auth::user()->userable_id);
        $organization->mobile = $request->mobile;
        $organization->mobile2 = $request->mobile2;
        $profile_picture = $request->img;

        $file_name = "";
        if ($profile_picture != null && $profile_picture!='') {
            if ($organization->img != 'img/default.png' )
                if (file_exists($organization->img))
                    unlink($organization->img);
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
        $organization->img =  '/img/' . $file_name;

        $organization->save();
        $user = User::find($organization->user->id);
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
            "message" => new OrganizationResource($organization)
        ], 200);
    }
    public function show($id)
    {
        $organization = Organization::where('id', $id)->first();
        return response()->json([
            "success" => true,
            "message" => new OrganizationResource($organization)
        ], 200);
    }
    public function list($pagination = null)
    {
        $organizations = Organization::paginate(10)->withQueryString();
        return response()->json([
            "success" => true,
            "message" => OrganizationResource::collection($organizations),
            "paginate" => $organizations
        ], 200);
    }
    public function activeJobs()
    {
        $jobs = Job::where('end_date', '>=', Carbon::today())
            ->where('organization_id', Auth::user()->userable_id)->get();
        return MinJobResource::collection($jobs);
    }

    public function allJobs()
    {
        $jobs = Job::where('organization_id', Auth::user()->userable_id)->get();
        return MinJobResource::collection($jobs);
    }
    public function updateRequest(Request $request)
    {
        $job_id = $request->job_id;
        $volunteer_id = $request->volunteer_id;
        $value = $request->value;
        DB::update('update volunteers_jobs set status = ? where volunteer_id = ? and job_id= ?', [$value, $volunteer_id, $job_id]);
        return response()->json([
            "success" => true,
        ], 200);
    }
    public function rateVolunteer(Request $request)
    {
        $volunteer_id = $request->volunteer_id;
        $job_id = $request->job_id;
        $rating = $request->rating;
        DB::update('update volunteers_jobs set stars = ? where volunteer_id = ? and job_id= ?', [$rating, $volunteer_id, $job_id]);
        return response()->json([
            "success" => true,
        ], 200);
    }
}
