<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cee_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the delete_id is passed via the URL
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM policies WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    // Execute and check if successful
    if ($stmt->execute()) {
        // Redirect back to the create_policy.php page after deletion
        header("Location: ../home.php?page=manage-policies");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
