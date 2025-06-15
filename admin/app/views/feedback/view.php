<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="ml-[220px] p-6 font-sans">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">🗨️ User Feedback</h2>


    <div class="overflow-x-auto shadow border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">ID</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Name</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Email</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Message</th>
                    <th class="px-6 py-3 text-sm text-center font-medium uppercase">Submitted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php if (empty($feedbacks)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">No feedback found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($feedbacks as $fb): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($fb['id']) ?></td>
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($fb['name']) ?></td>
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($fb['email']) ?></td>
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($fb['message']) ?></td>
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($fb['submitted_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
