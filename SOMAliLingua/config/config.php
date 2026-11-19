<?php
declare(strict_types=1);

// Change BASE_URL only if you rename the project folder or install it elsewhere.
define('APP_NAME', 'SomaliLingua');
define('BASE_URL', '/SomaliLingua');
define('APP_ROOT', dirname(__DIR__));
define('UPLOAD_MAX_BYTES', 10 * 1024 * 1024);

date_default_timezone_set('Africa/Nairobi');
if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secure,
        'samesite' => 'Lax'
    ]);
    session_start();
}
