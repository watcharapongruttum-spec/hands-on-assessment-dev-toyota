@echo off

start cmd /k "php artisan serve"

timeout /t 2 >nul

start cmd /k "php artisan queue:work --tries=3"

timeout /t 2 >nul

start cmd /k "php artisan reverb:start --debug"

timeout /t 2 >nul

start cmd /k "npm run dev"