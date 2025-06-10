<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    // Redirect to login page if not logged in
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

// Include the database connection file
include_once("db/config.php");

try {
    // Query to get the total number of employees under this company
    $employee_count_query = "
        SELECT COUNT(*) AS total_employees
        FROM employees
        WHERE owner_id = :owner_id
    ";
    $stmt = $pdo->prepare($employee_count_query);
    $stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
    $stmt->execute();
    $employee_count_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_employees = $employee_count_row['total_employees'];

    // Query to get the total number of case studies available in the platform
    $case_study_count_query = "
        SELECT COUNT(*) AS total_case_studies
        FROM case_studies
    ";
    $stmt = $pdo->prepare($case_study_count_query);
    $stmt->execute();
    $case_study_count_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_case_studies = $case_study_count_row['total_case_studies'];

} catch (PDOException $e) {
    // Handle errors
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Company Owner Dashboard</title>
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

        .card {
            background-color: #1F2937;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h3 {
            color: #fff;
        }

        .card p {
            color: #A0AEC0;
        }

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

        /* Sidebar */
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

        /* Content */
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>

<body>

<!-- Side Bar -->
<?php
include_once("./include/owner_sidebar.php");
?>

<!-- Main Content Section -->
<div class="content">
    <!-- Header Section -->
    <header class="header-nav sticky top-0 z-10">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-white">CyberTrone</a>
            <span class="company-name">Company Name: <?php echo htmlspecialchars($owner_name); ?> </span>
        </div>
    </header>

    <!-- Dashboard Info Section -->
    <div class="container mx-auto mt-10">
        <!-- Total Employees Card -->
        <div class="card bg-gray-800 text-white p-6 mb-6 rounded-lg">
            <h3 class="text-xl font-semibold">Total Employees</h3>
            <p class="text-lg"><?php echo number_format($total_employees); ?> Employees</p>
        </div>

        <!-- Total Case Studies Card -->
        <div class="card bg-gray-800 text-white p-6 rounded-lg">
            <h3 class="text-xl font-semibold">Total Case Studies</h3>
            <p class="text-lg"><?php echo number_format($total_case_studies); ?> Case Studies</p>
        </div>
    </div>
</div>

</body>
</html>
