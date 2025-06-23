<?php
$cyber = new mysqli("localhost", "root", "", "cybertraining");
$cee = new mysqli("localhost", "root", "", "cee_db");

if ($cyber->connect_error || $cee->connect_error) {
    die("Connection failed: " . $cyber->connect_error . " / " . $cee->connect_error);
}

$employees = $cyber->query("SELECT employees.*, departments.department_name 
                            FROM employees 
                            JOIN departments ON employees.department_id = departments.id");

$employeeData = [];

while ($emp = $employees->fetch_assoc()) {
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

    $avgScore = $quizCount > 0 ? round(array_sum($scores) / $quizCount, 2) : 0;
    $rank = "Unranked";
    $badgeColor = "gray-400";

    if ($avgScore < 50) {
        $rank = "❌ Not Good";
        $badgeColor = "red-500";
    } elseif ($avgScore < 80) {
        $rank = "✔ Average";
        $badgeColor = "yellow-500";
    } else {
        $rank = "🌟 Excellent";
        $badgeColor = "green-600";
    }

    $employeeData[] = [
        'eid' => $eid,
        'name' => $name,
        'email' => $email,
        'department' => $dept,
        'course' => $course,
        'quizCount' => $quizCount,
        'avgScore' => $avgScore,
        'rank' => $rank,
        'badgeColor' => $badgeColor,
        'labels' => $labels,
        'scores' => $scores
    ];
}

// Sort for leaderboard
usort($employeeData, fn($a, $b) => $b['avgScore'] <=> $a['avgScore']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Quiz Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-6">

    <!-- Leaderboard -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold mb-4 text-gray-800">🏆 Leaderboard - Top 3 Performers</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach (array_slice($employeeData, 0, 3) as $top): ?>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($top['name']) ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($top['email']) ?></p>
                    <p class="text-sm"><strong>Avg. Score:</strong> <?= $top['avgScore'] ?>%</p>
                    <p class="text-sm">
                        <span class="inline-block px-2 py-1 text-sm font-semibold text-white bg-<?= $top['badgeColor'] ?> rounded">
                            <?= $top['rank'] ?>
                        </span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- All Employee Cards -->
    <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($employeeData as $emp): ?>
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                <h2 class="text-xl font-semibold text-gray-800 mb-1 capitalize"><?= htmlspecialchars($emp['name']) ?></h2>
                <p class="text-sm text-gray-600"><strong>Email:</strong> <?= htmlspecialchars($emp['email']) ?></p>
                <p class="text-sm text-gray-600"><strong>Department:</strong> <?= htmlspecialchars($emp['department']) ?></p>
                <p class="text-sm text-gray-600"><strong>Course:</strong> <?= htmlspecialchars($emp['course']) ?></p>
                <p class="text-sm text-gray-600"><strong>Quizzes Taken:</strong> <?= $emp['quizCount'] ?></p>
                <p class="text-sm text-gray-600"><strong>Average Score:</strong> <?= $emp['avgScore'] ?>%</p>
                <p class="text-sm mb-2">
                    <strong>Rank:</strong>
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-<?= $emp['badgeColor'] ?> rounded-full">
                        <?= $emp['rank'] ?>
                    </span>
                </p>

                <?php if ($emp['quizCount'] > 0): ?>
                <div class="bg-gray-100 p-3 rounded-md">
                    <canvas id="chart<?= $emp['eid'] ?>" height="150"></canvas>
                    <script>
                        const ctx<?= $emp['eid'] ?> = document.getElementById('chart<?= $emp['eid'] ?>').getContext('2d');
                        new Chart(ctx<?= $emp['eid'] ?>, {
                            type: 'bar',
                            data: {
                                labels: <?= json_encode($emp['labels']) ?>,
                                datasets: [{
                                    label: 'Score (%)',
                                    data: <?= json_encode($emp['scores']) ?>,
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
        <?php endforeach; ?>
    </div>

</body>
</html>
