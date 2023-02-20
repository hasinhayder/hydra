<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): User|Response {
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

        $defaultRoleSlug = config('hydra.default_user_role_slug', 'user');
        $user->roles()->attach(Role::where('slug', $defaultRoleSlug)->first());

        return $user;
    }

    /**
     * Authenticate an user and dispatch token.
     */
    public function login(Request $request): Response {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $creds['email'])->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error' => 1, 'message' => 'invalid credentials'], 401);
        }

        if (config('hydra.delete_previous_access_tokens_on_login', false)) {
            $user->tokens()->delete();
        }

        $roles = $user->roles->pluck('slug')->all();

        $plainTextToken = $user->createToken('hydra-api-token', $roles)->plainTextToken;

        return response(['error' => 0, 'id' => $user->id, 'token' => $plainTextToken], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): User {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws MissingAbilityException
     */
    public function update(Request $request, User $user): User {
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
            throw new MissingAbilityException('Not Authorized');
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): Response {
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

    /**
     * Return Auth user.
     */
    public function me(Request $request) {
        return $request->user();
    }
}
