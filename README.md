## Simple Todo Web App

This is a basic todo web app built with laravel and vue.js with the following features:
- Task Lists (categories)
- Sorting
- Search
- Due dates
- statuses
- soft deletion and restore
- filtering based on status

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Setup

The app uses sqlite as a db connection for simplicity. after cloning the repo run the following commands.

`cp .env.example .env`

`php artisan key:generate`

`composer install`

`npm install`

`npm run build`

`php artisan migrate` or `php artisan migrate --seed` if you want to fill the DB with test data and user credentials (test@test.com : password).

`php artisan serve`

Then access the app using the URL printed on the screen.

## Video Demo

