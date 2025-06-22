<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Employee Quiz Performance</h1>

        <input
            type="text"
            id="searchInput"
            onkeyup="searchEmployees()"
            placeholder="Search by employee name..."
            class="w-full p-3 rounded-lg border border-gray-300 mb-6 shadow"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($quizResults as $result): ?>
                <div class="employee-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition" data-name="<?= htmlspecialchars($result['exmne_fullname']) ?>">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2"><?= htmlspecialchars($result['exmne_fullname']) ?></h2>
                    <p class="text-gray-600 mb-1"><strong>Email:</strong> <?= htmlspecialchars($result['exmne_email']) ?></p>
                    <p class="text-gray-600 mb-1"><strong>Quiz Title:</strong> <?= htmlspecialchars($result['ex_title']) ?></p>
                    <p class="text-gray-600 mb-1">
                        <strong>Score:</strong> <?= $result['correct_answers'] ?> / <?= $result['total_questions'] ?>
                    </p>
                    <div class="mt-2">
                        <div class="bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full" style="width: <?= round(($result['correct_answers'] / max(1, $result['total_questions'])) * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
