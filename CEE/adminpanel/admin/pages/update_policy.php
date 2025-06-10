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

// Get policy details for update
$id = $_GET['id'];
$policy = null;

if (!empty($id)) {
    $stmt = $conn->prepare("SELECT * FROM policies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $policy = $result->fetch_assoc();
    $stmt->close();
}

// Handle Update Policy
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_policy'])) {
    $policy_id = $_POST['policy_id'];
    $policy_name = $_POST['policy_name'];
    $description = $_POST['description'];
    $learn = $_POST['learn'];

    if (!empty($policy_id) && !empty($policy_name) && !empty($description) && !empty($learn)) {
        $stmt = $conn->prepare("UPDATE policies SET policy_name = ?, description = ?, learn = ? WHERE id = ?");
        $stmt->bind_param("sssi", $policy_name, $description, $learn, $policy_id);
        $stmt->execute();
        $stmt->close();
        // Redirect back to the main page after updating
        header("Location: ../home.php?page=manage-policies");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Policy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Update Policy</h2>

    <?php if ($policy): ?>
        <form action="update_policy.php?id=<?php echo $policy['id']; ?>" method="POST">
            <input type="hidden" name="policy_id" value="<?php echo $policy['id']; ?>">

            <div class="form-group">
                <label for="policy_name">Policy Name</label>
                <input type="text" class="form-control" name="policy_name" value="<?php echo $policy['policy_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Policy Category</label>
                <textarea class="form-control" name="description" rows="4" required><?php echo $policy['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="learn">Learn About</label>
                <input type="text" class="form-control" name="learn" value="<?php echo $policy['learn']; ?>" required>
            </div>

            <button type="submit" name="update_policy" class="btn btn-primary">Update Policy</button>
            <a href="../home.php?page=manage-policies" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <p>Policy not found.</p>
    <?php endif; ?>
</div>

</body>
</html>
