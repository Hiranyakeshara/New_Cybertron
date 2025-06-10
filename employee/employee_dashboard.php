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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - Employee Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1F2937;
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
        }
        .header-nav {
            background-color: #111827;
        }
        .header-nav a {
            text-transform: uppercase;
            color: #fff;
            padding: 10px 20px;
            transition: color 0.3s ease;
        }
        .header-nav a:hover {
            color: #10B981;
        }
        .cta-button {
            background-color: #10B981;
            color: white;
            padding: 12px 24px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 30px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #047857;
        }
        .card h3 { color: #fff; }
        .card p { color: #A0AEC0; }
        .footer {
            background-color: #111827;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }
        .footer a {
            color: #10B981;
            text-decoration: none;
        }
        .sidebar {
            background-color: #2D3748;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 15px;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #10B981;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">
    <header class="header-nav sticky top-0 z-10">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-white">CyberTrone</a>
            <span class="company-name text-white">User: <?php echo htmlspecialchars($emp_name); ?> | Email: <?php echo htmlspecialchars($emp_email); ?></span>
        </div>
    </header>

    <div class="container mx-auto mt-10">
        <!-- Total Participate Score -->
        <div class="card bg-gray-800 text-white p-6 mb-6 rounded-lg">
            <h3 class="text-xl font-semibold">Sample Data</h3>

        </div>

        <!-- My Score -->
        <div class="card bg-gray-800 text-white p-6 rounded-lg">
            <h3 class="text-xl font-semibold">Sample Data</h3>
       
        </div>

        <!-- Rank -->
        <div class="card bg-gray-800 text-white p-6 rounded-lg mt-6">
            <h3 class="text-xl font-semibold">Sample Data</h3>
         
        </div>
    </div>
