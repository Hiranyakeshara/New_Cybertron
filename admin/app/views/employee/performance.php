<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<body class="bg-gray-100 min-h-screen p-6">

 

    <!-- 🧾 Main Section -->
    <div class="max-w-7xl mx-auto">

       <!-- 🧭 CyberTrone Top Title Header -->
    <div class="text-center my-10">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white py-4 px-6 rounded-lg shadow-md inline-block gradient-header"
             style="background: linear-gradient(to right, #1e3a8a, #3b82f6);">
            🚀 CyberTrone Performance Panel
        </h1>
        <p class="mt-2 text-gray-600 text-md md:text-lg italic">
            Live analytics & performance breakdown powered by <span class="text-blue-700 font-semibold">CyberTrone</span>
        </p>
    </div>
        <?php include_once __DIR__ . '/fetch.php'; ?>
    </div>

</body>
</html>
