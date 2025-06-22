<?php
require_once __DIR__ . '/../core/Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $nic = trim($_POST['nic']);
    $contact = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';

    if (!$id || !$name || !$username || !$nic || !$contact || !$email) {
        $_SESSION['message'] = "All fields except password are required.";
        $_SESSION['message_type'] = "error";
        header("Location: /New_Cybertron/admin/public/employee");
        exit;
    }

    try {
        $db = new Database();
        $pdo = $db->getConnection();

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "UPDATE employees SET name = :name, username = :username, nic = :nic, contact_number = :contact, email = :email, password = :password WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':username' => $username,
                ':nic' => $nic,
                ':contact' => $contact,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':id' => $id
            ]);
        } else {
            $query = "UPDATE employees SET name = :name, username = :username, nic = :nic, contact_number = :contact, email = :email WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':username' => $username,
                ':nic' => $nic,
                ':contact' => $contact,
                ':email' => $email,
                ':id' => $id
            ]);
        }

        $_SESSION['message'] = "Employee updated successfully.";
        $_SESSION['message_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: /New_Cybertron/admin/public/employee");
    exit;
} else {
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message_type'] = "error";
    header("Location: /New_Cybertron/admin/public/employee");
    exit;
}
