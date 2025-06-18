<?php
class LogoutController extends Controller {
    public function index() {
        session_start(); // Ensure session is active
        session_unset(); // Clear session variables
        session_destroy(); // Destroy session
        header("Location: /New_Cybertron/admin/public/login"); // Redirect to admin login
        exit();
    }
}
