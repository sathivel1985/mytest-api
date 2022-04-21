Tech stack
Laravel 8
PHP 8.0 and Above
MySQL
Apache
Install
clone the repo
save as .env.example as .env
update the required configuration for .env file
cd to the project directory
execute composer install
execute php artisan key:generate
execute php artisan migrate

api/object { "keName": "value"}
Get object - GET method

api/object/{keyName}
Get object with timestamp - GET method

api/object/{keyName}?timestamp={unixTimestamp}
Get all records - GET method

api/object/get_all_records

Feature test
Use terminal and go to project directory Execute the below command php artisan test --testsuite=Feature
