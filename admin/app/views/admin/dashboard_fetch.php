<?php
// dashboard_fetch.php - Glassmorphism-Styled Dashboard with Modern Look & Extra Charts

$dbHost   = 'localhost';
$dbUser   = 'root';
$dbPass   = '';
$ceeDB    = new PDO("mysql:host=$dbHost;dbname=cee_db;charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$trainDB  = new PDO("mysql:host=$dbHost;dbname=cybertraining;charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$deptRows = $trainDB->query("SELECT d.id, d.department_name, COUNT(e.id) AS employee_cnt FROM departments d LEFT JOIN employees e ON e.department_id = d.id GROUP BY d.id")->fetchAll(PDO::FETCH_ASSOC);
$totalDepts = count($deptRows);
$totalEmployees = array_sum(array_column($deptRows, 'employee_cnt'));

$courseRows = $trainDB->query("SELECT cd.department_id, COUNT(cd.course_id) AS course_cnt FROM course_departments cd GROUP BY cd.department_id")->fetchAll(PDO::FETCH_KEY_PAIR);
$quizRows = $ceeDB->query("SELECT cou_id, COUNT(ex_id) AS quiz_cnt FROM exam_tbl GROUP BY cou_id")->fetchAll(PDO::FETCH_KEY_PAIR);
$totalQuizzes = array_sum($quizRows);

$attempt = $ceeDB->query("SELECT DISTINCT et.exmne_email FROM exam_attempt ea JOIN examinee_tbl et ON et.exmne_id = ea.exmne_id")->fetchAll(PDO::FETCH_COLUMN);
$attemptEmailSet = array_flip($attempt);



$chartLabels = [];
$chartQuizzes = [];
$chartComplete = [];
$chartCourses = [];

foreach ($deptRows as &$d) {
    $deptId = $d['id'];
    $deptName = $d['department_name'];
    $d['course_cnt'] = $courseRows[$deptId] ?? 0;

    $quizCnt = 0;
    foreach ($quizRows as $couId => $qCnt) {
        $couName = $ceeDB->prepare("SELECT cou_name FROM course_tbl WHERE cou_id=?");
        $couName->execute([$couId]);
        if (strtoupper($couName->fetchColumn()) === strtoupper($deptName)) {
            $quizCnt += $qCnt;
        }
    }
    $d['quiz_cnt'] = $quizCnt;

    $empEmails = $trainDB->prepare("SELECT email FROM employees WHERE department_id=?");
    $empEmails->execute([$deptId]);
    $empEmails = $empEmails->fetchAll(PDO::FETCH_COLUMN);

    $done = 0;
    foreach ($empEmails as $em) if (isset($attemptEmailSet[$em])) $done++;
    $d['completed_pct'] = $d['employee_cnt'] ? round($done * 100 / $d['employee_cnt'], 1) : 0;

    $chartLabels[] = $deptName;
    $chartQuizzes[] = $d['quiz_cnt'];
    $chartCourses[] = $d['course_cnt'];
    $chartComplete[] = $d['completed_pct'];
}
unset($d);

$latest = $ceeDB->query("SELECT ea.examat_id, ex.ex_title, et.exmne_email, et.exmne_fullname, ea.examat_id, ex.ex_created FROM exam_attempt ea JOIN exam_tbl ex ON ex.ex_id = ea.exam_id JOIN examinee_tbl et ON et.exmne_id = ea.exmne_id ORDER BY ea.examat_id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

$getDept = $trainDB->prepare("SELECT d.department_name FROM employees e JOIN departments d ON d.id = e.department_id WHERE e.email = ?");
?>

<style>
.glass {
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-radius: 12px;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}
</style>

<div class="bg-gradient-to-br from-blue-50 to-white text-gray-900 min-h-screen p-6">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6">
    <div class="p-4 glass">
      <p class="text-sm text-gray-700">Departments</p>
      <p class="text-3xl font-bold text-purple-700"><?= $totalDepts ?></p>
    </div>
    <div class="p-4 glass">
      <p class="text-sm text-gray-700">Employees</p>
      <p class="text-3xl font-bold text-blue-700"><?= $totalEmployees ?></p>
    </div>
    <div class="p-4 glass">
      <p class="text-sm text-gray-700">Courses</p>
      <p class="text-3xl font-bold text-green-700"><?= array_sum($courseRows) ?></p>
    </div>
    <div class="p-4 glass">
      <p class="text-sm text-gray-700">Quizzes</p>
      <p class="text-3xl font-bold text-yellow-600"><?= $totalQuizzes ?></p>
    </div>
    <div class="p-4 glass">
      <p class="text-sm text-gray-700">Completion</p>
      <p class="text-3xl font-bold text-pink-600"><?= $totalEmployees ? round(count($attempt) * 100 / $totalEmployees, 1) : 0 ?>%</p>
    </div>
  
  </div>

  <h2 class="mt-10 text-2xl font-bold">Recent Quiz Completions</h2>
  <div class="overflow-x-auto mt-4">
    <table class="min-w-full glass text-sm">
      <thead class="bg-white/80">
        <tr>
          <th class="px-4 py-2 text-left">Employee</th>
          <th class="px-4 py-2 text-left">Department</th>
          <th class="px-4 py-2 text-left">Quiz Title</th>
          <th class="px-4 py-2 text-left">Date</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-300">
        <?php foreach ($latest as $row):
            $getDept->execute([$row['exmne_email']]);
            $dept = $getDept->fetchColumn() ?: '—';
        ?>
        <tr class="hover:bg-white/60 transition">
          <td class="px-4 py-2"><?= htmlspecialchars($row['exmne_fullname']) ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($dept) ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($row['ex_title']) ?></td>
          <td class="px-4 py-2"><?= date('d M Y', strtotime($row['ex_created'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-14">
    <div class="glass p-6">
      <h3 class="text-lg font-semibold mb-4 text-purple-700">Quizzes per Department</h3>
      <canvas id="quizChart" class="p-4"></canvas>
    </div>
    <div class="glass p-6">
      <h3 class="text-lg font-semibold mb-4 text-pink-700">Completion Rate</h3>
      <canvas id="completionChart" class="p-4"></canvas>
    </div>
    <div class="glass p-6">
      <h3 class="text-lg font-semibold mb-4 text-green-700">Courses per Department</h3>
      <canvas id="courseChart" class="p-4"></canvas>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($chartLabels) ?>;
const quizCounts = <?= json_encode($chartQuizzes) ?>;
const compRates = <?= json_encode($chartComplete) ?>;
const courseCounts = <?= json_encode($chartCourses) ?>;

Chart.defaults.color = "#374151";

new Chart(document.getElementById('quizChart'), {
  type: 'bar',
  data: { labels, datasets: [{ label: 'Quizzes', data: quizCounts, backgroundColor: '#c084fc', borderColor: '#7c3aed', borderWidth: 1 }] },
  options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('completionChart'), {
  type: 'doughnut',
  data: { labels, datasets: [{ data: compRates, backgroundColor: ['#ec4899', '#8b5cf6', '#22d3ee', '#facc15', '#10b981', '#f87171', '#60a5fa', '#eab308'] }] },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '50%' }
});

new Chart(document.getElementById('courseChart'), {
  type: 'bar',
  data: { labels, datasets: [{ label: 'Courses', data: courseCounts, backgroundColor: '#6ee7b7', borderColor: '#059669', borderWidth: 1 }] },
  options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>