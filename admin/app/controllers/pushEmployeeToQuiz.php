<?php
session_start();  // Start session for flash message support

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/quiz_db.php';

global $pdo, $quizPdo;

if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {
        $employeeEmail = $employee['email'];

        $checkStmt = $quizPdo->prepare("SELECT COUNT(*) FROM examinee_tbl WHERE exmne_email = ?");
        $checkStmt->execute([$employeeEmail]);
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            $_SESSION['message'] = '⚠️ This employee is already added to the Quiz platform.';
            $_SESSION['message_type'] = 'warning';
        } else {
            $insertStmt = $quizPdo->prepare("
                INSERT INTO examinee_tbl (
                    exmne_fullname, exmne_course, exmne_gender,
                    exmne_birthdate, exmne_year_level,
                    exmne_email, exmne_password
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $insertStmt->execute([
                $employee['name'],
                $employee['department_id'],
                '',
                '',
                '',
                $employee['email'],
                $employee['password'] ?? '123456'
            ]);

            $_SESSION['message'] = '✅ Employee successfully added to Quiz platform.';
            $_SESSION['message_type'] = 'success';
        }

        header("Location: /New_Cybertron/admin/public/employee/viewAll.php");
        exit();
    } else {
        $_SESSION['message'] = '❌ Employee not found.';
        $_SESSION['message_type'] = 'error';
        header("Location: /New_Cybertron/admin/public/employee/viewAll.php");
        exit();
    }
} else {
    $_SESSION['message'] = '❌ Invalid request.';
    $_SESSION['message_type'] = 'error';
    header("Location: /New_Cybertron/admin/public/employee/viewAll.php");
    exit();
}
