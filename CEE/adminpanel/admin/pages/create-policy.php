<?php
// Database connection credentials
$servername = "localhost";  // Update with your MySQL server hostname
$username = "root";  // Update with your MySQL username
$password = "";  // Update with your MySQL password
$dbname = "cee_db";  // Update with your database name

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $policy_name = $_POST['policy_name'];
    $description = $_POST['description'];
    $learn = $_POST['learn'];

    // Basic form validation (optional)
    if (empty($policy_name) || empty($description) || empty($learn)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        // Prepare and bind the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO policies (policy_name, description, learn) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $policy_name, $description, $learn);

        // Execute the SQL statement and check if the data was inserted successfully
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>New policy added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>



<div class="app-main__outer">
    <div class="app-main__inner">
         
        
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">Policy Adding Form</div>
                <div class="card-body">
                    <!-- Policy Form -->
                    <form  method="POST" action="">
                        <div class="form-group">
                            <label for="policy_name">Policy Name</label>
                            <input type="text" class="form-control" id="policy_name" name="policy_name" placeholder="Enter policy name" required>
                        </div>

                        <div class="form-group">
    <label for="policy_category">Policy Category</label>
    <select class="form-control" id="description" name="description" required>
        <option value="" disabled selected>Select a policy category</option>
        <option value="Security">Security</option>
        <option value="Privacy">Privacy</option>
        <option value="Compliance">Compliance</option>
        <option value="Data Management">Data Management</option>
        <option value="Access Control">Access Control</option>
    </select>
</div>


                        <div class="form-group">
                            <label for="learn">Learn About</label>
                            <input type="text" class="form-control" id="learn" name="learn" placeholder="Enter the Policy Details" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Policy</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

