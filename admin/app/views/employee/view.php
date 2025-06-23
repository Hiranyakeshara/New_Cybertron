<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<!-- Alpine.js for modal control -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['message'])) {
    $type = $_SESSION['message_type'] ?? 'info';

    $bg = match ($type) {
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'error'   => 'bg-red-100 border-red-500 text-red-700',
        default   => 'bg-blue-100 border-blue-500 text-blue-700',
    };

    echo "<div class='border-l-4 p-4 mb-4 rounded $bg'>" . htmlspecialchars($_SESSION['message']) . "</div>";

    unset($_SESSION['message'], $_SESSION['message_type']);
}
?>

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
                    <th class="px-4 py-2 border">Department Code</th>
                    <th class="px-4 py-2 border">Department</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Action Buttons</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['employees'])): ?>
                    <?php foreach ($data['employees'] as $index => $emp): ?>
                        <tr x-data="{ openModal: false }" class="hover:bg-gray-50 relative">
                            <td class="border px-4 py-2"><?= $index + 1 ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['username']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['nic']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['contact_number']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['department_code']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['department_name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($emp['email']) ?></td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="/New_Cybertron/admin/public/employee/delete/<?= $emp['id'] ?>"
                                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                   Delete
                                </a>

                                <button @click="openModal = true"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                   Update
                                </button>

                                <a href="/New_Cybertron/admin/app/controllers/pushEmployeeToQuiz.php?id=<?= $emp['id'] ?>"
                                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                   Add to Quiz Platform
                                </a>

                                <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div @click.away="openModal = false"
                                         class="bg-white rounded-lg p-6 w-full max-w-lg space-y-4 shadow-xl">
                                        <h2 class="text-xl font-bold">✏️ Edit Employee</h2>
                                        <form method="POST" action="/New_Cybertron/admin/public/employee/update" class="space-y-4">
                                            <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                            <input type="text" name="name" value="<?= htmlspecialchars($emp['name']) ?>" class="w-full border p-2 rounded" required>
                                            <input type="text" name="username" value="<?= htmlspecialchars($emp['username']) ?>" class="w-full border p-2 rounded" required>
                                            <input type="text" name="nic" value="<?= htmlspecialchars($emp['nic']) ?>" class="w-full border p-2 rounded" required>
                                            <input type="text" name="contact_number" value="<?= htmlspecialchars($emp['contact_number']) ?>" class="w-full border p-2 rounded" required>
                                            <input type="email" name="email" value="<?= htmlspecialchars($emp['email']) ?>" class="w-full border p-2 rounded" required>
                                            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full border p-2 rounded">
                                            <div class="flex justify-end space-x-3">
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
                                                <button type="button" @click="openModal = false" class="px-4 py-2 border rounded">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
