<?php
$pdo = new PDO("mysql:host=localhost;dbname=cybertraining;charset=utf8mb4", "root", "");
$cee = new PDO("mysql:host=localhost;dbname=cee_db;charset=utf8mb4", "root", "");

// Fetch all departments
$deptStmt = $pdo->query("SELECT * FROM departments");
$departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Department Quiz Summary</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    function filterDepartments() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const cards = document.querySelectorAll(".department-card");
      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(input) ? "block" : "none";
      });
    }
  </script>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Jumbotron -->
  <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 text-center shadow-md">
    <h1 class="text-5xl font-bold mb-2">📊 Departmental Quiz Overview</h1>
    <p class="text-lg opacity-90">Explore course allocations, quizzes, and enrolled users by department</p>
  </div>

  <!-- Search Input -->
  <div class="max-w-4xl mx-auto my-8 px-4">
    <input type="text" id="searchInput" onkeyup="filterDepartments()" placeholder="🔍 Search department, course, quiz, or user..."
      class="w-full p-3 rounded-lg border border-gray-300 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
  </div>

  <!-- Department Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-7xl mx-auto px-4 pb-10">
    <?php foreach ($departments as $dept): ?>
      <?php
      $deptCode = $dept['department_code'];
      $deptName = $dept['department_name'];

      $courseStmt = $cee->prepare("SELECT * FROM course_tbl WHERE cou_id = ?");
      $courseStmt->execute([$deptCode]);
      $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
      if (!$course) continue;

      $courseId = $course['cou_id'];
      $courseName = $course['cou_name'];

      $quizStmt = $cee->prepare("SELECT * FROM exam_tbl WHERE cou_id = ?");
      $quizStmt->execute([$courseId]);
      $quizzes = $quizStmt->fetchAll(PDO::FETCH_ASSOC);

      $userStmt = $cee->prepare("SELECT exmne_fullname, exmne_email FROM examinee_tbl WHERE exmne_course = ?");
      $userStmt->execute([$courseId]);
      $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="department-card bg-white rounded-xl p-6 shadow-lg hover:shadow-2xl border border-gray-200 transition duration-300 ease-in-out">
        <!-- Department Header -->
        <div class="mb-4">
          <h2 class="text-2xl font-bold text-blue-700 mb-1">🏢 <?= htmlspecialchars($deptName) ?></h2>
          <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium mb-2">Code: <?= $deptCode ?></span><br>
          <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold mt-2">📘 Course: <?= htmlspecialchars($courseName) ?></span>
        </div>

        <!-- Quizzes Section -->
        <div class="mb-4">
          <h3 class="text-lg font-semibold text-yellow-600 mb-1">📝 Quizzes (<?= count($quizzes) ?>)</h3>
          <?php if (!empty($quizzes)): ?>
            <ul class="list-disc list-inside text-sm text-gray-700 ml-3 space-y-1">
              <?php foreach ($quizzes as $quiz): ?>
                <li><span class="font-medium"><?= htmlspecialchars($quiz['ex_title']) ?></span></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-sm text-gray-500 italic">No quizzes available for this course.</p>
          <?php endif; ?>
        </div>

        <!-- Users Section -->
        <div>
          <h3 class="text-lg font-semibold text-green-600 mb-1">👥 Users (<?= count($users) ?>)</h3>
          <?php if (!empty($users)): ?>
            <ul class="text-sm text-gray-800 max-h-32 overflow-y-auto pr-2 space-y-1 border-t border-gray-200 pt-2">
              <?php foreach ($users as $user): ?>
                <li class="border-b border-dashed border-gray-300 pb-1">
                  • <span class="font-semibold"><?= htmlspecialchars($user['exmne_fullname']) ?></span>
                  <span class="text-gray-500">(<a href="mailto:<?= htmlspecialchars($user['exmne_email']) ?>" class="underline hover:text-blue-500"><?= htmlspecialchars($user['exmne_email']) ?></a>)</span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-sm text-gray-500 italic">No users enrolled.</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
