## Simple Todo Web App

This is a basic todo web app built with laravel and vue.js with the following features:
- Task Lists (categories)
- Sorting
- Search
- Due dates
- statuses
- soft deletion and restore
- filtering based on status

## Setup

The app uses sqlite as a db connection for simplicity. after cloning the repo run the following commands.

`cp .env.example .env`

`php artisan key:generate`

`composer install`

`npm install`

`npm run build`

Note: If you are using the default sqlite db uploaded with this app no need to rerun migrations.

`php artisan migrate:fresh` or `php artisan migrate:fresh --seed` if you want to fill the DB with test data and user credentials (test@test.com : password).

`php artisan serve`

Then access the app using the URL printed on the screen.

## Video Demo

https://github.com/user-attachments/assets/8ff5c870-e30d-4612-af3a-798ff534e1dd

