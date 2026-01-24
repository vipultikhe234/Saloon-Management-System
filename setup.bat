@echo off
echo ========================================
echo Saloon Management System - Setup Script
echo ========================================
echo.

REM Check if .env exists
if not exist ".env" (
    echo Copying .env.example to .env...
    copy .env.example .env
    echo .env file created successfully!
    echo.
)

REM Install Composer dependencies
echo Installing Composer dependencies...
call composer install
if %ERRORLEVEL% NEQ 0 (
    echo Failed to install Composer dependencies!
    pause
    exit /b 1
)
echo.

REM Install NPM dependencies
echo Installing NPM dependencies...
call npm install
if %ERRORLEVEL% NEQ 0 (
    echo Failed to install NPM dependencies!
    pause
    exit /b 1
)
echo.

REM Generate application key
echo Generating application key...
php artisan key:generate
echo.

REM Ask for database type
echo Which database would you like to use?
echo 1. MySQL
echo 2. SQLite
set /p dbchoice="Enter your choice (1 or 2): "

if "%dbchoice%"=="2" (
    echo Setting up SQLite...
    echo. > database\database.sqlite
    
    REM Update .env for SQLite
    powershell -Command "(gc .env) -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_HOST=.*', '' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_PORT=.*', '' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_DATABASE=.*', '' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_USERNAME=.*', '' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_PASSWORD=.*', '' | Out-File -encoding ASCII .env"
    
    echo SQLite database file created!
) else (
    echo Using MySQL configuration...
    echo Please ensure you have created the database 'saloon_management' in MySQL
    echo.
    pause
)

REM Run migrations
echo Running database migrations...
php artisan migrate:fresh --seed
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ========================================
    echo Migration failed!
    echo ========================================
    echo.
    echo Please check:
    echo 1. Database connection in .env file
    echo 2. Database server is running
    echo 3. Database 'saloon_management' exists (for MySQL)
    echo.
    pause
    exit /b 1
)
echo.

REM Build assets
echo Building frontend assets...
call npm run build
echo.

echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo Default Login Credentials:
echo.
echo Super Admin:
echo   Email: admin@saloon.com
echo   Password: password
echo.
echo Saloon Admin:
echo   Email: saloon@saloon.com
echo   Password: password
echo.
echo Customer/User:
echo   Email: user@saloon.com  
echo   Password: password
echo.
echo To start the application, run:
echo   php artisan serve
echo.
echo Then visit: http://localhost:8000
echo.
pause
