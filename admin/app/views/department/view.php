<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📁 All Departments</h2>

        <table class="min-w-full table-auto border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Department Code</th>
                    <th class="px-4 py-2 border">Department Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['departments'])): ?>
                    <?php foreach ($data['departments'] as $index => $dept): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= $index + 1 ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($dept['department_code']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($dept['department_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">No departments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div> <!-- End flex -->
</body>
</html>
