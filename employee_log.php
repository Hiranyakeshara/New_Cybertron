<?php
session_start();
require_once "db/config.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emp_log"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {
        try {
            // Fetch all fields including hashed password and email
            $stmt = $pdo->prepare("SELECT id, department_id, name, username, password, nic, contact_number, email FROM employees WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Password verification using hashed password from DB
                if (password_verify($password, $user["password"])) {
                    $_SESSION["employee_id"] = $user["id"];
                    $_SESSION["employee_name"] = $user["name"]; // Fixed: use "name" instead of "employee_name"
                    $_SESSION["employee_email"] = $user["email"];
                    $_SESSION["username"] = $user["username"];

                    header("Location: employee/employee_dashboard.php"); // Redirect after login
                    exit();
                } else {
                    echo "<script>alert('Invalid email or password!'); window.location='employee_login.php';</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password!'); window.location='employee_login.php';</script>";
            }
        } catch (PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Please fill in all fields!'); window.location='employee_login.php';</script>";
    }
}
?>
