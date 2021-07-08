# BACK END :dog: :cat:

## Libraries
__ALL ENVS :__
* CORS : [NelmioCorsBundle](https://github.com/nelmio/NelmioCorsBundle)
* JWT Authentication : [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

__DEV ENV :__ 
* Fixtures : [NelmioAlice](https://github.com/nelmio/alice)

## Installation

### Git

* Clone the git repository

### Composer

#### From the ```/Pith``` folder :
* Install composer dependencies by running the following command : 

```
composer install
```

### Configuration

* Create the __.env.local__ file in which the environment (dev|prod) and the database connection informations will be set : 

```
touch .env.local
```

* Use ``` mysql -V ```, you'll need to replace __DB_SERVER_VERSION__ at the next step by this info (e.g ``` 10.3.29-MariaDB ```)

* Edit the file and add & customize the following configuration : 

```YAML
DATABASE_URL="mysql://DB_USERNAME:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=DB_SERVER_VERSION"
APP_ENV=dev 
```
### Database

There are 2 commands (normal and shorter) for each of the following 3 steps.
For each step, both commands have the same action.

* Create the database :  

```
bin/console doctrine:database:create
```
```
bin/console d:d:c
```

* Execute the migrations files :

```
bin/console doctrine:migrations:migrate
```
```
bin/console d:mi:mi
```

* Import (if needed) the fake datas :

```
bin/console doctrine:fixtures:load --group=NelmioAliceFixtures
```
```
bin/console d:f:l --group=NelmioAliceFixtures
```

The --group option specify that only this file (NelmioAlicefixtures.php) will be use for fake datas import.

That file can be found in : ``` /Pith/src/DataFixtures ```

## Start a local PHP Server

```
php -S localhost:8000 -t public
```

And Voila! :blush:

Go to the login page : http://localhost:8000/login
