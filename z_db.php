<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$con = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
$con->set_charset("utf8mb4");

if (mysqli_connect_errno()) {
    die("Veritabanı bağlantı hatası: " . mysqli_connect_error());
}
?>