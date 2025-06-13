<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Admin Header -->
    <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="https://img.icons8.com/fluency/48/security-checked.png" class="w-8 h-8" alt="Logo">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="/cyber-training-platform/public/admin/dashboard" class="hover:text-indigo-600">Cybertron Admin Panel</a>
            </h1>
        </div>
        <div class="flex items-center gap-6">
            <!-- Notification Bell -->
            <div class="relative">
                <svg class="w-6 h-6 text-gray-700 hover:text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a2 2 0 10-4 0v1.083A6 6 0 004 11v3.159c0 .538-.214 1.055-.595 1.436L2 17h5m7 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute top-0 right-0 block w-2 h-2 bg-red-600 rounded-full"></span>
            </div>

            <!-- Admin Avatar and Dropdown -->
            <div class="relative">
                <button class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 p-2 rounded-full">
                    <img class="w-8 h-8 rounded-full ring-2 ring-indigo-500" src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" alt="Admin Avatar">
                    <span class="text-white font-medium">Admin</span>
                </button>
                
                <!-- Dropdown Menu -->
              <a href="/New_Cybertron/admin/public/logout" 
   class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>

            </div>
        </div>
    </header>

    <script>
        // Toggle the dropdown visibility when clicking on the Admin avatar
        document.querySelector('button').addEventListener('click', function() {
            const dropdown = document.getElementById('admin-dropdown');
            dropdown.classList.toggle('hidden');  // Toggle visibility of the dropdown
        });
    </script>



    <!-- Optional: Logout logic (to be implemented on the server-side) -->
    <!-- Example: Include a PHP route to handle the logout -->
</body>
</html>
