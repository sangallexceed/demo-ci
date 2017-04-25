#!/bin/bash
cd /srv
#/usr/bin/php composer.phar update
/usr/sbin/php-fpm; /usr/sbin/nginx -g 'daemon off;'
