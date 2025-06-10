<?php
require_once __DIR__ . '/config/db.php';    // Main DB (cybertraining)
require_once __DIR__ . '/config/quiz_db.php';   // Secondary DB (cee_db)

// ✅ Now explicitly define the global $pdo and $quizPdo
global $pdo, $quizPdo;

if (isset($_GET['id'])) {
    $courseId = intval($_GET['id']);

    // Step 1: Fetch course data from cybertraining DB
    $stmt = $pdo->prepare("SELECT course_name FROM courses WHERE id = ?");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        // Step 2: Insert into cee_db.course_tbl
        $insertStmt = $quizPdo->prepare("INSERT INTO course_tbl (cou_name, cou_created) VALUES (?, NOW())");
        $insertStmt->execute([$course['course_name']]);

        header("Location: /New_Cybertron/admin/public/courses?success=1");
        exit();
    } else {
        echo "❌ Course not found.";
    }
} else {
    echo "❌ Invalid request.";
}
