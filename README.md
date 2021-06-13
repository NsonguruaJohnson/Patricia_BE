# User RESTful API

## Installation

Clone this repo\
Run composer install\
Run cp .env.example .env\
Fill in your databse details in the .env file\
Run php artisan key:generate\
Run php artisan migrate\
Run php artisan serve

## Usage
### Unprotected routes

POST /api/v1/register - register a new user\
POST /api/v1/login - login an authenticated user

### Protected routes

GET /api/v1/users/{id} - to fetch a user's details by id\
POST /api/v1/users/{id} - to update a user's details by id\
DELETE /api/v1/users/{id} - to delete a user's details by id

## Http Headers
Accept - application/json\ 
Authorization - Bearer {api_token} (For protected routes)

