<div class="app-main__outer">
    <div class="app-main__inner">

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

    // Handle form submission (Add Policy)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_policy'])) {
        $policy_name = $_POST['policy_name'];
        $description = $_POST['description'];
        $learn = $_POST['learn'];

        if (!empty($policy_name) && !empty($description) && !empty($learn)) {
            $stmt = $conn->prepare("INSERT INTO policies (policy_name, description, learn) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $policy_name, $description, $learn);
            $stmt->execute();
            $stmt->close();
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Policy</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .table-hover tbody tr:hover {
                background-color: #f1f1f1;
            }
            .table thead {
                background-color: #007bff;
                color: white;
            }
            .btn-sm {
                margin-right: 5px;
            }
            .container {
                margin-top: 30px;
            }
            .policy-header {
                background-color: #343a40;
                color: white;
                padding: 15px;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>

    <div class="container">
        <div class="policy-header">
            <h2 class="text-center">Policies Management</h2>
        </div>
        <hr>

        <!-- Display Policies -->
        <h3 class="mb-4">Policies List</h3>
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Policy Name</th>
                        <th>Policy Category</th>
                        <th>Learn</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all policies from the database
                    $result = $conn->query("SELECT * FROM policies");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['policy_name']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['learn']}</td>
                                <td>
                                    <a href='./pages/update_policy.php?id={$row['id']}' class='btn btn-warning btn-sm'>Update</a>
                                    <a href='./pages/delete_policy.php?delete_id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap and JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    </body>
    </html>

    </div>
</div>
