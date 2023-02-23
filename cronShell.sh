#!/bin/bash
echo "test1"
cd /var/www/html/dev && php artisan schedule:run >> /dev/null 2>&1
echo "test"
