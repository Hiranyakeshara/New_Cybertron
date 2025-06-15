<?php
require_once __DIR__ . '/../controllers/config/quiz_db.php';
require_once __DIR__ . '/../controllers/config/db.php';

class Quiz {
    public function getResults($departmentId = null) {
        global $quizPdo, $pdo;

        $sql = "
            SELECT 
                emp.name AS employee_name,
                emp.email,
                dept.name AS department_name,
                et.ex_title,
                COUNT(eq.eqt_id) AS total_questions,
                SUM(CASE WHEN eq.exam_answer = ea.exans_answer THEN 1 ELSE 0 END) AS correct_answers
            FROM cybertraining.employees emp
            INNER JOIN cybertraining.departments dept ON emp.department_id = dept.id
            INNER JOIN cee_db.examinee_tbl exm ON exm.exmne_email = emp.email
            INNER JOIN cee_db.exam_attempt ea1 ON ea1.exmne_id = exm.exmne_id
            INNER JOIN cee_db.exam_tbl et ON et.ex_id = ea1.exam_id
            INNER JOIN cee_db.exam_answers ea ON ea.exmne_id = exm.exmne_id AND ea.exam_id = et.ex_id
            INNER JOIN cee_db.exam_question_tbl eq ON eq.quest_id = ea.quest_id AND eq.exam_id = ea.exam_id
            WHERE 1 = 1
        ";

        if ($departmentId !== null) {
            $sql .= " AND emp.department_id = :dept_id";
        }

        $sql .= "
            GROUP BY emp.name, emp.email, dept.name, et.ex_title
            ORDER BY dept.name, emp.name, et.ex_title
        ";

        $stmt = $quizPdo->prepare($sql);

        if ($departmentId !== null) {
            $stmt->bindParam(':dept_id', $departmentId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
