<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cee_db"; // Replace with your actual database name

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Query 1: Total Examinees per Year Level
$query1 = "SELECT exmne_year_level, COUNT(*) as total_examinees FROM examinee_tbl GROUP BY exmne_year_level";
$stmt1 = $conn->prepare($query1);
$stmt1->execute();
$data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$yearLevels = [];
$yearTotals = [];
foreach ($data1 as $row) {
    $yearLevels[] = $row['exmne_year_level'];
    $yearTotals[] = $row['total_examinees'];
}

// Query 2: Examinees by Gender
$query2 = "SELECT exmne_gender, COUNT(*) as total_examinees FROM examinee_tbl GROUP BY exmne_gender";
$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$genders = [];
$genderTotals = [];
foreach ($data2 as $row) {
    $genders[] = $row['exmne_gender'];
    $genderTotals[] = $row['total_examinees'];
}

// Query 3: Examinees Added per Year
$query3 = "SELECT YEAR(exmne_birthdate) as year, COUNT(*) as total_examinees FROM examinee_tbl GROUP BY YEAR(exmne_birthdate) ORDER BY year";
$stmt3 = $conn->prepare($query3);
$stmt3->execute();
$data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
$years = [];
$yearlyTotals = [];
foreach ($data3 as $row) {
    $years[] = $row['year'];
    $yearlyTotals[] = $row['total_examinees'];
}

// Query 4: Examinees by Course
$query4 = "SELECT exmne_course, COUNT(*) as total_examinees FROM examinee_tbl GROUP BY exmne_course";
$stmt4 = $conn->prepare($query4);
$stmt4->execute();
$data4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
$courses = [];
$courseTotals = [];
foreach ($data4 as $row) {
    $courses[] = $row['exmne_course'];
    $courseTotals[] = $row['total_examinees'];
}

// Query 5: Examinees Added per Month (Time Series)
$query5 = "SELECT DATE_FORMAT(exmne_birthdate, '%Y-%m') as month, COUNT(*) as total_examinees FROM examinee_tbl GROUP BY month ORDER BY month";
$stmt5 = $conn->prepare($query5);
$stmt5->execute();
$data5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
$months = [];
$monthlyTotals = [];
foreach ($data5 as $row) {
    $months[] = $row['month'];
    $monthlyTotals[] = $row['total_examinees'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        .chart-container {
            width: 45%;
            margin: 20px;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .charts {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Aligns charts in the center */
        }
        h2 {
            font-size: 18px;
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>
<body>
   

    <div class="charts">
        <div class="chart-container">
            <h2>Total Employees per  Level</h2>
            <canvas id="yearLevelChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Employees by Gender</h2>
            <canvas id="genderChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Employees Added per Year</h2>
            <canvas id="yearlyChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Employees by Department</h2>
            <canvas id="courseChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>Employees Added per Month</h2>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <script>
        // Chart 1: Total Examinees per Year Level
        var yearLevelCtx = document.getElementById('yearLevelChart').getContext('2d');
        var yearLevelChart = new Chart(yearLevelCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($yearLevels); ?>,
                datasets: [{
                    label: 'Total Employees',
                    data: <?php echo json_encode($yearTotals); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 2: Examinees by Gender
        var genderCtx = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($genders); ?>,
                datasets: [{
                    data: <?php echo json_encode($genderTotals); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            }
        });

        // Chart 3: Examinees Added per Year
        var yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        var yearlyChart = new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [{
                    label: 'Total Employees',
                    data: <?php echo json_encode($yearlyTotals); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 4: Examinees by Course
        var courseCtx = document.getElementById('courseChart').getContext('2d');
        var courseChart = new Chart(courseCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($courses); ?>,
                datasets: [{
                    label: 'Total Employees',
                    data: <?php echo json_encode($courseTotals); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 5: Examinees Added per Month (Time Series)
        var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        var monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Total Employees',
                    data: <?php echo json_encode($monthlyTotals); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
