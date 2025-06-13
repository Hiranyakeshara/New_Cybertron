<?php
require_once __DIR__ . '/config/db.php';        // cybertraining DB
require_once __DIR__ . '/config/quiz_db.php';   // cee_db

global $pdo, $quizPdo;

if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    // Step 1: Fetch employee data from cybertraining DB
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {
        $employeeEmail = $employee['email'];

        // Step 2: Check if already exists in cee_db.examinee_tbl
        $checkStmt = $quizPdo->prepare("SELECT COUNT(*) FROM examinee_tbl WHERE exmne_email = ?");
        $checkStmt->execute([$employeeEmail]);
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            // Already exists
            header("Location: /New_Cybertron/admin/public/employee/viewAll?exists=1");
            exit();
        }

        // Step 3: Insert into examinee_tbl in cee_db
        $insertStmt = $quizPdo->prepare("
            INSERT INTO examinee_tbl (
                exmne_fullname,
                exmne_course,
                exmne_gender,
                exmne_birthdate,
                exmne_year_level,
                exmne_email,
                exmne_password
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $insertStmt->execute([
            $employee['name'],             // Fullname
            $employee['department_id'],    // Course ID
            '',                            // Gender
            '',                            // Birthdate
            '',                            // Year Level
            $employee['email'],            // Email
            $employee['password'] ?? '123456'  // Password or fallback
        ]);

        header("Location: /New_Cybertron/admin/public/employee/viewAll?success=1");
        exit();
    } else {
        echo "❌ Employee not found.";
    }
} else {
    echo "❌ Invalid request.";
}
