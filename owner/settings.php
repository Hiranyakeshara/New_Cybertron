<?php
session_start();
include 'db/config.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

// Fetch company details from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM company_owners WHERE id = ?");
    $stmt->execute([$owner_id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle industry update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_industry'])) {
    $new_industry = trim($_POST['industry']);

    try {
        $updateStmt = $pdo->prepare("UPDATE company_owners SET industry = ? WHERE id = ?");
        $updateStmt->execute([$new_industry, $owner_id]);

        $_SESSION['success'] = "Industry updated successfully!";
        header("Location: owner_dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating industry: " . $e->getMessage();
    }
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
            padding: 20px;
            color: white;
            text-align: center;
        }
        .card {
            background-color: #2D3748;
            border-radius: 10px;
            padding: 20px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
        .input-field {
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #10B981;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #047857;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include_once("./include/owner_sidebar.php"); ?>

<!-- Main Content -->
<div class="content">
     <!-- Header Section -->
     <header class="header-nav sticky top-0 z-10">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-white">CyberTrone</a>
            <span class="company-name">Company Name: <?php echo htmlspecialchars($owner_name); ?> </span>
        </div>
    </header>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-500 text-white p-3 mb-4 rounded"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-500 text-white p-3 mb-4 rounded"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Company Details -->
    <div class="card">
        <h2 class="text-2xl font-bold mb-4">Company Details</h2>
        <p><strong>Company Name:</strong> <?php echo htmlspecialchars($company['company_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($company['email']); ?></p>
        <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($company['reg_number']); ?></p>
        <p><strong>Industry:</strong> <?php echo htmlspecialchars($company['industry'] ?? 'Not Set'); ?></p>
    </div>

   

  
</div>

</body>
</html>
