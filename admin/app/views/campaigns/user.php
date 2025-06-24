<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php
$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$apiBaseUrl = "https://3.93.236.247:3636/api/groups/";

// Handle form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $groupName = $_POST['group_name'];
    $emails = $_POST['email'];
    $firstNames = $_POST['first_name'];
    $lastNames = $_POST['last_name'];
    $positions = $_POST['position'];

    $targets = [];
    for ($i = 0; $i < count($emails); $i++) {
        if (!empty($emails[$i])) {
            $targets[] = [
                "email" => $emails[$i],
                "first_name" => $firstNames[$i],
                "last_name" => $lastNames[$i],
                "position" => $positions[$i]
            ];
        }
    }

    $payload = json_encode([
        "name" => $groupName,
        "targets" => $targets
    ]);

    $ch = curl_init($apiBaseUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    echo "<pre class='bg-green-100 text-green-800 p-4 rounded mt-4 font-semibold'>Group Created:\n" . htmlspecialchars($response) . "</pre>";
}

// Fetch groups
$ch = curl_init($apiBaseUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);
$groupsJson = curl_exec($ch);
curl_close($ch);

$groups = json_decode($groupsJson, true);
$isValidGroups = is_array($groups) && isset($groups[0]['name']);
?>

<!-- Form -->
<div class="p-6 bg-white rounded shadow-lg max-w-5xl mx-auto mt-10 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">➕ Create New User Group</h2>
    <form method="POST" id="groupForm">
        <div class="mb-4">
            <label class="block font-medium text-gray-700">Group Name:</label>
            <input type="text" name="group_name" required class="w-full p-2 border rounded">
        </div>

        <div id="targets-container" class="space-y-4">
            <div class="target-row grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="email" name="email[]" placeholder="Email" required class="p-2 border rounded w-full">
                <input type="text" name="first_name[]" placeholder="First Name" required class="p-2 border rounded w-full">
                <input type="text" name="last_name[]" placeholder="Last Name" required class="p-2 border rounded w-full">
                <input type="text" name="position[]" placeholder="Position" class="p-2 border rounded w-full">
            </div>
        </div>

        <button type="button" onclick="addTargetRow()" class="mt-4 bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700">➕ Add Another Target</button>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">✅ Create Group</button>
        </div>
    </form>
</div>

<!-- Display Groups -->
<div class="mt-16 max-w-6xl mx-auto px-4">
    <h2 class="text-3xl font-extrabold mb-6 text-gray-900">📋 Existing User Groups</h2>

    <?php if ($isValidGroups): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($groups as $group): ?>
                <div class="bg-white border shadow-md rounded-lg p-5">
                    <h3 class="text-xl font-bold text-blue-800 mb-2"><?= htmlspecialchars($group['name']) ?></h3>
                    <p class="text-sm text-gray-500 mb-3">Last Modified: <?= date("Y-m-d H:i", strtotime($group['modified_date'])) ?></p>

                    <h4 class="text-md font-semibold text-gray-700 mb-1">👥 Targets (<?= count($group['targets']) ?>):</h4>
                    <ul class="text-sm text-gray-600 list-disc pl-5 max-h-40 overflow-y-auto space-y-1">
                        <?php foreach ($group['targets'] as $target): ?>
                            <li><?= htmlspecialchars($target['first_name']) ?> <?= htmlspecialchars($target['last_name']) ?> - <span class="text-gray-800 font-medium"><?= htmlspecialchars($target['email']) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-red-600 text-center font-semibold mt-10">
            ⚠️ Failed to load groups. Check your API key or server.
        </div>
    <?php endif; ?>
</div>

<script>
function addTargetRow() {
    const container = document.getElementById('targets-container');
    const row = document.createElement('div');
    row.className = 'target-row grid grid-cols-1 md:grid-cols-4 gap-4 mt-2';
    row.innerHTML = `
        <input type="email" name="email[]" placeholder="Email" required class="p-2 border rounded w-full">
        <input type="text" name="first_name[]" placeholder="First Name" required class="p-2 border rounded w-full">
        <input type="text" name="last_name[]" placeholder="Last Name" required class="p-2 border rounded w-full">
        <input type="text" name="position[]" placeholder="Position" class="p-2 border rounded w-full">
    `;
    container.appendChild(row);
}
</script>
