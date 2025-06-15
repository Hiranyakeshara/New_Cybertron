<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

// Session info
$emp_name  = $_SESSION['username'];
$emp_email = $_SESSION['employee_email'];

// Fetch cybersecurity news using Newsdata.io API
$newsData = [];
$newsApiKey = 'pub_455615c3c213429ca9b287c0f7d0a3b3';
$newsUrl = "https://newsdata.io/api/1/news?apikey=$newsApiKey&q=cybersecurity&language=en&category=technology";

if ($res = @file_get_contents($newsUrl)) {
    $parsed = json_decode($res, true);
    if (isset($parsed['results'])) {
        $newsData = array_slice($parsed['results'], 0, 5);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CyberTrone - News Feed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

  <?php include "./include/employee_sidebar.php"; ?>

  <div class="content p-6 space-y-10">
    <header class="text-center py-4">
      <h1 class="text-4xl font-bold">Welcome, <?= htmlspecialchars($emp_name) ?></h1>
      <p class="text-gray-400"><?= htmlspecialchars($emp_email) ?></p>
    </header>

    <!-- Today's Cybersecurity News -->
    <section class="bg-gray-800 p-6 rounded-lg shadow">
      <h2 class="text-2xl font-semibold mb-4">🔐 Latest Cybersecurity News</h2>
      <?php if (empty($newsData)): ?>
        <p class="text-gray-300">Unable to fetch news currently.</p>
      <?php else: ?>
        <ul class="space-y-4">
          <?php foreach ($newsData as $article): ?>
            <li class="border-b border-gray-700 pb-4">
              <a href="<?= htmlspecialchars($article['link']) ?>" target="_blank" class="text-blue-400 font-medium">
                <?= htmlspecialchars($article['title']) ?>
              </a>
              <p class="text-sm text-gray-400 mt-1">
                <?= isset($article['source_id']) ? htmlspecialchars($article['source_id']) : 'Unknown Source' ?>
                | <?= htmlspecialchars(date('F j, Y', strtotime($article['pubDate']))) ?>
              </p>
              <p class="text-gray-300 text-sm mt-2">
                <?= htmlspecialchars($article['description']) ?>
              </p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>

</body>
</html>
