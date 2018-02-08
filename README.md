## Run Instructions:

* make new directory `mkdir testProj; cd testProj`
* clone project: `git clone https://github.com/snizhok/test-store.git .`
* change configuration in file `app/config.php`
* run in console: 
```
composer install
sudo chmod 777 -R resources/tmp
sudo npm i
sudo gulp
```
* restore database from dump `resources/db/dump.sql`
* app entry point `public/index.php`
