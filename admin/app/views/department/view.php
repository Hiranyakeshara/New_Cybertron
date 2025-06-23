<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<!-- Alpine.js for modal control -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📁 All Departments</h2>

       <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['push_status'])): ?>
    <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">
        <?= $_SESSION['push_status']; unset($_SESSION['push_status']); ?>
    </div>
<?php endif; ?>

        <table class="min-w-full table-auto border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Department Code</th>
                    <th class="px-4 py-2 border">Department Name</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['departments'])): ?>
                    <?php foreach ($data['departments'] as $index => $dept): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= $index + 1 ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($dept['department_code']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($dept['department_name']) ?></td>
                            <td class="border px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Push to Quiz -->
                                  <form method="POST" action="/New_Cybertron/admin/public/pushToQuiz/handlePush">
    <input type="hidden" name="cou_id" value="<?= htmlspecialchars($dept['department_code']) ?>">
    <input type="hidden" name="cou_name" value="<?= htmlspecialchars($dept['department_name']) ?>">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
        Push to Quiz
    </button>
</form>


                                    <!-- Edit Modal Button -->
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button @click="open = true"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                            Edit
                                        </button>
                                        <!-- Modal -->
                                        <div x-show="open"
                                            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                                            <div @click.away="open = false"
                                                class="bg-white p-6 rounded-lg w-full max-w-md space-y-4 shadow-lg">
                                                <h3 class="text-xl font-bold text-gray-800">✏️ Edit Department</h3>
                                                <form method="POST" action="/New_Cybertron/admin/public/department/update" class="space-y-4">
                                                    <input type="hidden" name="id" value="<?= $dept['id'] ?>">
                                                    <input type="text" name="department_code"
                                                        value="<?= htmlspecialchars($dept['department_code']) ?>"
                                                        placeholder="Department Code" class="w-full border p-2 rounded" required>
                                                    <input type="text" name="department_name"
                                                        value="<?= htmlspecialchars($dept['department_name']) ?>"
                                                        placeholder="Department Name" class="w-full border p-2 rounded" required>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="submit"
                                                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                                            Update
                                                        </button>
                                                        <button type="button" @click="open = false"
                                                            class="border px-4 py-2 rounded hover:bg-gray-100">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4">No departments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div> <!-- End flex -->
</body>
</html>
