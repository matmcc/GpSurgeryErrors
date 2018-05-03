# GpSurgery
Laravel app for GP Surgery

To Install:
Clone the project
Navigate to the application root folder in cmd or terminal
Run 'composer install'
Copy .env.example file to .env on the root folder. 
(You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Ubuntu)
Open .env file and change the database name (DB_DATABASE), username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your DB (MySQL) configuration. 
By default, username is 'root' and password field is empty. (For Xampp, Laragon) 

Run php artisan key:generate
Run php artisan migrate
Run php artisan db:seed
Run php artisan serve

Go to localhost:8000
