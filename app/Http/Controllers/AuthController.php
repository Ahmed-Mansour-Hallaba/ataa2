<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VolunteerResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (Auth::user()->userable_type == 'App\Models\Organization')
            return ([$this->respondWithToken($token), new OrganizationResource(Organization::find(Auth::user()->userable_id))]);

        else if (Auth::user()->userable_type == 'App\Models\Volunteer')
            return ([$this->respondWithToken($token), new VolunteerResource(Volunteer::find(Auth::user()->userable_id))]);
        else
            return ([$this->respondWithToken($token),new UserResource(Auth::user())]);
    }
    public function register(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userable_id' => 1,
            'userable_type' => 'App\Admin'
        ]);
    }
    public function userData()
    {
        if (Auth::user()->userable_type == 'App\Models\Organization')
            return new OrganizationResource(Organization::find(Auth::user()->userable_id));
        else if (Auth::user()->userable_type == 'App\Models\Volunteer')
            return new VolunteerResource(Volunteer::find(Auth::user()->userable_id));
        else
            return new UserResource(Auth::user());
    }
}
