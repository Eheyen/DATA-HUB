<?php
declare(strict_types=1);

/*
 * Configure estos datos con los proporcionados por el administrador del servidor.
 * No use localhost salvo que MySQL esté instalado en el mismo servidor web.
 */
const DB_HOST = 'localhost';
const DB_NAME = 'data_hub';
const DB_USER = 'USUARIO_MYSQL';
const DB_PASS = 'CONTRASENA_MYSQL';
const DB_CHARSET = 'utf8mb4';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
