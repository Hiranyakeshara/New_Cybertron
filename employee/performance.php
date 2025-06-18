<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

// Get session data
$emp_name = $_SESSION["username"];
$emp_email = $_SESSION["employee_email"];

// Connect to cee_db
try {
    $quizPdo = new PDO("mysql:host=localhost;dbname=cee_db", "root", "");
    $quizPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Query quiz results
$sql = "
    SELECT 
        et.ex_title,
        COUNT(eq.eqt_id) AS total_questions,
        SUM(CASE WHEN eq.exam_answer = ea.exans_answer THEN 1 ELSE 0 END) AS correct_answers
    FROM exam_attempt ea1
    JOIN examinee_tbl e ON ea1.exmne_id = e.exmne_id
    JOIN exam_tbl et ON ea1.exam_id = et.ex_id
    JOIN exam_answers ea ON ea.axmne_id = e.exmne_id AND ea.exam_id = et.ex_id
    JOIN exam_question_tbl eq ON eq.eqt_id = ea.quest_id
    WHERE e.exmne_email = ?
    GROUP BY et.ex_id
    ORDER BY et.ex_title
";

$stmt = $quizPdo->prepare($sql);
$stmt->execute([$emp_email]);
$quiz_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Quiz Performance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen px-4 py-10">
    <?php include_once("./include/employee_sidebar.php"); ?>

<div class="max-w-6xl mx-auto space-y-10">
    <h1 class="text-3xl font-bold">Welcome, <?= htmlspecialchars($emp_name) ?></h1>

    <div class="bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">📊 My Quiz Results</h2>

        <?php if (empty($quiz_results)): ?>
            <p class="text-gray-300">No quiz results found for your account.</p>
        <?php else: ?>
            <canvas id="quizChart"></canvas>
            <table class="w-full mt-6 text-left text-sm text-gray-200">
                <thead class="bg-gray-700 text-white">
                <tr>
                    <th class="p-2">Quiz Title</th>
                    <th class="p-2 text-center">Total Questions</th>
                    <th class="p-2 text-center">Correct Answers</th>
                    <th class="p-2 text-center">Score (%)</th>
                </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                <?php foreach ($quiz_results as $row): ?>
                    <tr>
                        <td class="p-2"><?= htmlspecialchars($row['ex_title']) ?></td>
                        <td class="p-2 text-center"><?= $row['total_questions'] ?></td>
                        <td class="p-2 text-center"><?= $row['correct_answers'] ?></td>
                        <td class="p-2 text-center">
                            <?= round(($row['correct_answers'] / $row['total_questions']) * 100, 2) ?>%
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($quiz_results)): ?>
<script>
    const ctx = document.getElementById('quizChart').getContext('2d');
    const quizChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($quiz_results, 'ex_title')) ?>,
            datasets: [{
                label: 'Score (%)',
                data: <?= json_encode(array_map(fn($r) => round(($r['correct_answers'] / $r['total_questions']) * 100, 2), $quiz_results)) ?>,
                backgroundColor: 'rgba(96, 165, 250, 0.7)',
                borderColor: 'rgba(96, 165, 250, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#E5E7EB' }
                },
                x: {
                    ticks: { color: '#E5E7EB' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#E5E7EB' }
                }
            }
        }
    });
</script>
<?php endif; ?>

</body>
</html>
