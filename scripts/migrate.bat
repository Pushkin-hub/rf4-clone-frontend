@echo off
REM Скрипт для применения миграций

echo Applying database migrations...
docker exec php-fpm php /app/artisan migrate

if %errorlevel% equ 0 (
    echo migrations applied succeccfully!
) else (
    echo Failed to apply migrations!
)
pause