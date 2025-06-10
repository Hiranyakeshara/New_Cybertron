<?php
session_start();
include 'db/config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['owner_reg'])) {
    
    // Retrieve form data
    $reg_number = $_POST['reg_number'];
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

    try {
        // Check if the email is already registered
        $stmt = $pdo->prepare("SELECT * FROM company_owners WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email is already registered!";
            header("Location: company_owner.php");
            exit();
        }

        // Insert user into the database
        $stmt = $pdo->prepare("INSERT INTO company_owners (reg_number, company_name, email, password) VALUES (?, ?, ?, ?)");
        $execute = $stmt->execute([$reg_number, $company_name, $email, $password]);

        if ($execute) {
            $_SESSION['success'] = "Registration successful! You can now login.";
        } else {
            $_SESSION['error'] = "Error in registration. Please try again.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header("Location: company_owner.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: company_owner.php");
    exit();
}
?>
