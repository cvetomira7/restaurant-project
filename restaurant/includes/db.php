<?php
$host = 'localhost';
$dbname = 'restaurant_db';
$username = 'root';
$password = 'root123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Успешно свързване с базата данни!"; // по желание
} catch (PDOException $e) {
    die("Грешка при свързване: " . $e->getMessage());
}
?>