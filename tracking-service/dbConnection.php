<?php
$dsn = "pgsql:host=db;port=5432;dbname=group-5;user=group-5;password=group-5";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Установка режима обработки ошибок
    // echo "Connected to the database successfully!";
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed.");
}
?>