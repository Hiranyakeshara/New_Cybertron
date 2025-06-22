<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Cybertrone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- Darker Admin Header -->
<header class="bg-gray-900 text-white shadow-md">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <!-- Logo and Title -->
    <div class="flex items-center space-x-3">
      <img src="https://img.icons8.com/fluency/48/security-checked.png" alt="Logo" class="w-10 h-10">
      <a href="/New_Cybertron/admin/public/admin/dashboard" class="text-2xl font-extrabold tracking-wide text-white hover:text-yellow-400 transition">
        CYBERTRONE
      </a>
    </div>

    <!-- Right Side Profile Dropdown -->
    <div class="relative" x-data="{ open: false }">
      <button @click="open = !open" class="flex items-center space-x-2 bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-full transition">
        <img src="https://ui-avatars.com/api/?name=Admin&background=1f2937&color=fff" alt="Admin" class="w-9 h-9 rounded-full ring-2 ring-white">
        <span class="text-sm font-semibold">Admin</span>
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>

      <!-- Dropdown -->
      <div x-show="open" @click.away="open = false" x-transition
           class="absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded shadow-lg z-50">
        <a href="/New_Cybertron/admin/public/logout" class="block px-4 py-2 hover:bg-red-50 text-red-600 font-medium text-sm">Logout</a>
      </div>
    </div>
  </div>
</header>

</body>
</html>
