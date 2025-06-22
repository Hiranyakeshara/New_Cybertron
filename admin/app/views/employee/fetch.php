<?php
$cyber = new mysqli("localhost", "root", "", "cybertraining");
$cee = new mysqli("localhost", "root", "", "cee_db");

if ($cyber->connect_error || $cee->connect_error) {
    die("Connection failed: " . $cyber->connect_error . " / " . $cee->connect_error);
}

$employees = $cyber->query("SELECT employees.*, departments.department_name 
                            FROM employees 
                            JOIN departments ON employees.department_id = departments.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Quiz Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f4f7fa;
        }
        .gradient-header {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
        }
        .card-shadow {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="min-h-screen py-10 px-6">



    <!-- Employee Cards -->
    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
        <?php while ($emp = $employees->fetch_assoc()):
            $email = $emp['email'];
            $dept = $emp['department_name'];

            $examineeRes = $cee->query("SELECT * FROM examinee_tbl WHERE exmne_email = '$email'");
            if ($examineeRes->num_rows == 0) continue;

            $examinee = $examineeRes->fetch_assoc();
            $eid = $examinee['exmne_id'];
            $name = $examinee['exmne_fullname'];
            $courseId = $examinee['exmne_course'];

            $course = "Unknown";
            $courseRes = $cee->query("SELECT cou_name FROM course_tbl WHERE cou_id = '$courseId'");
            if ($courseRes && $courseRes->num_rows > 0) {
                $course = $courseRes->fetch_assoc()['cou_name'];
            }

            $examRes = $cee->query("SELECT exam_tbl.ex_id, exam_tbl.ex_title 
                                    FROM exam_attempt 
                                    JOIN exam_tbl ON exam_tbl.ex_id = exam_attempt.exam_id 
                                    WHERE exam_attempt.exmne_id = '$eid'");

            $labels = [];
            $scores = [];
            $quizCount = 0;

            while ($exam = $examRes->fetch_assoc()) {
                $examId = $exam['ex_id'];
                $title = $exam['ex_title'];

                $qCountRes = $cee->query("SELECT COUNT(*) as total FROM exam_question_tbl WHERE exam_id = '$examId'");
                $qCount = $qCountRes->fetch_assoc()['total'];

                $correctRes = $cee->query("SELECT COUNT(*) as correct 
                                           FROM exam_answers AS a 
                                           JOIN exam_question_tbl AS q ON a.quest_id = q.eqt_id 
                                           WHERE a.axmne_id = '$eid' AND a.exam_id = '$examId' 
                                           AND a.exans_answer = q.exam_answer");
                $correct = $correctRes->fetch_assoc()['correct'];

                $score = $qCount > 0 ? round(($correct / $qCount) * 100, 2) : 0;
                $labels[] = $title;
                $scores[] = $score;
                $quizCount++;
            }
        ?>
        <div class="bg-white card-shadow rounded-xl p-6 transition transform hover:-translate-y-1 hover:shadow-xl">
            <h2 class="text-xl font-semibold text-gray-800 mb-1 capitalize"><?= htmlspecialchars($name) ?></h2>
            <p class="text-sm text-gray-600"><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p class="text-sm text-gray-600"><strong>Department:</strong> <?= htmlspecialchars($dept) ?></p>
            <p class="text-sm text-gray-600"><strong>Course:</strong> <?= htmlspecialchars($course) ?></p>
            <p class="text-sm text-gray-600 mb-2"><strong>Quizzes Taken:</strong> <?= $quizCount ?></p>

            <?php if ($quizCount > 0): ?>
            <div class="bg-gray-50 p-3 rounded-md">
                <canvas id="chart<?= $eid ?>" height="150"></canvas>
                <script>
                    const ctx<?= $eid ?> = document.getElementById('chart<?= $eid ?>').getContext('2d');
                    new Chart(ctx<?= $eid ?>, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($labels) ?>,
                            datasets: [{
                                label: 'Score (%)',
                                data: <?= json_encode($scores) ?>,
                                backgroundColor: '#3b82f6',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: val => val + '%'
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
            <?php else: ?>
            <p class="text-red-600 italic mt-3">⚠ No quizzes attempted yet.</p>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
