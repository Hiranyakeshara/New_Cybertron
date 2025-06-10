<?php include_once './app/views/layout/header.php'; ?>
<?php include_once './app/views/layout/sidebar.php'; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="max-w-xl bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">➕ Create Department</h2>

        <?php if (!empty($data['success'])): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                Department created successfully!
            </div>
        <?php elseif (!empty($data['error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?= htmlspecialchars($data['error']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Department Code</label>
                <input type="text" name="department_code" required
                       class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Department Name</label>
                <input type="text" name="department_name" required
                       class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit"
                    class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">
                Create Department
            </button>
        </form>
    </div>
</div>

</div> <!-- Close flex from sidebar -->
</body>
</html>
