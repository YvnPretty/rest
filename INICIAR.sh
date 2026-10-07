#!/usr/bin/env bash
set -e
cd "$(dirname "$0")"
php artisan serve --host=127.0.0.1 --port=8000
