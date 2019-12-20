#!/usr/bin/env bash

SERVICE=timetracker_timetracker_1
CONTAINER=$(docker ps -qf "name=${SERVICE}")

if [[ $CONTAINER == '' ]]; then
    echo "Starting ${SERVICE}..."
    docker-compose up -d
fi

echo "Starting ${SERVICE} PHPUnit Tests..."

docker exec -i ${SERVICE} ./vendor/bin/phpunit \
    --colors=always \
    --configuration tests/unit \
    --coverage-clover=clover.xml \
    --log-junit junit.xml \
#    --testdox

exit $?