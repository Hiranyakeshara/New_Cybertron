<?php
session_start();

require_once __DIR__ . '/config/db.php';       // cybertraining
require_once __DIR__ . '/config/quiz_db.php';  // cee_db

global $pdo, $quizPdo;

if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    // Fetch employee
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {
        $employeeEmail = $employee['email'];

        // ✅ Fetch department_code from department_id
        $deptStmt = $pdo->prepare("SELECT department_code FROM departments WHERE id = ?");
        $deptStmt->execute([$employee['department_id']]);
        $dept = $deptStmt->fetch(PDO::FETCH_ASSOC);

        $departmentCode = $dept ? $dept['department_code'] : null;

        if (!$departmentCode) {
            $_SESSION['message'] = '❌ Department code not found.';
            $_SESSION['message_type'] = 'error';
        } else {
            // Check if email already exists in quiz DB
            $checkStmt = $quizPdo->prepare("SELECT COUNT(*) FROM examinee_tbl WHERE exmne_email = ?");
            $checkStmt->execute([$employeeEmail]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                $_SESSION['message'] = '⚠️ This employee is already added to the Quiz platform.';
                $_SESSION['message_type'] = 'warning';
            } else {
                // Insert into examinee_tbl
                $insertStmt = $quizPdo->prepare("
                    INSERT INTO examinee_tbl (
                        exmne_fullname, exmne_course, exmne_gender,
                        exmne_birthdate, exmne_year_level,
                        exmne_email, exmne_password
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                $insertStmt->execute([
                    $employee['name'],
                    $departmentCode, // ✅ use department_code instead of ID
                    '', '', '',       // gender, birthdate, year_level
                    $employeeEmail,
                    $employee['password'] ?? '123456'
                ]);

                $_SESSION['message'] = '✅ Employee successfully added to Quiz platform.';
                $_SESSION['message_type'] = 'success';
            }
        }
    } else {
        $_SESSION['message'] = '❌ Employee not found.';
        $_SESSION['message_type'] = 'error';
    }

    header("Location: /New_Cybertron/admin/public/employee/viewAll.php");
    exit();
} else {
    $_SESSION['message'] = '❌ Invalid request.';
    $_SESSION['message_type'] = 'error';
    header("Location: /New_Cybertron/admin/public/employee/viewAll.php");
    exit();
}
