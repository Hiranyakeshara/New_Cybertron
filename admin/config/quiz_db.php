<?php
try {
    $quizPdo = new PDO("mysql:host=localhost;dbname=cee_db", "root", ""); // Adjust credentials
    $quizPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection to quiz DB failed: " . $e->getMessage());
}
