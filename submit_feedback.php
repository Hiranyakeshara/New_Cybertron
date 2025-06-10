<?php
// Include database configuration
require_once 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required!";
        exit;
    }

    try {
        // Prepare SQL query to insert feedback
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (:name, :email, :message)");
        
        // Execute query with user input
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);

        // Redirect to index.php after successful submission
        header("Location: index.php?success=true");
        exit;
    } catch (PDOException $e) {
        echo "Error submitting feedback: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
