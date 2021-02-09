<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $organization=new Organization();
        $organization->mobile=$request->mobile;
        $organization->mobile2=$request->mobile2;
        // $organization->img=$request->img;
        $organization->save();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userable_id'=>$organization->id,
            'userable_type'=>'App\Models\Organization'
        ]);

        DB::commit();
        return $organization;

    }
    public function show($id)
    {
        $organization=Organization::where('id',$id)->first();
        return new OrganizationResource($organization);
    }
}
