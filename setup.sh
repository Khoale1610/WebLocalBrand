#!/usr/bin/env bash
set -e

echo "========================================================"
echo "  OWEN STUDIO - CAI DAT TU DONG DU AN WEBLOCALBRAND"
echo "========================================================"

if [ ! -f ".env" ]; then
    echo "[1/6] Tao file .env tu .env.example..."
    cp .env.example .env
else
    echo "[1/6] Da co file .env, bo qua."
fi

if [ ! -f "vendor/autoload.php" ]; then
    echo "[2/6] Dang cai dat composer install..."
    composer install --no-interaction --prefer-dist
else
    echo "[2/6] Da co thu muc vendor, bo qua."
fi

if ! grep -q "^APP_KEY=base64:" .env; then
    echo "[3/6] Dang tao APP_KEY..."
    php artisan key:generate --force
else
    echo "[3/6] Da co APP_KEY, bo qua."
fi

echo "[4/6] Kiem tra va tao Database MySQL theo file .env..."
php -r '$lines = @file(".env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); $env = []; foreach ($lines ?: [] as $line) { if (strpos(trim($line), "#") === 0 || strpos($line, "=") === false) continue; [$k, $v] = explode("=", $line, 2); $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'\''"); } $host = $env["DB_HOST"] ?? "127.0.0.1"; $port = $env["DB_PORT"] ?? "3306"; $db = $env["DB_DATABASE"] ?? "laravel"; $user = $env["DB_USERNAME"] ?? "root"; $pass = $env["DB_PASSWORD"] ?? ""; $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"); echo "Da san sang database: $db\n";'

echo "[5/6] Dang chay migrate & seed du lieu..."
php artisan migrate --seed --force

echo "[6/6] Tao storage:link va don dep cache..."
php artisan storage:link >/dev/null 2>&1 || true
php artisan optimize:clear

echo "========================================================"
echo "  HOAN TAT! Chay: php artisan serve"
echo "  Tai khoan Admin: admin@gmail.com / 12345678"
echo "========================================================"
