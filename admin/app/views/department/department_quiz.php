<?php
// Connect to both databases
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
  <title>Department Quizzes</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script>
    // Live filter function
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
<body class="bg-gray-900 text-white p-8 min-h-screen">

  <h1 class="text-3xl font-bold mb-6">📚 Department Quizzes and User Participation</h1>

  <!-- Search Bar -->
  <input type="text" id="searchInput" onkeyup="filterDepartments()" placeholder="Search department, course, or quiz..." class="mb-8 w-full p-3 rounded-lg border border-gray-600 bg-gray-800 text-white shadow">

  <?php foreach ($departments as $dept): ?>
    <?php
    $deptCode = $dept['department_code'];
    $deptName = $dept['department_name'];

    // Match department_code with course_tbl.cou_id
    $courseStmt = $cee->prepare("SELECT * FROM course_tbl WHERE cou_id = ?");
    $courseStmt->execute([$deptCode]);
    $course = $courseStmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) continue;

    $courseId = $course['cou_id'];
    $courseName = $course['cou_name'];

    // Quizzes for this course
    $quizStmt = $cee->prepare("SELECT * FROM exam_tbl WHERE cou_id = ?");
    $quizStmt->execute([$courseId]);
    $quizzes = $quizStmt->fetchAll(PDO::FETCH_ASSOC);

    // Users in this course
    $userStmt = $cee->prepare("SELECT exmne_fullname, exmne_email FROM examinee_tbl WHERE exmne_course = ?");
    $userStmt->execute([$courseId]);
    $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Department Card -->
    <div class="department-card bg-gray-800 rounded-lg p-6 mb-10 shadow">
      <h2 class="text-2xl font-semibold mb-1"><?= htmlspecialchars($deptName) ?> (Code: <?= $deptCode ?>)</h2>
      <p class="text-gray-400 mb-4">Mapped Course: <span class="font-medium"><?= htmlspecialchars($courseName) ?></span></p>

      <!-- Quizzes -->
      <div class="mb-4">
        <h3 class="text-lg font-semibold text-yellow-300">📝 Quizzes Allocated</h3>
        <?php if (empty($quizzes)): ?>
          <p class="text-gray-400">No quizzes found.</p>
        <?php else: ?>
          <ul class="list-disc ml-6 text-gray-300">
            <?php foreach ($quizzes as $quiz): ?>
              <li><?= htmlspecialchars($quiz['ex_title']) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Users -->
      <div>
        <h3 class="text-lg font-semibold text-green-300">👥 Users in This Department: <?= count($users) ?></h3>
        <?php if (empty($users)): ?>
          <p class="text-gray-400">No users found.</p>
        <?php else: ?>
          <ul class="mt-2 space-y-1">
            <?php foreach ($users as $user): ?>
              <li class="text-sm text-gray-200">• <?= htmlspecialchars($user['exmne_fullname']) ?> (<?= htmlspecialchars($user['exmne_email']) ?>)</li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

  <?php endforeach; ?>
</body>
</html>
