<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

  <?php if (isset($_GET['exists'])): ?>
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
        ⚠️ This employee is already added to the Quiz platform.
    </div>
<?php endif; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">👥 All Employees</h2>

        <table class="min-w-full table-auto border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">NIC</th>
                    <th class="px-4 py-2 border">Contact</th>
                    <th class="px-4 py-2 border">Department</th>
                    <th class="px-4 py-2 border">Email</th>
                       <th class="px-4 py-2 border">Action Buttons</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($data['employees'])): ?>
        <?php foreach ($data['employees'] as $index => $emp): ?>
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2"><?= $index + 1 ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['name']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['username']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['nic']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['contact_number']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['department_name']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($emp['email']) ?></td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="update_employee.php?id=<?= $emp['id'] ?>"
                       class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">Update</a>
                    <a href="delete_employee.php?id=<?= $emp['id'] ?>"
                       class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                       onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                  <a href="/New_Cybertron/admin/app/controllers/pushEmployeeToQuiz.php?id=<?= $emp['id'] ?>"
   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
   Add to Quiz Platform
 

</a>

                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8" class="text-center py-4 text-gray-500">No employees found.</td></tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</div>

</div>
</body>
</html>
