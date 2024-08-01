# Build docker Containers
docker-compose build
docker-compose up -d
# Setup database
docker exec -it php-web php /var/www/html/db/setup_db.php
# Available routes can be checked in the router.php
The website will be available at http://localhost:8080/
# Run test
docker-compose run --rm tests