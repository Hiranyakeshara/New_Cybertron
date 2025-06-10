<?php
session_start();
include_once("db/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $case_id = $_POST['case_id'];
    $selected_answer = $_POST['answer'];
    $employee_id = $_SESSION['employee_id']; // Get logged-in user ID

    try {
        $pdo->beginTransaction(); // Start transaction

        // Fetch the correct answer and allocated marks
        $query = "SELECT correct_answer, allocated_marks FROM case_studies WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$case_id]);
        $case_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$case_data) {
            throw new Exception("Case study not found.");
        }

        $correct_answer = $case_data['correct_answer'];
        $allocated_marks = (int)$case_data['allocated_marks']; // Ensure integer type

        // Determine marks scored
        $case_marks = ($selected_answer === $correct_answer) ? $allocated_marks : 0;

        // Store the answer in the case_study_answers table
        $insert_query = "INSERT INTO case_study_answers (employee_id, case_id, selected_answer, correct_answer, case_marks, answered_at) 
                         VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute([$employee_id, $case_id, $selected_answer, $correct_answer, $case_marks]);

        // Ensure the employee exists in employee_score
        $check_query = "SELECT COUNT(*) FROM employee_score WHERE emp_id = ?";
        $stmt = $pdo->prepare($check_query);
        $stmt->execute([$employee_id]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            // Insert the employee if not present
            $insert_score_query = "INSERT INTO employee_score (emp_id, total_marks_participated, total_marks_scored) 
                                   VALUES (?, 0, 0)";
            $stmt = $pdo->prepare($insert_score_query);
            $stmt->execute([$employee_id]);
        }

        // Fetch total allocated marks for all answered case studies by this employee
        $sum_query = "SELECT SUM(cs.allocated_marks) AS total_allocated_marks 
                      FROM case_study_answers csa 
                      JOIN case_studies cs ON csa.case_id = cs.id 
                      WHERE csa.employee_id = ?";
        $stmt = $pdo->prepare($sum_query);
        $stmt->execute([$employee_id]);
        $total_allocated_marks = (int)$stmt->fetchColumn(); // Ensure integer

        // Update employee_score table
        $update_query = "UPDATE employee_score 
                         SET total_marks_participated = ?, 
                             total_marks_scored = total_marks_scored + ? 
                         WHERE emp_id = ?";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([$total_allocated_marks, $case_marks, $employee_id]);

        $pdo->commit(); // Commit transaction

        // Provide feedback
        $_SESSION['feedback'] = ($case_marks > 0) 
            ? "✅ Correct answer! You earned $case_marks points." 
            : "❌ Incorrect! The correct answer was: " . htmlspecialchars($correct_answer);

    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback in case of error
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    // Redirect back
    header("Location: emp_course.php");
    exit();
}
?>
