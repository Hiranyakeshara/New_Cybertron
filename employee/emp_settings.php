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

try {
    $query = "
        SELECT e.id, e.name AS employee_name, e.username, e.nic, e.contact_number, e.email,
               d.department_name, d.department_code
        FROM employees e
        JOIN departments d ON e.department_id = d.id
        WHERE e.id = :emp_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":emp_id", $emp_id, PDO::PARAM_INT);
    $stmt->execute();
    $employee_details = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Query Failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #1F2937; font-family: 'Arial', sans-serif; display: flex; min-height: 100vh; }
        .header-nav { background-color: #111827; }
        .header-nav a { color: white; font-weight: bold; text-transform: uppercase; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }
        .card {
            background-color: #2D3748;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.3);
        }
        .card h2 { color: #10B981; font-size: 22px; margin-bottom: 15px; }
        .card p { color: #CBD5E1; margin-bottom: 10px; }
        .card span { color: #F9FAFB; font-weight: bold; }
    </style>
</head>

<body>

<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">



    <!-- Card Section -->
    <div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Employee Info -->
        <div class="card">
            <h2>Employee Information</h2>
            <p><span>ID:</span> <?php echo htmlspecialchars($employee_details["id"]); ?></p>
            <p><span>Name:</span> <?php echo htmlspecialchars($employee_details["employee_name"]); ?></p>
            <p><span>Username:</span> <?php echo htmlspecialchars($employee_details["username"]); ?></p>
            <p><span>NIC:</span> <?php echo htmlspecialchars($employee_details["nic"]); ?></p>
            <p><span>Contact:</span> <?php echo htmlspecialchars($employee_details["contact_number"]); ?></p>
            <p><span>Email:</span> <?php echo htmlspecialchars($employee_details["email"]); ?></p>
        </div>

        <!-- Department Info -->
        <div class="card">
            <h2>Department Information</h2>
            <p><span>Department:</span> <?php echo htmlspecialchars($employee_details["department_name"]); ?></p>
            <p><span>Department Code:</span> <?php echo htmlspecialchars($employee_details["department_code"]); ?></p>
        </div>

    </div>

</div>
</body>
</html>
