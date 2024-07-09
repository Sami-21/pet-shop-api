
# Pet shop API 

## Table of content
1.  [Project Overview](#project-overview)
2.  [Cloning and Running the Project](#cloning-and-running-the-project)
3.  [Current Progress](#current-progress)
4.  [To Dos](#to-dos)


## Project Overview

Pet shop API is a project designed to test candidates experience in backend development. On this part of the test, I showcased my knowledge, skills, and detail-oriented Backend experience.

This application is a replica to the existing PetShop API made by Buckhill to  test candidates.

This project was created using the following technologies and tools:
- [Laravel framework (11.x)](https://laravel.com/docs/11.x)

This project was scaffolded with Laravel. It sets up a standard directory structure, enabling you to begin development and build things quickly. Feel free to check Laravel  [full documentation](https://vuetifyjs.com/) 

 
## Cloning and Running the Project

### Prerequisites
- [PHP 8.3](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/) 
- [MySQL](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04) 

####  Quick note
If you are using windows you can download [XAMPP](https://www.apachefriends.org/) + Composer and you are ready to go.


### Cloning the Repository

1. Open your terminal and run the following command to clone the project:
```bash
git clone https://github.com/Sami-21/pet-shop-api.git
```

2. Enter project directory:
```bash
cd pet-shop-api
```

3. Install dependencies:
```bash
composer install 
```
4. Copy .env.example to .env 
```bash
cp .env.example .env
```
fill database credentials in your .env file to link it with laravel application.

5. Generate app key
```bash
php artisan key:generate
```
6. Generate private and public keys for asymmetric encryption with lcobucci/jwt , the command below with generate a private key within yout root directory: 
```bash 
openssl genpkey -algorithm RSA -out private.key -pkeyopt rsa_keygen_bits:4096
```
Now for the public key:
```bash
openssl rsa -pubout -in private.key -out public.key
```

Et voila you have your keys ready , the next step is to create a folder called keys in  storage directory move them to it.
```bash
mkdir storage/keys
mv private.key storage/keys/private.key
mv public.key storage/keys/public.key

```

#### ❗️ Important info
Add those keys to your .gitignore file (you can exclude the public key) if it not there already.
\
\
7. Run your migrations and seeders :
```bash
php artisan migrate --seed
```
\
8. Finally run your project:
```bash 
php artisan serve
```
\
9. You can generate Swagger docs by running : 
```bash 
php artisan l5-swagger:generate
```

\
10. You can also run tests  : 
```bash 
php artisan test
```
## Current Progress

- Log in
- Create user account
- Logout
- View user details
- View user orders

## Current Progress

- User endpoints (excluding password-reset/change-password)
- Brand endpoints
- Category Endpoints
- Product Endpoints

## To Dos
- User password reset / password change
- 


## License

[MIT](https://choosealicense.com/licenses/mit/)
