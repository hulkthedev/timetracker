# install composer
docker run --rm -v $(pwd):/app composer:1.10.1 install --prefer-dist --ignore-platform-reqs --optimize-autoloader

# start container
docker-compose up -d