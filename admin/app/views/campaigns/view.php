<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="ml-[220px] p-6 font-sans">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">📧 Email Campaigns Overview</h2>

    <!-- 🔍 Search Bar -->
    <form method="GET" class="mb-6 flex items-center gap-3">
        <input 
            type="text" 
            name="search" 
            placeholder="Search employee..." 
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
            class="w-[250px] px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
        <button 
            type="submit" 
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            Search
        </button>
    </form>

    <?php
    $campaigns = [
        ['employee' => 'John Doe', 'title' => 'Welcome Campaign', 'sent_on' => '2025-06-10', 'total_sent' => 100, 'opened' => 75, 'clicked' => 30],
        ['employee' => 'Jane Smith', 'title' => 'Summer Sale Promo', 'sent_on' => '2025-06-05', 'total_sent' => 200, 'opened' => 140, 'clicked' => 50],
        ['employee' => 'John Doe', 'title' => 'Weekly Newsletter', 'sent_on' => '2025-06-01', 'total_sent' => 150, 'opened' => 90, 'clicked' => 25],
        ['employee' => 'Mark Henry', 'title' => 'Event Followup', 'sent_on' => '2025-05-28', 'total_sent' => 180, 'opened' => 120, 'clicked' => 60],
    ];

    $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
    $filtered = array_filter($campaigns, fn($c) => $search === '' || str_contains(strtolower($c['employee']), $search));
    ?>

    <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Employee</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Campaign Title</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Date Sent</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Total Sent</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Opened</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Clicked</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Open Rate</th>
                    <th class="px-6 py-3 text-center text-sm font-medium uppercase">Click Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php if (empty($filtered)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">No campaigns found for your search.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($filtered as $c): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($c['employee']) ?></td>
                            <td class="text-center px-6 py-4"><?= htmlspecialchars($c['title']) ?></td>
                            <td class="text-center px-6 py-4"><?= $c['sent_on'] ?></td>
                            <td class="text-center px-6 py-4"><?= $c['total_sent'] ?></td>
                            <td class="text-center px-6 py-4"><?= $c['opened'] ?></td>
                            <td class="text-center px-6 py-4"><?= $c['clicked'] ?></td>
                            <td class="text-center px-6 py-4 text-green-600 font-semibold"><?= round(($c['opened'] / $c['total_sent']) * 100, 2) ?>%</td>
                            <td class="text-center px-6 py-4 text-blue-600 font-semibold"><?= round(($c['clicked'] / $c['total_sent']) * 100, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


