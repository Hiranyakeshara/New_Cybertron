<?php
session_start();
include_once("db/config.php"); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

// Fetch employees under the logged-in owner
$query = "SELECT e.id, e.employee_name, e.email, 
                 es.total_marks_participated, es.total_marks_scored 
          FROM employees e 
          LEFT JOIN employee_score es ON e.id = es.emp_id 
          WHERE e.owner_id = :owner_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":owner_id", $owner_id, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$employee_data = [];
foreach ($employees as $row) {
    $performance_marks = ($row['total_marks_participated'] > 0) ? 
                         ($row['total_marks_scored'] / $row['total_marks_participated']) * 100 : 0;
    
    $employee_data[] = [
        'name' => $row['employee_name'],
        'email' => $row['email'],
        'performance_marks' => round($performance_marks, 2)
    ];
}

// Process search query if present
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$filtered_employees = array_filter($employee_data, function ($employee) use ($search_query) {
    return empty($search_query) || stripos($employee['name'], $search_query) !== false;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Employee Performance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #1F2937; font-family: 'Arial', sans-serif; display: flex; height: 100vh; }
        .header-nav { background-color: #111827; margin-bottom: 20px; }
        .sidebar { background-color: #2D3748; width: 250px; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px; }
        .sidebar a { color: #fff; display: block; padding: 15px; text-transform: uppercase; font-weight: bold; text-decoration: none; }
        .sidebar a:hover { background-color: #10B981; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }
        .card { background-color: #1F2937; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        .high-performance { background-color: #34D399; color: #065F46; }
        .average-performance { background-color: #FCD34D; color: #B45309; }
        .low-performance { background-color: #FEE2E2; color: #9B2C2C; }
    </style>
</head>
<body>

<!-- Side Bar -->
<?php include_once("./include/owner_sidebar.php"); ?>

<!-- Main Content Section -->
<div class="content">
    <header class="header-nav sticky top-0 z-10">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-white">CyberTrone</a>
            <span class="company-name">Company Name: <?php echo htmlspecialchars($owner_name); ?> </span>
        </div>
    </header>

    <!-- Employee Search Section -->
    <div class="mb-6">
        <form method="GET" action="" class="flex justify-between items-center">
            <input type="text" name="search" class="px-4 py-2 border rounded-md w-1/2" placeholder="Search Employee by Name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="bg-teal-500 text-white px-6 py-2 rounded-md ml-4">Search</button>
        </form>
    </div>

    <!-- Employee Performance Rankings -->
    <div class="mt-6">
        <div class="mb-6">
            <h2 class="text-green-500 text-2xl font-bold mb-4">Higher Rank (Above 70%)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($filtered_employees as $employee) {
                    if ($employee['performance_marks'] > 70) { ?>
                        <div class="card high-performance">
                            <h3><?php echo htmlspecialchars($employee['name']); ?></h3>
                            <p><strong>Performance Marks:</strong> <?php echo htmlspecialchars($employee['performance_marks']); ?>%</p>
                        </div>
                <?php }} ?>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-yellow-500 text-2xl font-bold mb-4">Average Rank (50% - 70%)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($filtered_employees as $employee) {
                    if ($employee['performance_marks'] >= 50 && $employee['performance_marks'] <= 70) { ?>
                        <div class="card average-performance">
                            <h3><?php echo htmlspecialchars($employee['name']); ?></h3>
                            <p><strong>Performance Marks:</strong> <?php echo htmlspecialchars($employee['performance_marks']); ?>%</p>
                        </div>
                <?php }} ?>
            </div>
        </div>

        <div>
            <h2 class="text-red-500 text-2xl font-bold mb-4">Lower Rank (Below 50%)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($filtered_employees as $employee) {
                    if ($employee['performance_marks'] < 50) { ?>
                        <div class="card low-performance">
                            <h3><?php echo htmlspecialchars($employee['name']); ?></h3>
                            <p><strong>Performance Marks:</strong> <?php echo htmlspecialchars($employee['performance_marks']); ?>%</p>
                        </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
