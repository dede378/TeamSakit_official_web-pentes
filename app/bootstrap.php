<?php
declare(strict_types=1);

session_start();

function env_value(string $key, string $default = ''): string {
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function lab_enabled(): bool {
    return filter_var(env_value('LAB_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN);
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = env_value('DB_HOST', '127.0.0.1');
    $port = env_value('DB_PORT', '3306');
    $name = env_value('DB_DATABASE', 'teamsakit_lab');
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo = new PDO($dsn, env_value('DB_USERNAME'), env_value('DB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function require_lab_access(): void {
    if (!lab_enabled()) {
        http_response_code(404);
        exit('Not found');
    }
    $user = env_value('LAB_USERNAME');
    $pass = env_value('LAB_PASSWORD');
    if ($user === '' || $pass === '' ||
        !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
        !hash_equals($user, (string)$_SERVER['PHP_AUTH_USER']) ||
        !hash_equals($pass, (string)$_SERVER['PHP_AUTH_PW'])) {
        header('WWW-Authenticate: Basic realm="TeamSakit Practice Lab"');
        http_response_code(401);
        exit('Authentication required');
    }
}
