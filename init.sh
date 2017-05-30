#for Linux and OSX
mkdir storage
mkdir storage/framework
mkdir storage/framework/sessions
mkdir storage/framework/views
mkdir storage/framework/cache
mkdir vendor
chmod -R a+w storage
chmod -R a+w bootstrap/cache
chmod a+w storage/logs/laravel.log
php artisan key:generate
