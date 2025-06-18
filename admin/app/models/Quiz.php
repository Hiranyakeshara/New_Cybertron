<?php
require_once __DIR__ . '/../controllers/config/quiz_db.php';

class Quiz {
    public function getResults() {
        global $quizPdo;

        $sql = "
            SELECT 
                e.exmne_fullname,
                e.exmne_email,
                et.ex_title,
                COUNT(eq.eqt_id) AS total_questions,
                SUM(CASE WHEN eq.exam_answer = ea.exans_answer THEN 1 ELSE 0 END) AS correct_answers
            FROM exam_attempt ea1
            JOIN examinee_tbl e ON ea1.exmne_id = e.exmne_id
            JOIN exam_tbl et ON ea1.exam_id = et.ex_id
            JOIN exam_answers ea ON ea.axmne_id = e.exmne_id AND ea.exam_id = et.ex_id
            JOIN exam_question_tbl eq ON eq.eqt_id = ea.quest_id
            GROUP BY e.exmne_id, et.ex_id
            ORDER BY e.exmne_fullname, et.ex_title
        ";

        $stmt = $quizPdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
