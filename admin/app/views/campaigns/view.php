<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">📧 Gophish Campaign Dashboard</h1>

<?php
// Replace with your actual API key
$apiKey = "ddb5949962c39c5955beb2d9fc942c6593d6fc1899cd8bc4946d07572a9212dd";
$gophishUrl = "https://localhost:3333/api/campaigns/"; // trailing slash is fine

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gophishUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // dev only
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$campaigns = json_decode($response, true);

// Display error if failed
if ($httpCode !== 200 || !is_array($campaigns)) {
    echo "<p class='text-red-500 font-semibold'>⚠️ Failed to fetch campaign data. HTTP Code: $httpCode</p>";
    echo "<pre class='text-xs text-red-400 bg-red-50 p-2 border border-red-300 rounded'>" . htmlspecialchars($response) . "</pre>";
} else {
?>

<!-- Table -->
<div class="overflow-x-auto">
    <table class="table-auto w-full border-collapse border border-gray-300 mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Campaign Name</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Emails Sent</th>
                <th class="border px-4 py-2">Emails Opened</th>
                <th class="border px-4 py-2">Clicked</th>
                <th class="border px-4 py-2">Reported</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($campaigns as $campaign): 
            $sent = count($campaign['results']);
            $opened = count(array_filter($campaign['results'], fn($r) => $r['status'] === 'Opened'));
            $clicked = count(array_filter($campaign['results'], fn($r) => $r['status'] === 'Clicked'));
            $reported = count(array_filter($campaign['results'], fn($r) => $r['status'] === 'Reported'));
        ?>
            <tr class="bg-white hover:bg-gray-100">
                <td class="border px-4 py-2"><?= htmlspecialchars($campaign['name']) ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($campaign['status']) ?></td>
                <td class="border px-4 py-2"><?= $sent ?></td>
                <td class="border px-4 py-2"><?= $opened ?></td>
                <td class="border px-4 py-2"><?= $clicked ?></td>
                <td class="border px-4 py-2"><?= $reported ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php } ?>
</div>
