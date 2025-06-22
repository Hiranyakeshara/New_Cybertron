<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="p-6 bg-gray-100 min-h-screen">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-blue-900">📧 CyberTrone Campaign Dashboard</h1>
        <p class="text-gray-600 text-md italic">Real-time phishing campaign analytics</p>
    </div>

<?php
$apiKey = "3bfca2468a7982f81288b76af71d16e3005b5b294792efa9ea0990fb9e449fcb";
$gophishBaseUrl = "https://localhost:3333/api/campaigns/";

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// Fetch all campaigns
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gophishBaseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$campaigns = json_decode($response, true);

if ($httpCode !== 200 || !is_array($campaigns)) {
    echo "<div class='text-red-600 text-center font-semibold'>⚠️ Failed to retrieve campaign data (HTTP $httpCode)</div>";
    exit;
}
?>

<div class="grid grid-cols-1 gap-6">
<?php foreach ($campaigns as $campaign): 
    $campaignId = $campaign['id'];
    $campaignName = htmlspecialchars($campaign['name']);
    $campaignStatus = htmlspecialchars($campaign['status']);

    // Get campaign results
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gophishBaseUrl . $campaignId . "/results");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $resultResponse = curl_exec($ch);
    curl_close($ch);

    $sent = $opened = $clicked = $reported = $submitted = 0;
    $userRows = "";

    if ($resultResponse) {
        $campaignResults = json_decode($resultResponse, true);

        if (isset($campaignResults['results'])) {
            foreach ($campaignResults['results'] as $entry) {
                $sent++;
                $status = $entry['status'] ?? 'N/A';
                $email = htmlspecialchars($entry['email']);
                $ip = htmlspecialchars($entry['ip'] ?? '-');
                $sentTime = date("Y-m-d H:i", strtotime($entry['send_date']));
                $modifiedTime = date("Y-m-d H:i", strtotime($entry['modified_date']));
                $rowColor = "";

                // Count statuses
                if ($status === "Email Opened") $opened++;
                if ($status === "Clicked Link") {
                    $clicked++;
                    $rowColor = "bg-yellow-100";
                }
                if ($status === "Submitted Data") {
                    $submitted++;
                    $rowColor = "bg-red-100";
                }
                if (!empty($entry['reported'])) $reported++;

                $userRows .= "<tr class='border-b hover:bg-gray-50 $rowColor'>
                    <td class='px-3 py-2'>$email</td>
                    <td class='px-3 py-2'>$status</td>
                    <td class='px-3 py-2'>$ip</td>
                    <td class='px-3 py-2'>$sentTime</td>
                    <td class='px-3 py-2'>$modifiedTime</td>
                </tr>";
            }
        }
    }
?>
    <div class="bg-white rounded shadow-lg p-4">
        <h2 class="text-xl font-bold text-blue-800 mb-2">📌 <?= $campaignName ?> <span class="text-sm text-gray-500">(<?= $campaignStatus ?>)</span></h2>
        <div class="grid grid-cols-5 text-center text-sm text-gray-700">
            <div><strong>Sent</strong><div class="text-xl text-blue-700"><?= $sent ?></div></div>
            <div><strong>Opened</strong><div class="text-xl text-green-600"><?= $opened ?></div></div>
            <div><strong>Clicked</strong><div class="text-xl text-yellow-600"><?= $clicked ?></div></div>
            <div><strong>Submitted</strong><div class="text-xl text-red-600"><?= $submitted ?></div></div>
            <div><strong>Reported</strong><div class="text-xl text-pink-600"><?= $reported ?></div></div>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-xs border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border">Email</th>
                        <th class="px-3 py-2 border">Status</th>
                        <th class="px-3 py-2 border">IP Address</th>
                        <th class="px-3 py-2 border">Sent Time</th>
                        <th class="px-3 py-2 border">Modified</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?= $userRows ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
