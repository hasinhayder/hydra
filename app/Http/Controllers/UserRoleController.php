<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRoleController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(User $user): User {
        return $user->load('roles');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user): User|Response {
        $data = $request->validate([
            'role_id' => 'required|integer',
        ]);
        $role = Role::find($data['role_id']);
        if (! $user->roles()->find($data['role_id'])) {
            $user->roles()->attach($role);
        }

        return $user->load('roles');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Role $role): User {
        $user->roles()->detach($role);

        return $user->load('roles');
    }
}
