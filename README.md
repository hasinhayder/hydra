<!-- <img src='https://img.icons8.com/external-flaticons-lineal-color-flat-icons/344/external-hydra-ancient-greek-mythology-monsters-and-creatures-flaticons-lineal-color-flat-icons.png' width='256px'/> -->

![Hydra - Zero Config API Boilerplate with Laravel Sanctum](https://res.cloudinary.com/roxlox/image/upload/v1653133921/hydra/hydra-trnsparent_jcsl4l.png)

# Hydra - Zero Config API Boilerplate with Laravel Sanctum

[![CircleCI](https://circleci.com/gh/hasinhayder/hydra/tree/master.svg?style=svg)](https://circleci.com/gh/hasinhayder/hydra/tree/master) ![GitHub](https://img.shields.io/github/license/hasinhayder/hydra?label=License&style=flat-square)

Hydra is a zero-config API boilerplate with Laravel Sanctum and comes with excellent user and role management API out of the box. Start your next big API project with Hydra, focus on building business logic, and save countless hours of writing boring user and role management API again and again.

- [Hydra - Zero Config API Boilerplate with Laravel Sanctum](#hydra---zero-config-api-boilerplate-with-laravel-sanctum)
  - [Getting Started](#getting-started)
  - [Database Migration and Seeding](#database-migration-and-seeding)
  - [List of Default Routes](#list-of-default-routes)
  - [Default Roles](#default-roles)
  - [Routes Documentation](#routes-documentation)
    - [User Registration](#user-registration)
    - [User Authentication/Login (Admin)](#user-authenticationlogin-admin)
    - [User Authentication/Login (Other Roles)](#user-authenticationlogin-other-roles)
    - [List Roles (Admin Ability Required)](#list-roles-admin-ability-required)
    - [Add a New Role (Admin Ability Required)](#add-a-new-role-admin-ability-required)
    - [Update a Role (Admin Ability Required)](#update-a-role-admin-ability-required)
    - [Delete a Role (Admin Ability Required)](#delete-a-role-admin-ability-required)

## Getting Started

It's super easy to get Hydra up and running.

1. clone the project

```shell
git clone https://github.com/hasinhayder/hydra.git
```

2. Copy `.env.example` to `.env`

```shell
cp .env.example .env
```

3. Start the webserver

```shell
php artisan serve
```

That's mostly it! You have a fully running laravel installation with Sanctum, all configured.

## Database Migration and Seeding

Open your `.env` file and change the DATABASE options. You can start with SQLite by following these steps

1. Create a new sqlite database

```shell
touch database/hydra.sqlite
```

Or simply create a new file as **hydra.sqlite** inside your **database** folder.

2. Run migration

```shell
php artisan migrate
```

Now your database has essential tables for user and roles management.

3. Database Seeding

Run `db:seed`, and you have your first admin user, some essential roles in the roles table and the relationship properly setup.

```shell
php artisan db:seed
```

Please note that the default admin user is **admin@hydra.project** and default password is **hydra**. You should create a new admin user before deploying to production and delete this default admin user. You can do that using available Hydra user management API, or using any DB management tool.

## List of Default Routes

Here is a list of default routes. Run the following artisan command to see this list in your terminal.

```shell
php artisan route:list
```

![Hydra - List of Default Routes](https://res.cloudinary.com/roxlox/image/upload/v1653131647/hydra/default-routes-hydra_fgn9oh.webp)

## Default Roles

Hydra comes with these `super-admin`,`admin`,`editor`,`customer` & `user` roles out of the box. For details, open the roles table after database seeding, or simply open laravel tinker and experiment with `Role` model

```shell
php artisan tinker
```

run the following command

```php
>>> Role::select(['id','slug','name'])->get()
//or
>>> Role::all(['id','name','slug'])
//or
>>> Role::all()
```

## Routes Documentation

Let's have a look at what Hydra has to offer. Before experimenting with the following API endpoints, run your Hydra project using `php artisan serve` command. For the next part of this documentation, we assumed that Hydra is listening at http://localhost:8000

### User Registration

You can make an `HTTP POST` call to the following endpoint to create/register a new user. newly created user will have the `user` role by default.

```shell
http://localhost:8000/api/users
```

**API Payload & Response**

You can send a Form Multipart payload or a JSON payload like this

```json
{
    "name":"Hydra User",
    "email":"user@hydra.project",
    "passsword":"Surprisingly A Good Password"
}
```

Voila! your user has been created and is now ready to login!

If this user already exists, then you will receive a 409 Response like this

```json
{
    "error": 1,
    "message": "user already exists"
}
```

### User Authentication/Login (Admin)

Remember Hydra comes with the default admin user? You can login as an admin by making an HTTP POST call to the folllowing route

```shell
http://localhost:8000/api/login
```

**API Payload & Response**

You can send a Form Multipart or a JSON payload like this

```json
{
    "email":"admin@hydra.project",
    "passsword":"hydra"
}
```

You will get a JSON response with user token. You need this admin token for making any call to other routes protected by admin ability.

```json
{
    "error": 0,
    "token": "1|se9wkPKTxevv9jpVgXN8wS5tYKx53wuRLqvRuqCR"
}
```

For any unsuccsesful attempt, you will receive a 401 error response.

```json
{
    "error": 1,
    "message": "invalid credentials"
}
```

### User Authentication/Login (Other Roles)

You can login as a user by making an HTTP POST call to the folllowing route

```shell
http://localhost:8000/api/login
```

**API Payload & Response**

You can send a Form Multipart or a JSON payload like this

```json
{
    "email":"user@hydra.project",
    "passsword":"Surprisingly A Good Password"
}
```

You will get a JSON response with user token. You need this user token for making any call to other routes protected by user ability.

```json
{
    "error": 0,
    "token": "2|u0ZUNlNtXgdUmtQSACRU1KWBKAmcaX8Bkhd2xVIf"
}
```

For any unsuccsesful attempt, you will receive a 401 error response.

```json
{
    "error": 1,
    "message": "invalid credentials"
}
```

### List Roles (Admin Ability Required)

To list the roles, make an `HTTP GET` call to the following route, with Admin Token obtained from Admin Login. Add this token as a standard `Bearer Token` to your API call.

```shell
http://localhost:8000/api/roles
```

**API Payload & Response**

No payload required for this call.

You will get a JSON response with all the roles available in your project.

```json
[
    {
        "id": 1,
        "name": "Administrator",
        "slug": "admin"
    },
    {
        "id": 2,
        "name": "User",
        "slug": "user"
    },
    {
        "id": 3,
        "name": "Customer",
        "slug": "customer"
    },
    {
        "id": 4,
        "name": "Editor",
        "slug": "editor"
    },
    {
        "id": 5,
        "name": "All",
        "slug": "*"
    },
    {
        "id": 6,
        "name": "Super Admin",
        "slug": "super-admin"
    }
]
```

For any unsuccsesful attempt or wrong token, you will receive a 401 error response.

```json
{
    "message": "Unauthenticated."
}
```

### Add a New Role (Admin Ability Required)

To list the roles, make an `HTTP POST` call to the following route, with Admin Token obtained from Admin Login. Add this token as a standard `Bearer Token` to your API call.

```shell
http://localhost:8000/api/roles
```

**API Payload & Response**

You need to supply title of the role as `name`, role `slug` in your payload as Multipart Form or JSON data

```json
{
    "name":"Manager",
    "slug":"manager"
}
```

For successful execution, you will get a JSON response with this newly created role.

```json
{
    "name": "Manager",
    "slug": "manager",
    "id": 7
}
```

If this role `slug` already exists, you will get a 409 error message like this

```json
{
    "error": 1,
    "message": "role already exists"
}
```

For any unsuccsesful attempt or wrong token, you will receive a 401 error response.

```json
{
    "message": "Unauthenticated."
}
```

### Update a Role (Admin Ability Required)

To update a role, make an `HTTP PUT` or `HTTP PATCH` request to the following route, with Admin Token obtained from Admin Login. Add this token as a standard `Bearer Token` to your API call.

```shell
http://localhost:8000/api/roles/{roleid}
```

For example to update the Customer role, use this endpoint `http://localhost:8000/api/roles/3`

**API Payload & Response**

You need to supply title of the role as `name`, and/or role `slug` in your payload as Multipart Form or JSON data

```json
{
    "name":"Product Customer",
    "slug":"product-customer"
}
```

For successful execution, you will get a JSON response with this updated role.

```json
{
    "id": 3,
    "name": "Product Customer",
    "slug": "product-customer"
}
```

Please note that you cannot change a `super-admin` or `admin` role slug because many API routes in Hydra exclusively require this role to function properly.

For any unsuccsesful attempt or wrong token, you will receive a 401 error response.

```json
{
    "message": "Unauthenticated."
}
```

### Delete a Role (Admin Ability Required)

To delete a role, make an `HTTP DELETE` request to the following route, with Admin Token obtained from Admin Login. Add this token as a standard `Bearer Token` to your API call.

```shell
http://localhost:8000/api/roles/{roleid}
```

For example to delete the Customer role, use this endpoint `http://localhost:8000/api/roles/3`

**API Payload & Response**

No payload required for this endpoint.

For successful execution, you will get a JSON response with this updated role.

```json
{
    "error": 0,
    "message": "role has been deleted"
}
```

Please note that you cannot delete the `admin` role because many API routes in Hydra exclusively require this role to function properly.

If you try to delete the admin role you will receive the following 422 error response

```json
{
    "error": 1,
    "message": "you cannot delete this role"
}
```

For any unsuccsesful attempt or wrong token, you will receive a 401 error response.

```json
{
    "message": "Unauthenticated."
}
```
[Documentation In Progress...]
