<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

include 'db/config.php'; // Include database connection

// Fetch employees
try {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE owner_id = ?");
    $stmt->execute([$owner_id]);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
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

        .form-section, .employees-section {
            background-color: #1F2937;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-section h2, .employees-section h2 {
            color: #fff;
            margin-bottom: 10px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #A0AEC0;
            background-color: #2D3748;
            color: white;
        }

        .btn {
            background-color: #10B981;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #047857;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #A0AEC0;
            color: white;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
            font-size: 12px;
        }

        .edit-btn {
            background-color: #4A90E2;
            color: white;
        }

        .delete-btn {
            background-color: #E53E3E;
            color: white;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #2D3748;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            color: white;
        }
        .modal .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
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

    <div class="grid grid-cols-2 gap-6">
        <!-- Employee Form -->
        <div class="form-section">
            <h2>Add Employee</h2>
            <form action="add_employee.php" method="POST">
                <input type="hidden" name="owner_id" value="<?php echo $owner_id; ?>">
                <input type="text" name="name" class="input-field" placeholder="Employee Name" required>
                <input type="email" name="email" class="input-field" placeholder="Employee Email" required>
                <input type="text" name="username" class="input-field" placeholder="Username" required>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
                <button type="submit" class="btn">Add Employee</button>
            </form>
        </div>

        <!-- Employees List -->
        <div class="employees-section">
            <h2>Employees</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['employee_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['username']); ?></td>
                            <td>
                                <!-- Edit Button to Open Modal -->
                                <button class="edit-btn text-white" onclick="openEditModal(<?php echo $employee['id']; ?>, '<?php echo $employee['employee_name']; ?>', '<?php echo $employee['email']; ?>', '<?php echo $employee['username']; ?>')">Edit</button>
                                
                                <!-- Delete Button -->
                                <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Edit Employee</h2>
        <form id="editForm" action="update_employee.php" method="POST">
            <input type="hidden" name="employee_id" id="employee_id">
            <input type="text" name="employee_name" id="employee_name" class="input-field" placeholder="Employee Name" required>
            <input type="email" name="email" id="email" class="input-field" placeholder="Employee Email" required>
            <input type="text" name="username" id="username" class="input-field" placeholder="Username" required>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>

<script>
    // Open Edit Modal and Populate with Employee Data
    function openEditModal(id, name, email, username) {
        document.getElementById('employee_id').value = id;
        document.getElementById('employee_name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('username').value = username;
        document.getElementById('editModal').style.display = "flex";
    }

    // Close Modal
    function closeModal() {
        document.getElementById('editModal').style.display = "none";
    }
</script>

</body>
</html>
