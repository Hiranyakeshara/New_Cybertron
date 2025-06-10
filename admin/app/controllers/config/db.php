<?php
global $pdo;
try {
    $pdo = new PDO("mysql:host=localhost;dbname=cybertraining", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection to cybertraining DB failed: " . $e->getMessage());
}
