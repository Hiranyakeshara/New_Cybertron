<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


</head>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Admin Header -->
    <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="https://img.icons8.com/fluency/48/security-checked.png" class="w-8 h-8" alt="Logo">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="/New_Cybertron/admin/public/admin/dashboard" class="hover:text-indigo-600">Cybertron Admin Panel</a>
            </h1>
        </div>
        <div class="flex items-center gap-6">
          

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
