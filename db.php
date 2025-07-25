
<?php
$host = 'localhost';
$db = 'sanignaciodeloyo_moodle';
$user = 'sanignaciodeloyo_adminmoodle';
$pass = 'rjEt$(N%,uv9';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die('Error de conexi¨®n: ' . $e->getMessage());
}
