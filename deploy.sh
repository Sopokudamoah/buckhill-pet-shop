#Install dependencies
composer install
composer dump-autoload

# Set application key
php artisan key:generate --force

#Migrate tables
php artisan migrate --force

#Create default admin account
php artisan admin:create

# Seed database with data
php artisan db:seed --force

#Generate private and public keys for JWT generation
php artisan jwt:generate --force

#Regenerate API documentation
php artisan scribe:generate
