#!/bin/bash
docker exec mvno-app /srv/vendor/bin/phpunit -c /srv/application/tests/ --debug
