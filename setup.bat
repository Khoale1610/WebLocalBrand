@echo off
chcp 65001 >nul
echo ========================================================
echo   OWEN STUDIO - CAI DAT TU DONG DU AN WEBLOCALBRAND
echo ========================================================
echo.

REM 1. Tao file .env neu chua co
if not exist ".env" (
    echo [1/6] Tao file .env tu .env.example...
    copy ".env.example" ".env" >nul
) else (
    echo [1/6] Da co file .env, bo qua.
)

REM 2. Cai dat vendor neu chua co
if not exist "vendor\autoload.php" (
    echo [2/6] Dang cai dat cac goi thu vien PHP (composer install)...
    call composer install --no-interaction --prefer-dist
    if errorlevel 1 (
        echo [LOI] Khong the chay composer install. Vui long kiem tra lai Composer/PHP.
        pause
        exit /b 1
    )
) else (
    echo [2/6] Da co thu muc vendor, bo qua.
)

REM 3. Tao APP_KEY neu chua co
findstr /r "^APP_KEY=base64:" .env >nul
if errorlevel 1 (
    echo [3/6] Dang tao APP_KEY...
    php artisan key:generate --force
) else (
    echo [3/6] Da co APP_KEY, bo qua.
)

REM 4. Tu dong tao Database MySQL neu chua ton tai
echo [4/6] Kiem tra va tao Database MySQL theo file .env...
php -r "$lines = @file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); $env = []; foreach ($lines ?: [] as $line) { if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue; [$k, $v] = explode('=', $line, 2); $env[trim($k)] = trim($v, \" \t\n\r\0\x0B\\\"'\"); } $host = $env['DB_HOST'] ?? '127.0.0.1'; $port = $env['DB_PORT'] ?? '3306'; $db = $env['DB_DATABASE'] ?? 'laravel'; $user = $env['DB_USERNAME'] ?? 'root'; $pass = $env['DB_PASSWORD'] ?? ''; try { $pdo = new PDO(\"mysql:host=$host;port=$port;charset=utf8mb4\", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); $pdo->exec(\"CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci\"); echo \"Da san sang database: $db\n\"; } catch (Throwable $e) { fwrite(STDERR, \"[LOI MYSQL] \" . $e->getMessage() . \"\nHay dam bao MySQL (XAMPP/Laragon) dang chay!\n\"); exit(1); }"
if errorlevel 1 (
    pause
    exit /b 1
)

REM 5. Chay Migrate va Seed toan bo san pham, bien the, voucher, don hang
echo [5/6] Dang tao bang va nap du lieu san pham (migrate --seed)...
php artisan migrate --seed --force
if errorlevel 1 (
    echo [LOI] Migrate hoac Seed that bai!
    pause
    exit /b 1
)

REM 6. Tao storage link va xoa cache
echo [6/6] Tao storage:link va don dep cache...
php artisan storage:link >nul 2>&1
php artisan optimize:clear

echo.
echo ========================================================
echo   HOAN TAT! Chay lenh sau de mo trang web:
echo   php artisan serve
echo   Sau do truy cap: http://127.0.0.1:8000
echo ========================================================
echo   Tai khoan Admin: admin@gmail.com / 12345678
echo   Tai khoan Khach: khachhang@gmail.com / 12345678
echo ========================================================
pause
