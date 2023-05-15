###Order App
####Docker Installation & Docker Up
- docker-compose build && docker-compose up -d

- docker-compose exec order_app bash 

####Create Database , Migration and Seeder Commands

- composer install
- php artisan migrate
- php artisan db:seed
