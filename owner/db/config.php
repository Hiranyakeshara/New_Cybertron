<?php
// Database Configuration
$host = "localhost";  // Change to your database host (e.g., 127.0.0.1 or actual server IP)
$dbname = "cyber";  // Change to your database name
$username = "root";  // Change to your database username
$password = "";  // Change to your database password

try {
    // Creating a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Display error and stop execution if connection fails
    die("Database Connection Failed: " . $e->getMessage());
}
?>
