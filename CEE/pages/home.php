<?php
// Colors mapping for each category
$colors = [
    "Security" => "bg-danger text-white",
    "Privacy" => "bg-info text-white",
    "Compliance" => "bg-success text-white",
    "Data Management" => "bg-warning text-dark",
    "Access Control" => "bg-primary text-white"
];
?>

<div class="app-main__outer">
    <div id="refreshData">
        <div class="col-md-12">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <br>
                    <div class="page-title-heading">
                        <h4>Learn the Policies in Our System</h4>
                    </div>
                </div>
            </div>

            <?php
            // Fetch policies from the database grouped by description
            $query = "SELECT * FROM policies ORDER BY description";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $policies_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $policies = [
                "Security" => [],
                "Privacy" => [],
                "Compliance" => [],
                "Data Management" => [],
                "Access Control" => []
            ];

            // Group the policies by their description
            foreach ($policies_data as $row) {
                if (array_key_exists($row['description'], $policies)) {
                    $policies[$row['description']][] = $row;
                }
            }
            ?>

            <div class="container mt-4">
                <div class="row">
                    <?php
                    // Loop through the grouped policies and display them with collapsible cards
                    foreach ($policies as $category => $policyList) {
                        if (!empty($policyList)) {
                            $collapseId = strtolower(str_replace(' ', '-', $category)); // Generate a unique ID for collapse element
                            $colorClass = $colors[$category]; // Get the color class for the category

                            echo "<div class='col-md-12'>
                                    <div class='card mb-4'>
                                        <div class='card-header d-flex justify-content-between align-items-center {$colorClass}'>
                                            <h4>{$category} Policies</h4>
                                            <button class='btn btn-link text-white' type='button' data-bs-toggle='collapse' data-bs-target='#{$collapseId}' aria-expanded='false' aria-controls='{$collapseId}'>
                                                Toggle
                                            </button>
                                        </div>
                                        <div id='{$collapseId}' class='collapse'>
                                            <div class='card-body'>
                                                <ul class='list-group'>";

                            foreach ($policyList as $policy) {
                                echo "<li class='list-group-item'>
                                        <strong>Policy Name:</strong> {$policy['policy_name']}<br>
                                        <strong>Description:</strong> {$policy['description']}<br>
                                        <strong>Learn:</strong> {$policy['learn']}<br>
                                        <strong>Date Created:</strong> {$policy['date_created']}
                                    </li>";
                            }

                            echo "              </ul>
                                                <button class='btn btn-secondary mt-3' data-bs-toggle='collapse' data-bs-target='#{$collapseId}'>
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap CSS and JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
