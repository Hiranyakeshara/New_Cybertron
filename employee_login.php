<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Employee Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Body background */
        body {
            background: url('https://img.freepik.com/free-vector/cyber-security-concept_53876-93472.jpg?t=st=1740174585~exp=1740178185~hmac=d87598005a9fb2d96d465c48e4c50a1d9f01cbd2ade0440fd66229e419a8846b&w=1060') no-repeat center center fixed;
            background-size: 100% 100%;
            font-family: 'Arial', sans-serif;
        }

        /* Navbar styling */
        .navbar {
            background-color: #111827;
            padding: 16px;
            color: #fff;
        }

        .navbar a {
            text-transform: uppercase;
            color: #fff;
            padding: 10px 20px;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #10B981;
        }

        /* Hero section styling */
        .hero-section {
            position: relative;
            background: url('https://img.freepik.com/free-photo/cyber-security-protection-firewall-interface-concept_53876-125636.jpg?t=st=1739475961~exp=1739479561~hmac=8ef337d77e856e0785ec04fe198a19682d91d350978e0e7ed2e276e0cc88ab87&w=1380') no-repeat center center;
            background-size: cover;
            padding: 120px 0;
            text-align: center;
            color: #fff;
            z-index: 1;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        /* Card styling */
        .form-card {
            background-color: #1F2937;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-10px);
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
    </style>
</head>
<body class="bg-gray-900 text-white">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold text-green-400">CyberTrone</a>
            <a href="index.php" class="bg-[#10B981] px-4 py-2 rounded-lg hover:bg-green-600">Back to Home</a>
        </div>
    </nav>

    <!-- Employee Login Portal -->
    <section class="h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-5xl flex gap-6 bg-gray-900 bg-opacity-80 p-8 rounded-lg shadow-lg border border-gray-700">
            <!-- Employee Login Form Card -->
            <div class="w-full bg-gray-800 p-8 rounded-lg shadow-lg border border-gray-700 form-card">
                <h2 class="text-2xl font-bold mb-4 text-green-400">Employee Login</h2>

                <form method="post" action="employee_log.php">
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Email</label>
                        <input name="email" type="email" class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]" placeholder="Enter your email">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Password</label>
                        <input name="password" type="password" class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]" placeholder="Enter your password">
                    </div>
                    <button name="emp_log" class="w-full bg-[#10B981] px-4 py-2 rounded-lg hover:bg-green-600 cta-button" id="emplog">Login</button>
                </form>
                
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 CyberTrone. All rights reserved.</p>
    </footer>

</body>
</html>
