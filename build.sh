# install composer
docker run --rm -v $(pwd):/app composer/composer install --prefer-dist --ignore-platform-reqs --optimize-autoloader

# start container
docker-compose up -d