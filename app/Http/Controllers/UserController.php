<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'nullable|string',
        ]);

        $user = User::where('email', $creds['email'])->first();
        if ($user) {
            return response(['error' => 1, 'message' => 'user already exists'], 409);
        }

        $user = User::create([
            'email' => $creds['email'],
            'password' => Hash::make($creds['password']),
            'name' => $creds['name'],
        ]);

        $default_user_role_id = env('DEFAULT_ROLE_ID', 2);
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $default_user_role_id,
        ]);


        return $user;
    }

    /**
     * Authenticate an user and dispatch token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $creds['email'])->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error' => 1, 'message' => 'invalid credentials'], 401);
        }

        if (env('DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN', false) == true) {
            $user->tokens()->delete();
        }


        $roles = $user->roles()->get();
        $_roles = [];
        foreach ($roles as $role) {
            $_roles[] = $role->slug;
        }

        $plainTextToken = $user->createToken('hydra-api-token', $_roles)->plainTextToken;

        return response(['error' => 0, 'id' => $user->id, 'token' => $plainTextToken], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->email_verified_at = $request->email_verified_at ?? $user->email_verified_at;

        //check if the logged in user is updating it's own record


        $loggedInUser = $request->user();
        if ($loggedInUser->id == $user->id) {
            $user->update();
        } elseif ($loggedInUser->tokenCan('admin') || $loggedInUser->tokenCan('super-admin')) {
            $user->update();
        } else {
            throw new MissingAbilityException("Not Authorized");
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRoles = $user->roles;

        if ($userRoles->contains($adminRole)) {
            //the current user is admin, then if there is only one admin - don't delete
            $numberOfAdmins = Role::where('slug', 'admin')->first()->users()->count();
            if (1 == $numberOfAdmins) {
                return response(['error' => 1, 'message' => 'Create another admin before deleting this only admin user'], 409);
            }
        }

        $user->delete();

        return response(['error' => 0, 'message' => 'user deleted']);
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}
