# Hydra - Zero Config API Boilerplate for Laravel Sanctum

Zero config API Boilerplate with user and role management API for Laravel Sanctum.

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

```
php artisan route:list
```

![Hydra - List of Default Routes](https://i.ibb.co/P629RbT/ezgif-5-78882ff5fc.webp)

