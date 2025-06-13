<?php

class LoginController extends Controller {

    public function __construct() {
      if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start session to store login info
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Hardcoded admin credentials
            $adminUsername = 'admin';
            $adminPassword = 'admin1234';

            // Check if username and password match
            if ($username === $adminUsername && $password === $adminPassword) {
                // Set session variables for admin
                $_SESSION['role'] = 'admin';
                $_SESSION['username'] = $username;

                // Redirect to admin dashboard
                header("Location: /New_Cybertron/admin/public/admin/dashboard");
                exit;
            } else {
                $error = "Invalid admin credentials.";
            }
        }

        // Show the login page
        $this->view('admin/login', ['error' => $error ?? null]);
    }

    public function logout() {
        session_start();  // Start the session to ensure we can access the session data
        session_unset();  // Unset all session variables
        session_destroy();  // Destroy the session
        header("Location: /cyber-training-platform/public/admin/login");  // Redirect to login page after logout
        exit;
    }
}
