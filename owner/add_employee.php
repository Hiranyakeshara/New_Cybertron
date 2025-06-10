<?php
session_start();
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_id = $_POST['owner_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction(); // Start transaction

        // Insert into employees table
        $stmt = $pdo->prepare("INSERT INTO employees (owner_id, employee_name, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$owner_id, $name, $email, $username, $password]);

        // Get the last inserted employee ID
        $emp_id = $pdo->lastInsertId();

        // Insert into employee_score table
        $stmt = $pdo->prepare("INSERT INTO employee_score (emp_id, name, email, total_marks_participated, total_marks_scored, current_rank) VALUES (?, ?, ?, 0, 0, 0)");
        $stmt->execute([$emp_id, $name, $email]);

        $pdo->commit(); // Commit transaction

        $_SESSION['success'] = "Employee added successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack(); // Rollback if error occurs
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to the employee management page
header("Location: manage_employees.php");
exit();
?>
