<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequest;
use App\Models\Role;
use App\Models\User;

class UserRoleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user) {
        return $user->load('roles');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(UserRoleRequest $request, User $user) {
        $role = Role::find($request->role_id);
        if (! $user->roles()->find($request->role_id)) {
            $user->roles()->attach($role);
        }

        return $user->load('roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Role $role) {
        $user->roles()->detach($role);

        return $user->load('roles');
    }
}
