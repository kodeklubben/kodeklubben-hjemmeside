# Deploy instructions (Ubuntu, NGINX, PHP, MySQL)

### Prerequisites
Ubuntu 14.04 or Ubuntu 16.04 with NGINX, MySQL and Node

Follow Step 1, Step 2 and Step 3 from https://www.digitalocean.com/community/tutorials/how-to-deploy-a-symfony-application-to-production-on-ubuntu-14-04

### Set Folder Permissions
(You may need to create these folders if they don't exist already)
```
cd /var/www
sudo setfacl -R -m u:www-data:rwX kodeklubben/app/cache kodeklubben/app/logs kodeklubben/web/img/club
sudo setfacl -dR -m u:www-data:rwX kodeklubben/app/cache kodeklubben/app/logs kodeklubben/web/img/club
```

### Setting Up the Application
#### 1. Make sure a new (empty) database has been created and is configured correctly in app/config/parameters.yml

#### 2. Set environment to production
`export SYMFONY_ENV=prod`

#### 3. Go to project directory
`cd kodeklubben`

#### 4. Install project dependencies and configure database and mail server
`SYMFONY_ENV=prod composer install --no-dev --optimize-autoloader`

#### 5. Create the database schema
`php app/console doctrine:schema:create --env=prod`

#### 6. Clear cache
`php app/console cache:clear --env=prod --no-debug`

#### 7. (Optional) Load fixture data to database
`php app/console doctrine:fixtures:load --env=prod`

#### 8. Install assets
`php app/console assets:install --symlink web --env=prod --no-debug`

`php app/console assetic:dump --env=prod --no-debug`

#### 9. Install node dependencies
`npm install`

#### 10. Build static files
`gulp build:prod`

### Setting Up the Web Server
Install PHP-FPM
`sudo apt-get install php5-fpm`

Follow Step 6 from https://www.digitalocean.com/community/tutorials/how-to-deploy-a-symfony-application-to-production-on-ubuntu-14-04#step-6-—-setting-up-the-web-server

### Done
The website should now be running

### Problems?
See http://symfony.com/doc/current/deployment/tools.html

and https://www.digitalocean.com/community/tutorials/how-to-deploy-a-symfony-application-to-production-on-ubuntu-14-04

for more info
