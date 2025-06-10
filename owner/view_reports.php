<?php
session_start();
include 'db/config.php';

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

// Fetch employee data from the database using PDO
$query = "
    SELECT 
        e.id, 
        e.employee_name, 
        e.email, 
        s.total_marks_participated, 
        s.total_marks_scored, 
        s.current_rank 
    FROM employees e
    JOIN employee_score s ON e.id = s.emp_id
    WHERE e.owner_id = :owner_id
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Reports - CyberTrone</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function searchEmployees() {
            let input = document.getElementById("search").value.toLowerCase();
            let cards = document.getElementsByClassName("employee-card");
            
            for (let i = 0; i < cards.length; i++) {
                let name = cards[i].getElementsByClassName("card-header")[0].innerText.toLowerCase();
                if (name.includes(input)) {
                    cards[i].style.display = "block";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-white">

<?php include_once("./include/owner_sidebar.php"); ?>

<div class="content p-6">
    <header class="sticky top-0 z-10 bg-gray-800 p-4">
        <div class="flex justify-between items-center">
            <a href="#" class="text-3xl font-bold">CyberTrone</a>
            <span class="text-gray-300">Company Name: <?php echo htmlspecialchars($owner_name); ?></span>
        </div>
    </header>

    <div class="mb-6">
        <input type="text" id="search" onkeyup="searchEmployees()" placeholder="Search employees..." 
               class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-blue-500">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($employees as $employee): ?>
        <div class="employee-card bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="card-header text-xl font-bold mb-2"> <?php echo htmlspecialchars($employee['employee_name']); ?> </h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
            <p><strong>Total Marks Scored:</strong> <?php echo htmlspecialchars($employee['total_marks_scored']); ?></p>
            <p><strong>Total Marks Participated:</strong> <?php echo htmlspecialchars($employee['total_marks_participated']); ?></p>
            <p><strong>Current Rank:</strong> <?php echo number_format($employee['current_rank'], 2); ?>%</p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
