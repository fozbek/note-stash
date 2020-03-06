## How to use

### First
    docker-compose up
    
## Create passport credentials in container
#### Get Laravel container ID
    docker ps

### Generate 
    docker-compose exec laravel php artisan passport:install

## Authentication
### Register
    curl -X POST -H 'Accept: application/json' -d 'name=Fatih&email=mail@fatih.dev&password=secretpassword&password_confirmation=secretpassword' http://localhost:3000/api/register
    
### Login
    curl -X POST -H 'Accept: application/json' -d 'email=mail@fatih.dev&password=secretpassword' http://localhost:3000/api/login

### Logout
    curl -H 'Accept: application/json' -H 'Authorization: Bearer the_token_given_to_u' http://localhost:3000/api/logout
