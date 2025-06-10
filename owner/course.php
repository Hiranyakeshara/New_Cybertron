<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    // Redirect to login page if not logged in
    header("Location: company_owner.php");
    exit();
}

// Fetch session details
$owner_id = $_SESSION['owner_id'];
$owner_name = $_SESSION['owner_name'];
$owner_email = $_SESSION['owner_email'];

// Database connection
require_once("db/config.php"); // Assuming this file contains your DB connection

// Fetch case studies and related answers for employees
$query = "
    SELECT cs.id as case_id, cs.title, cs.allocated_marks, cs.instructions, cs.source_link, cs.question, cs.answer_description, cs.correct_answer, cs.created_at, 
           e.id as employee_id, e.employee_name, e.email, csa.selected_answer, csa.correct_answer as employee_correct_answer, csa.case_marks, csa.answered_at
    FROM case_studies cs
    LEFT JOIN case_study_answers csa ON cs.id = csa.case_id
    LEFT JOIN employees e ON csa.employee_id = e.id
    WHERE e.owner_id = :owner_id ORDER BY cs.created_at DESC
";

// Prepare the query using the PDO connection
$stmt = $pdo->prepare($query);

// Bind the owner ID parameter
$stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Organize the data into case studies
$case_studies = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $case_id = $row['case_id'];
    $employee_id = $row['employee_id'];
    $employee_name = $row['employee_name'];
    $selected_answer = $row['selected_answer'];
    $correct_answer = $row['correct_answer'];
    $case_marks = $row['case_marks'];

    // If case study doesn't exist in array, initialize it
    if (!isset($case_studies[$case_id])) {
        $case_studies[$case_id] = [
            'case_id' => $case_id,
            'title' => $row['title'],
            'allocated_marks' => $row['allocated_marks'],
            'instructions' => $row['instructions'],
            'source_link' => $row['source_link'],
            'question' => $row['question'],
            'answer_description' => $row['answer_description'],
            'correct_answer' => $row['correct_answer'],
            'created_at' => $row['created_at'],
            'employees' => []
        ];
    }

    // Add the employee's answer to the case study's employee list
    $case_studies[$case_id]['employees'][] = [
        'employee_id' => $employee_id,
        'employee_name' => $employee_name,
        'email' => $row['email'],
        'selected_answer' => $selected_answer,
        'correct_answer' => $correct_answer,
        'case_marks' => $case_marks,
        'answered_at' => $row['answered_at']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Case Study Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1F2937;
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .card {
            background-color: #2D3748;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            color: #A0AEC0;
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .employee-card {
            background-color: #4A5568;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .employee-card:hover {
            background-color: #2D3748;
        }

        .employee-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #10B981;
        }

        .performance-marks {
            font-size: 1rem;
            color: #CBD5E0;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<?php include_once("./include/owner_sidebar.php"); ?>

<!-- Main Content Section -->
<div class="content">
    <!-- Header Section -->
    <header class="header-nav sticky top-0 z-10">
        <div class="max-w-full mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-3xl font-bold text-white">CyberTrone</a>
            <span class="company-name">Company Name: <?php echo htmlspecialchars($owner_name); ?> </span>
        </div>
    </header>

    <!-- Case Study Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <?php
        // Loop through the case studies and display each card with employees
        foreach ($case_studies as $study) {
            ?>
            <div class="card">
                <h2 class="card-header"><?php echo htmlspecialchars($study['title']); ?></h2>
                <p class="card-text"><strong>Instructions:</strong> <?php echo htmlspecialchars($study['instructions']); ?></p>
                <p class="card-text"><strong>Question:</strong> <?php echo htmlspecialchars($study['question']); ?></p>
                <p class="card-text"><strong>Source:</strong> <a href="<?php echo htmlspecialchars($study['source_link']); ?>" target="_blank" class="text-blue-500"><?php echo htmlspecialchars($study['source_link']); ?></a></p>

                <?php
                // Loop through employees who participated in this case study
                foreach ($study['employees'] as $employee) {
                    ?>
                    <div class="employee-card">
                        <p class="employee-name"><?php echo htmlspecialchars($employee['employee_name']); ?></p>
                        <p class="performance-marks"><strong>Marks Awarded:</strong> <?php echo htmlspecialchars($employee['case_marks']); ?></p>
                        <p class="performance-marks"><strong>Selected Answer:</strong> <?php echo htmlspecialchars($employee['selected_answer']); ?></p>
                        <p class="performance-marks"><strong>Correct Answer:</strong> <?php echo htmlspecialchars($employee['correct_answer']); ?></p>
                        <p class="performance-marks"><strong>Answered At:</strong> <?php echo htmlspecialchars($employee['answered_at']); ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>
