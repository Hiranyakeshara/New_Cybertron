<?php
/* ──────────────────────────────────────────────────────────────
 *  Cyber-Training Project Dashboard     (dashboard.php)
 *  ----------------------------------------------------------------
 *  Requires: PHP ≥ 7.4, PDO, Tailwind CDN, Chart.js CDN
 *  DB #1  : cybertraining   – employees, departments, courses …
 *  DB #2  : cee_db          – quizzes / exam tables …
 *  ----------------------------------------------------------------
 *  ⚠️  Replace the connection credentials ($dbUser/$dbPass) with
 *  ⚠️  those on your server OR pull them from ENV variables.
 * ────────────────────────────────────────────────────────────── */

$dbHost   = 'localhost';
$dbUser   = 'root';
$dbPass   = '';
$ceeDB    = new PDO("mysql:host=$dbHost;dbname=cee_db;charset=utf8mb4",$dbUser,$dbPass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$trainDB  = new PDO("mysql:host=$dbHost;dbname=cybertraining;charset=utf8mb4",$dbUser,$dbPass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

/* ------------------------------------------------------------
 *  1.  Departments & Employees
 * ---------------------------------------------------------- */
$deptRows = $trainDB->query("
    SELECT d.id,
           d.department_name,
           COUNT(e.id)                    AS employee_cnt
    FROM   departments d
    LEFT JOIN employees e ON e.department_id = d.id
    GROUP BY d.id
")->fetchAll(PDO::FETCH_ASSOC);

$totalDepts     = count($deptRows);
$totalEmployees = array_sum(array_column($deptRows,'employee_cnt'));

/* ------------------------------------------------------------
 *  2.  Courses & Quizzes (mapped by department)
 * ---------------------------------------------------------- */
$courseRows = $trainDB->query("
    SELECT cd.department_id,
           COUNT(cd.course_id)            AS course_cnt
    FROM   course_departments cd
    GROUP BY cd.department_id
")->fetchAll(PDO::FETCH_KEY_PAIR);   // [dept_id => course_cnt]

$quizRows = $ceeDB->query("
    SELECT cou_id, COUNT(ex_id) AS quiz_cnt
    FROM   exam_tbl
    GROUP BY cou_id
")->fetchAll(PDO::FETCH_KEY_PAIR);   // [cou_id => quiz_cnt]

$totalQuizzes = array_sum($quizRows);

/* ------------------------------------------------------------
 *  3.  Completion – who tried at least one quiz? 
 * ---------------------------------------------------------- */
$attempt = $ceeDB->query("
    SELECT DISTINCT et.exmne_email
    FROM   exam_attempt ea
    JOIN   examinee_tbl et ON et.exmne_id = ea.exmne_id
")->fetchAll(PDO::FETCH_COLUMN);             // unique emails

$attemptEmailSet = array_flip($attempt);     // quick look-ups by email

/* ------------------------------------------------------------
 *  4.  Department-level derived metrics & chart data
 * ---------------------------------------------------------- */
$chartLabels   = [];
$chartQuizzes  = [];
$chartComplete = [];

foreach ($deptRows as &$d) {
    $deptId   = $d['id'];
    $deptName = $d['department_name'];

    // course & quiz counts
    $d['course_cnt'] = $courseRows[$deptId] ?? 0;

    // Map department -> course name (same string in cee_db.course_tbl)
    $quizCnt = 0;
    foreach ($quizRows as $couId => $qCnt) {   // naive map by name
        $couName = $ceeDB->prepare("SELECT cou_name FROM course_tbl WHERE cou_id=?");
        $couName->execute([$couId]);
        if (strtoupper($couName->fetchColumn()) === strtoupper($deptName)) {
            $quizCnt += $qCnt;
        }
    }
    $d['quiz_cnt'] = $quizCnt;

    // completion ratio
    $empEmails = $trainDB->prepare("SELECT email FROM employees WHERE department_id=?");
    $empEmails->execute([$deptId]);
    $empEmails = $empEmails->fetchAll(PDO::FETCH_COLUMN);

    $done = 0;
    foreach ($empEmails as $em) if (isset($attemptEmailSet[$em])) $done++;
    $d['completed_pct'] = $d['employee_cnt'] ? round($done*100/$d['employee_cnt'],1) : 0;

    /* push to chart arrays */
    $chartLabels[]   = $deptName;
    $chartQuizzes[]  = $d['quiz_cnt'];
    $chartComplete[] = $d['completed_pct'];
}
unset($d);

/* ------------------------------------------------------------
 *  5.  Latest quiz completions (10)
 * ---------------------------------------------------------- */
$latest = $ceeDB->query("
    SELECT ea.examat_id, ex.ex_title,
           et.exmne_email, et.exmne_fullname,
           ea.examat_id, ex.ex_created
    FROM   exam_attempt ea
    JOIN   exam_tbl  ex ON ex.ex_id  = ea.exam_id
    JOIN   examinee_tbl et ON et.exmne_id = ea.exmne_id
    ORDER  BY ea.examat_id DESC
    LIMIT  10
")->fetchAll(PDO::FETCH_ASSOC);

/* helper to fetch dept name by email */
$getDept = $trainDB->prepare("
    SELECT d.department_name
    FROM   employees e
    JOIN   departments d ON d.id = e.department_id
    WHERE  e.email = ?
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cyber-Training Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 p-6">


<!-- KPI CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
  <div class="p-4 bg-white rounded shadow">
    <p class="text-sm text-gray-500">Departments</p>
    <p class="text-3xl font-semibold"><?= $totalDepts ?></p>
  </div>
  <div class="p-4 bg-white rounded shadow">
    <p class="text-sm text-gray-500">Employees</p>
    <p class="text-3xl font-semibold"><?= $totalEmployees ?></p>
  </div>
  <div class="p-4 bg-white rounded shadow">
    <p class="text-sm text-gray-500">Courses</p>
    <p class="text-3xl font-semibold"><?= array_sum($courseRows) ?></p>
  </div>
  <div class="p-4 bg-white rounded shadow">
    <p class="text-sm text-gray-500">Quizzes</p>
    <p class="text-3xl font-semibold"><?= $totalQuizzes ?></p>
  </div>
  <div class="p-4 bg-white rounded shadow">
    <p class="text-sm text-gray-500">Overall Completion</p>
    <p class="text-3xl font-semibold">
        <?= $totalEmployees ? round(count($attempt)*100/$totalEmployees,1) : 0 ?>%
    </p>
  </div>
</div>

<!-- RECENT COMPLETIONS -->
<h2 class="mt-10 text-2xl font-bold">Latest Quiz Completions</h2>
<div class="overflow-x-auto mt-4">
<table class="min-w-full bg-white rounded shadow">
  <thead class="bg-gray-50 text-left text-sm font-semibold">
    <tr>
      <th class="px-4 py-2">Employee</th>
      <th class="px-4 py-2">Department</th>
      <th class="px-4 py-2">Quiz Title</th>
      <th class="px-4 py-2">Date</th>
    </tr>
  </thead>
  <tbody class="divide-y">
    <?php foreach ($latest as $row):
        $getDept->execute([$row['exmne_email']]);
        $dept = $getDept->fetchColumn() ?: '—';
    ?>
    <tr class="hover:bg-gray-50">
      <td class="px-4 py-2"><?= htmlspecialchars($row['exmne_fullname']) ?></td>
      <td class="px-4 py-2"><?= htmlspecialchars($dept) ?></td>
      <td class="px-4 py-2"><?= htmlspecialchars($row['ex_title']) ?></td>
      <td class="px-4 py-2"><?= date('d M Y',strtotime($row['ex_created'])) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

<!-- CHARTS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-14">
  <div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Quizzes per Department</h3>
    <canvas id="quizChart"></canvas>
  </div>
  <div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Department Completion Rate</h3>
    <canvas id="completionChart"></canvas>
  </div>
</div>

<script>
const labels      = <?= json_encode($chartLabels) ?>;
const quizCounts  = <?= json_encode($chartQuizzes) ?>;
const compRates   = <?= json_encode($chartComplete) ?>;

/* -- Bar: quizzes per department -- */
new Chart(document.getElementById('quizChart'), {
  type: 'bar',
  data: { labels,
          datasets:[{ label:'Quizzes', data:quizCounts }]},
  options: { responsive:true, plugins:{legend:{display:false}} }
});

/* -- Doughnut: % completion -- */
new Chart(document.getElementById('completionChart'), {
  type: 'doughnut',
  data: { labels,
          datasets:[{ data:compRates }]},
  options: {
     responsive:true,
     plugins:{legend:{position:'bottom'}},
     cutout:'50%'
  }
});
</script>
</body>
</html>
