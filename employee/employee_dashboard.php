<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

// Session user info
$emp_name  = $_SESSION['username'];
$emp_email = $_SESSION['employee_email'];

// Load stats
include 'fetch_employee.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CyberTrone - Employee Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen">

  <?php include "./include/employee_sidebar.php"; ?>

  <div class="content p-6 space-y-10">

    <!-- Header -->
    <header class="text-center">
      <h1 class="text-4xl font-bold">Welcome, <?= htmlspecialchars($emp_name) ?></h1>
      <p class="text-gray-400"><?= htmlspecialchars($emp_email) ?></p>
    </header>

    <!-- Summary Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
      <div class="bg-blue-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-2">👤 Profile</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($employee['contact_number']) ?></p>
      </div>

      <div class="bg-green-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-2">🏢 Department</h3>
        <p><strong>Department:</strong> <?= htmlspecialchars($employee['department_name']) ?></p>
        <p><strong>Assigned Courses:</strong> <?= $courseCount ?></p>
      </div>

      <div class="bg-purple-800 p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-2">📊 Stats</h3>
        <p><strong>Quizzes Participated:</strong> <?= $quizCount ?></p>
        <p><strong>Total Feedbacks:</strong> <?= $totalFeedbacks ?></p>
      </div>
    </section>

    <!-- Quiz Score Summary -->
    <section class="bg-gray-800 p-6 rounded-lg shadow">
      <h2 class="text-2xl font-semibold mb-4">📈 Your Quiz Performance</h2>
      <?php if (empty($quizScores)): ?>
        <p class="text-gray-300">No quiz data available yet.</p>
      <?php else: ?>
        <div class="space-y-6">
          <?php foreach ($quizScores as $quiz): ?>
            <div>
              <h4 class="text-lg font-medium mb-1"><?= htmlspecialchars($quiz['title']) ?> - <?= $quiz['score'] ?>%</h4>
              <div class="w-full bg-gray-700 rounded h-4">
                <div class="bg-blue-500 h-4 rounded" style="width: <?= $quiz['score'] ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

  </div>

</body>
</html>
