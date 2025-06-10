<?php
session_start();
require_once("db/config.php");

if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$emp_id = $_SESSION["employee_id"];
$emp_name = $_SESSION["username"];
$emp_email = $_SESSION["employee_email"];

// Dummy phishing campaign data
$campaignStats = [
    "Total Emails Sent" => 25,
    "Emails Opened" => 19,
    "Links Clicked" => 11,
    "Credentials Submitted" => 3,
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - Campaign Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #1F2937; font-family: 'Arial', sans-serif; display: flex; height: 100vh; }
        .header-nav { background-color: #111827; }
        .header-nav a { text-transform: uppercase; color: #fff; padding: 10px 20px; transition: color 0.3s ease; }
        .header-nav a:hover { color: #10B981; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }

        .card {
            background-color: #2D3748;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .card h3 {
            font-size: 1.5rem;
            color: #10B981;
            margin-bottom: 10px;
        }

        .card p {
            color: #CBD5E1;
            font-size: 1.2rem;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }
    </style>
</head>
<body>

<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">


    <!-- Campaign Statistics -->
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-6">Phishing Campaign Performance</h2>

        <div class="grid-container">
            <?php foreach ($campaignStats as $label => $value): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($label); ?></h3>
                    <p><?php echo htmlspecialchars($value); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>
