# Project Setup
- Rename .env.example to .env
- Create two databases and add them : one in .env and the other in .env.testing
- composer install
- Add empty folder called "Unit" under /tests
- php artisan key:generate
- php artisan migrate:fresh
- php artisan passport:install
- php artisan test
- php artisan db:seed
- php artisan serve


# Notes
- Make sure to use application/json header

- You will find added folders like Enums, Requests, Helpers, Traits : This is a pattern that I use to make the code easy to maintain.
  Enums : for constants / Requests: for validation / Helpers: where I put the "long" logic so that the controllers are as clean as possible
  
- It is a simple task and it doesn't need all of this pattern setup, but it is really efficient for more complicated projects.

- I added test cases to cover all the APIs, and the most important part: student registration to handle all the cases possible. You can find all the tests under /tests/Feature

- I was going to containerize the task with creating MySQL / Nginx / App  containers with docker-compose, but I faced some issues related to docker with windows for docker desktop (wsl 2), and I run out of time to fix it.
