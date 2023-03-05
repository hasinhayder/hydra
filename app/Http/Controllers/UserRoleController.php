<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\User  $user
     */
    public function index(User $user) {
        return $user->load('roles');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Models\User  $user
     */
    public function store(Request $request, User $user) {
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
     *
     * @return \App\Models\User  $user
     */
    public function destroy(User $user, Role $role) {
        $user->roles()->detach($role);

        return $user->load('roles');
    }
}
