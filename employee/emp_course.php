<?php
session_start();
include_once("db/config.php");

if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$emp_name = $_SESSION['username'];
$emp_email = $_SESSION['employee_email'];

// Fetch employee's department
$stmt = $pdo->prepare("SELECT department_id FROM employees WHERE id = :employee_id");
$stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
$stmt->execute();
$department_id = $stmt->fetchColumn();

// Fetch courses assigned to the employee's department
$stmt = $pdo->prepare("
    SELECT c.id, c.course_name, c.pdf_material, c.video_links
    FROM courses c
    INNER JOIN course_departments cd ON cd.course_id = c.id
    WHERE cd.department_id = :department_id
");
$stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - My Courses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #0F172A; font-family: 'Arial', sans-serif; }
        .card { background-color: #1F2937; border-radius: 10px; padding: 20px; border: 2px solid #10B981; }
        .card h3 { color: #E2E8F0; font-size: 1.5rem; font-weight: bold; }
        .card p { color: #CBD5E1; margin: 10px 0; }
        .cta-button { background-color: #38BDF8; color: white; padding: 10px 20px; border-radius: 30px; }
        .cta-button:hover { background-color: #0284C7; }
        .video { margin-top: 10px; }
        .header-nav { background-color: #111827; }
        .header-nav a { color: white; text-transform: uppercase; font-weight: bold; }
        .content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>

<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">
  

    <!-- Course List -->
    <div class="max-w-6xl mx-auto mt-10">
        <h2 class="text-white text-2xl font-bold mb-6">My Assigned Courses</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>

                        <!-- PDF Material -->
                        <p>
                            <strong>PDF:</strong>
                            <?php if (!empty($course['pdf_material'])): ?>
                                <a href="../admin/public/uploads/course_materials/<?php echo htmlspecialchars($course['pdf_material']); ?>"
                                   target="_blank" class="cta-button inline-block mt-2">Download</a>
                            <?php else: ?>
                                <span class="text-red-400">No PDF Available</span>
                            <?php endif; ?>
                        </p>

                        <!-- Video Section -->
                        <div class="video mt-4">
                            <strong class="text-white">Videos:</strong>
                            <div class="mt-2 space-y-2">
                           <?php
$videos = json_decode($course['video_links'], true);
if (!empty($videos)) {
    foreach ($videos as $videoLink) {
        // Extract YouTube video ID
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoLink, $matches);
        $youtubeID = $matches[1] ?? null;

        if ($youtubeID) {
            echo '<div class="aspect-w-16 aspect-h-9">
                    <iframe class="w-full h-64 rounded-lg"
                        src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeID) . '"
                        title="YouTube video"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                  </div>';
        } else {
            echo '<p class="text-red-400">Invalid YouTube link: ' . htmlspecialchars($videoLink) . '</p>';
        }
    }
} else {
    echo '<p class="text-gray-400">No videos uploaded.</p>';
}
?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-white">No courses assigned to your department yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
