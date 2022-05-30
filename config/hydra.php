<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default user role
    |--------------------------------------------------------------------------
    |
    | This value is the default user role id that will be assigned to new users
    | when they register.
    |
    | 1 = Admin role, 2 = User role, 3 = Customer Role - Check RoleSeeder for more
    */

    'default_user_role_id' => env('DEFAULT_ROLE_ID', 2),

    /*
    |--------------------------------------------------------------------------
    | Delete old access tokens when logged in
    |--------------------------------------------------------------------------
    |
    | This value determines whether or not to delete old access tokens when
    | the users are logged in.
    |
    */

    'delete_previous_access_tokens_on_login' => env('DELETE_PREVIOUS_ACCESS_TOKENS_ON_LOGIN', false),
];
