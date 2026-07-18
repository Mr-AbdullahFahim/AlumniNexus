<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'AlumniNexus';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP DATABASE IF EXISTS `$db`");
    $pdo->exec("CREATE DATABASE `$db`");
    echo "Database dropped and recreated successfully.\n";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
