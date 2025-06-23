<?php
// Connect to both databases
$pdo = new PDO("mysql:host=localhost;dbname=cybertraining;charset=utf8mb4", "root", "");
$cee = new PDO("mysql:host=localhost;dbname=cee_db;charset=utf8mb4", "root", "");

// Get the employee email from session
$emp_email = $_SESSION['employee_email'] ?? '';

// 1. Fetch Employee Profile & Department
$empStmt = $pdo->prepare("SELECT employees.id as emp_id, employees.name, employees.contact_number, departments.id as dept_id, departments.department_name
                          FROM employees 
                          JOIN departments ON employees.department_id = departments.id
                          WHERE employees.email = ?");
$empStmt->execute([$emp_email]);
$employee = $empStmt->fetch(PDO::FETCH_ASSOC);

// 2. Course count assigned to this department
$courseStmt = $pdo->prepare("SELECT COUNT(*) FROM course_departments WHERE department_id = ?");
$courseStmt->execute([$employee['dept_id']]);
$courseCount = $courseStmt->fetchColumn();

// 3. Get matching examinee_id from cee_db
$exmneStmt = $cee->prepare("SELECT exmne_id FROM examinee_tbl WHERE exmne_email = ?");
$exmneStmt->execute([$emp_email]);
$examinee = $exmneStmt->fetch(PDO::FETCH_ASSOC);
$exmne_id = $examinee['exmne_id'] ?? null;

// 4. Quiz participation count
$quizCount = 0;
if ($exmne_id) {
    $quizStmt = $cee->prepare("SELECT COUNT(DISTINCT exam_id) FROM exam_attempt WHERE exmne_id = ?");
    $quizStmt->execute([$exmne_id]);
    $quizCount = $quizStmt->fetchColumn();
}

// 5. Feedback count from both DBs
$fbStmt1 = $cee->prepare("SELECT COUNT(*) FROM feedbacks_tbl WHERE exmne_id = ?");
$fbStmt1->execute([$exmne_id]);
$fbCount1 = $fbStmt1->fetchColumn();

$fbStmt2 = $pdo->prepare("SELECT COUNT(*) FROM feedback WHERE email = ?");
$fbStmt2->execute([$emp_email]);
$fbCount2 = $fbStmt2->fetchColumn();

$totalFeedbacks = $fbCount1 + $fbCount2;

// 6. Score Summary per Quiz (title + percentage)
$quizScores = [];

if ($exmne_id) {
    // Get all exams the employee has attempted
    $examStmt = $cee->prepare("SELECT DISTINCT exam_id FROM exam_attempt WHERE exmne_id = ?");
    $examStmt->execute([$exmne_id]);
    $exams = $examStmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($exams as $exam_id) {
        // Get total questions in that exam
        $totalQStmt = $cee->prepare("SELECT COUNT(*) FROM exam_question_tbl WHERE exam_id = ?");
        $totalQStmt->execute([$exam_id]);
        $totalQ = $totalQStmt->fetchColumn();

        // Get correct answers count
        $correctStmt = $cee->prepare("
            SELECT COUNT(*) FROM exam_answers AS ea
            JOIN exam_question_tbl AS q ON ea.quest_id = q.eqt_id
            WHERE ea.axmne_id = ? AND ea.exam_id = ? AND ea.exans_answer = q.exam_answer
        ");
        $correctStmt->execute([$exmne_id, $exam_id]);
        $correct = $correctStmt->fetchColumn();

        // Get exam title
        $titleStmt = $cee->prepare("SELECT ex_title FROM exam_tbl WHERE ex_id = ?");
        $titleStmt->execute([$exam_id]);
        $examTitle = $titleStmt->fetchColumn();

        $scorePercent = ($totalQ > 0) ? round(($correct / $totalQ) * 100) : 0;
        $quizScores[] = [
            'title' => $examTitle,
            'score' => $scorePercent
        ];
    }
}
?>
