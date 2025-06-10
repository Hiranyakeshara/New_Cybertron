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
        // Step 2: Insert into examinee_tbl in cee_db
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
            $employee['name'],             // fullname
            $employee['department_id'],    // course (just mapping department)
            '',                            // gender (optional)
            '',                            // birthdate (optional)
            '',                            // year level (optional)
            $employee['email'],            // email
            $employee['password'] ?? '123456'  // default password if missing
        ]);

        header("Location: /New_Cybertron/admin/public/employee/viewAll?success=1");
        exit();
    } else {
        echo "❌ Employee not found.";
    }
} else {
    echo "❌ Invalid request.";
}
