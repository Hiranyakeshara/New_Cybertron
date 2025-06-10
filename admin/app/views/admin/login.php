<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- You can add other CDN links here if needed, for example, Google Fonts or any other CSS -->
</head>
<body class="bg-gray-50">

    <div class="flex justify-center items-center h-screen bg-gray-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-xs w-full">
            <h2 class="text-2xl font-bold mb-4">Admin Login</h2>

            <?php if (!empty($data['error'])): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($data['error']) ?></div>
            <?php endif; ?>

            <form method="POST" action="New_Cybertron/admin/public/admin/login" class="space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded">
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">Login</button>
            </form>

           
        </div>
    </div>

</body>
</html>
