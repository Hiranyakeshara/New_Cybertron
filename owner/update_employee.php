<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];

include 'db/config.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Update employee details in the database
    try {
        $stmt = $pdo->prepare("UPDATE employees SET employee_name = ?, email = ?, username = ? WHERE id = ? AND owner_id = ?");
        $stmt->execute([$employee_name, $email, $username, $employee_id, $owner_id]);

        // Redirect to dashboard with a success message
        header("Location: manage_employees.php?status=success");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
