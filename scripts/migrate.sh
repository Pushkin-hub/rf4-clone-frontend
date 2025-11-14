#!/bin/bash
# Скрипт для применения миграции

echo "Applying database migrations..."
docker exec php-fpm php /app/artisan migrate

if [ $? -eg 0]; then
    echo "Миграция"
else
    echo "Failed to apply migrations!"
    exit 1
fi

