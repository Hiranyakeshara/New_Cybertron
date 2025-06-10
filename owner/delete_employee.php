<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

include 'db/config.php'; // Include database connection

// Check if the employee id is set
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $owner_id = $_SESSION['owner_id'];

    // Delete the employee from the database
    try {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ? AND owner_id = ?");
        $stmt->execute([$employee_id, $owner_id]);

        // Redirect to dashboard with a success message
        header("Location: manage_employees.php?status=deleted");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
