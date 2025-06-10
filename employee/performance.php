<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

include_once("db/config.php");

// Fetch session data
$emp_id = $_SESSION["employee_id"];
$emp_name = $_SESSION["username"];
$emp_email = $_SESSION["employee_email"];



// Generate dummy phishing results
$phishing_results = [
    'Total Emails Sent' => 30,
    'Link Clicked' => 18,
    'Credentials Submitted' => 4,
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Performance - CyberTrone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-900 text-white">

<!-- Sidebar -->
<?php include_once("./include/employee_sidebar.php"); ?>

<!-- Content -->
<div class="content">

    <!-- Header -->


    <div class="max-w-6xl mx-auto mt-10 space-y-10">

        <!-- 📊 Case Study Performance Chart -->
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold mb-4">My Quizzes Results </h3>
            <canvas id="scoreChart"></canvas>
        </div>

   

        <!-- 🛡️ Phishing Campaign Results -->
        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold mb-4">Phishing Campaign Results</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-200">
                <?php foreach ($phishing_results as $key => $value): ?>
                    <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>

<!-- Chart Script -->
<script>
    const ctx = document.getElementById('scoreChart').getContext('2d');
    const scoreChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($case_studies, 'title')); ?>,
            datasets: [{
                label: 'Marks (%)',
                data: <?php echo json_encode(array_map(function($row) {
                    return round(($row['case_marks'] / $row['allocated_marks']) * 100, 2);
                }, $case_studies)); ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.7)', // Tailwind's emerald-500
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: '#E5E7EB'
                    }
                },
                x: {
                    ticks: {
                        color: '#E5E7EB'
                    }
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

</body>
</html>
