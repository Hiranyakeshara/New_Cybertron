<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="ml-[220px] p-6 font-sans">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">📊 Quiz Results</h2>

    <div class="overflow-x-auto mb-10">
        <table class="min-w-full divide-y divide-gray-200 shadow rounded-lg">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Name</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Email</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Quiz</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Total Questions</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Correct Answers</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Score (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white text-center">
                <?php
                $totalCorrect = 0;
                $totalQuestions = 0;
                $labels = [];
                $scores = [];

                foreach ($results as $res):
                    $percentage = $res['total_questions'] > 0 ? round(($res['correct_answers'] / $res['total_questions']) * 100, 2) : 0;
                    $labels[] = "{$res['exmne_fullname']} - {$res['ex_title']}";
                    $scores[] = $percentage;
                    $totalCorrect += $res['correct_answers'];
                    $totalQuestions += $res['total_questions'];
                ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?= htmlspecialchars($res['exmne_fullname']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($res['exmne_email']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($res['ex_title']) ?></td>
                        <td class="px-6 py-4"><?= $res['total_questions'] ?></td>
                        <td class="px-6 py-4"><?= $res['correct_answers'] ?></td>
                        <td class="px-6 py-4"><?= $percentage ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow border">
            <h3 class="text-lg font-semibold mb-2">Score Percentage by User/Quiz</h3>
            <canvas id="barChart"></canvas>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border">
            <h3 class="text-lg font-semibold mb-2">Overall Accuracy</h3>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const barCtx = document.getElementById('barChart').getContext('2d');
const pieCtx = document.getElementById('pieChart').getContext('2d');

new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Score (%)',
            data: <?= json_encode($scores) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Correct', 'Incorrect'],
        datasets: [{
            data: [<?= $totalCorrect ?>, <?= $totalQuestions - $totalCorrect ?>],
            backgroundColor: ['#10B981', '#EF4444']
        }]
    },
    options: {
        responsive: true
    }
});
</script>
