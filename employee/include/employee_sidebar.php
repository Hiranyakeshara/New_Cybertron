<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Company Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1F2937;
            font-family: 'Arial', sans-serif;
            display: flex;
            height: 100vh;
        }

        .header-nav {
            background-color: #111827;
            width: 100%;
        }

        .header-nav a {
            text-transform: uppercase;
            color: #fff;
            padding: 10px 20px;
            transition: color 0.3s ease;
        }

        .header-nav a:hover {
            color: #10B981;
        }

        .cta-button {
            background-color: #10B981;
            color: white;
            padding: 12px 24px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 30px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #047857;
        }

        .card {
            background-color: #1F2937;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h3 {
            color: #fff;
        }

        .card p {
            color: #A0AEC0;
        }

        .footer {
            background-color: #111827;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }

        .footer a {
            color: #10B981;
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {
            background-color: #2D3748;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            color: #fff;
            display: block;
            padding: 15px;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #10B981;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        /* Company Name Styling */
        .company-name {
            background-color: #10B981;
            border-radius: 15px;
            padding: 5px 15px;
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <a href="employee_dashboard.php">Dashboard</a>
    <a href="emp_course.php">Courses</a>
    <a href="performance.php">Performance</a>
    <a href="emp_email.php">Email Campaigns</a>
   <a href="emp_settings.php">Settings</a> 
   <a href="../CEE">Access Quize Platform</a>
    <a href="emp_logout.php">Logout</a>
   
</div>



</body>
</html>
