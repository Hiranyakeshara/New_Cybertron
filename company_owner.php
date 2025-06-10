<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Company Owner Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Background */
        body {
            background: url('https://img.freepik.com/free-vector/cyber-security-concept_53876-93472.jpg?t=st=1740174585~exp=1740178185~hmac=d87598005a9fb2d96d465c48e4c50a1d9f01cbd2ade0440fd66229e419a8846b&w=1060') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
        }

        /* Navbar */
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

        /* Form container */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        /* Card styling */
        .form-card {
            width: 400px; 
            background-color: #1F2937;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
            border: 1px solid #2D3748;
        }

        .form-card:hover {
            transform: translateY(-10px);
        }

        .cta-button {
            background-color: #10B981;
            color: white;
            padding: 12px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #047857;
        }

        .footer {
            background-color: #111827;
            color: #fff;
            padding: 20px 0;
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

    <!-- Forms Section -->
    <div class="form-container">
        <div class="flex gap-8">

            <!-- Registration Form -->
            <div class="form-card">
                <h2 class="text-2xl font-bold mb-4 text-green-400">Company Owner Registration</h2>
                <form action="company_owner_reg.php" method="post">
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Registration Number (Gov)</label>
                        <input type="text" name="reg_number" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Company Name</label>
                        <input type="text" name="company_name" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <button type="submit" name="owner_reg" class="w-full cta-button">
                        Register
                    </button>
                </form>
            </div>

            <!-- Login Form -->
            <div class="form-card">
                <h2 class="text-2xl font-bold mb-4 text-green-400">Company Owner Login</h2>
                <form action="company_owner_log.php" method="post">
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Registration Number (Gov)</label>
                        <input type="text" name="reg_number" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <button type="submit" name="owner_login" class="w-full cta-button">
                        Login
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 CyberTrone. All rights reserved.</p>
    </footer>

</body>
</html>
