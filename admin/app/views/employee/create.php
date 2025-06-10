<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">👤 Add Employee</h2>

        <?php if (!empty($data['success'])): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">Employee created successfully!</div>
        <?php elseif (!empty($data['error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" required class="w-full mt-1 p-2 border rounded">
                    <option value="">Select Department</option>
                    <?php foreach ($data['departments'] as $dept): ?>
                        <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">NIC Number</label>
                <input type="text" name="nic" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Employee Email</label>
                <input type="text" name="email" required class="w-full p-2 border rounded">
            </div>

            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                ➕ Add Employee
            </button>
        </form>
    </div>
</div>

</div>
</body>
</html>
