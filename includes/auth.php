<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function require_login(): void {
    if (empty($_SESSION['usuario'])) {
        header('Location: ../index.php');
        exit;
    }
}

function require_admin(): void {
    require_login();
    if (($_SESSION['usuario']['rol'] ?? '') !== 'ADMIN') {
        http_response_code(403);
        exit('Acceso denegado.');
    }
}
