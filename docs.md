# Sources

- Laravel docs
- https://www.digitalocean.com/community/tutorials/simple-laravel-crud-with-resource-controllers


# Tools
- Docker, docker compose
- VSCode
- Postman

# Auth

- laravel sanctum
    check Sanctum vs Passport - pros and cons
    In general, Sanctum should be preferred when possible since it is a simple, complete solution for API authentication, SPA authentication, and mobile authentication, including support for "scopes" or "abilities".

    If you are building a single-page application (SPA) that will be powered by a Laravel backend, you should use Laravel Sanctum. When using Sanctum, you will either need to manually implement your own backend authentication routes or utilize Laravel Fortify as a headless authentication backend service that provides routes and controllers for features such as registration, password reset, email verification, and more.


    What are the downsides of sanctum?

- AuthController
    Hash::check safe from timing attacks, constant time check.
    Laravel uses the bcrypt (or Argon2) hash algorithm for passwords and Hash::check()


# User levels
- Simple database field for user level in user model
- Pros: simple to implement and, good for basic use cases
- Unable to handle complex authorization processes, like roles and permissions. For that, spatie/permissions would be better.