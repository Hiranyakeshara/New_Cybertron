<?php
session_start();
include 'db/config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['owner_login'])) {
    $reg_number = trim($_POST['reg_number']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM company_owners WHERE reg_number = ? AND email = ?");
        $stmt->execute([$reg_number, $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['owner_id'] = $user['id'];
                $_SESSION['owner_name'] = $user['company_name'];
                $_SESSION['owner_email'] = $user['email'];

                $_SESSION['success'] = "Login successful!";
                header("Location: owner/owner_dashboard.php"); // Redirect to owner dashboard
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password!";
            }
        } else {
            $_SESSION['error'] = "No account found with these details!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header("Location: company_owner_portal.php"); // Redirect back to login page
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: company_owner.php");
    exit();
}
?>
