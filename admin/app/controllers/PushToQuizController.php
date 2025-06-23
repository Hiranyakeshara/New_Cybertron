<?php
class PushToQuizController
{
    public function handlePush()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        include_once __DIR__ . '/config/quiz_db.php';
        global $quizPdo;

        $cou_id = htmlspecialchars(trim($_POST['cou_id'] ?? ''));
        $cou_name = htmlspecialchars(trim($_POST['cou_name'] ?? ''));

        if (!$cou_id || !$cou_name) {
            $_SESSION['push_status'] = '❌ Missing department data.';
        } else {
            $check = $quizPdo->prepare("SELECT * FROM course_tbl WHERE cou_id = ?");
            $check->execute([$cou_id]);

            if ($check->rowCount() > 0) {
                $_SESSION['push_status'] = '⚠️ Department already pushed to Quiz Platform.';
            } else {
                $insert = $quizPdo->prepare("INSERT INTO course_tbl (cou_id, cou_name) VALUES (?, ?)");
                if ($insert->execute([$cou_id, $cou_name])) {
                    $_SESSION['push_status'] = '✅ Successfully pushed to Quiz Platform.';
                } else {
                    $_SESSION['push_status'] = '❌ Insert failed.';
                }
            }
        }

        header("Location: /New_Cybertron/admin/public/department/viewDepartments");
        exit;
    }

    // Optional default index method
    public function index()
    {
        echo "PushToQuizController is ready.";
    }
}
