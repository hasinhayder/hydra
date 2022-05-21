<!-- <img src='https://img.icons8.com/external-flaticons-lineal-color-flat-icons/344/external-hydra-ancient-greek-mythology-monsters-and-creatures-flaticons-lineal-color-flat-icons.png' width='256px'/> -->

![Hydra - Zero Config API Boilerplate with Laravel Sanctum](https://res.cloudinary.com/roxlox/image/upload/v1653131751/hydra/hydra_ygibap.png)

# Hydra - Zero Config API Boilerplate with Laravel Sanctum

Hydra is a zero-config API boilerplate with Laravel Sanctum and comes with excellent user and role management API out of the box. Start your next big API project with Hydra, focus on building business logic, and save countless hours of writing boring user and role management API again and again.

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
Role::select(['id','slug','name'])->get()
//Or Role::all()
```

## Routes Documentation

[Coming Soon...]
