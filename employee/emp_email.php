<?php
session_start();

// ======== Session Check ========
if (!isset($_SESSION['employee_id']) || !isset($_SESSION['employee_email'])) {
    header("Location: employee_login.php");
    exit();
}

$employeeEmail = $_SESSION['employee_email'];

// ======== GoPhish API Config ========
$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$apiUrl = "https://3.93.236.247:3636/api/campaigns/";

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// ======== Fetch Campaigns Function ========
function fetchCampaignData($url, $headers) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        return [
            "error" => "❌ Failed to fetch campaigns. HTTP: $httpCode - cURL: $curlError",
            "raw_response" => $response
        ];
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "error" => "❌ JSON Decode Error: " . json_last_error_msg(),
            "raw_response" => $response
        ];
    }

    return $data;
}

// ======== Process Campaign Data ========
$apiData = fetchCampaignData($apiUrl, $headers);
$filteredCampaigns = [];

if (!isset($apiData['error'])) {
    foreach ($apiData as $campaign) {
        if (!isset($campaign['results']) || !is_array($campaign['results'])) continue;

        foreach ($campaign['results'] as $result) {
            if (!isset($result['email']) || strtolower($result['email']) !== strtolower($employeeEmail)) continue;

            if (!empty($result['clicked']) || !empty($result['submitted_data'])) {
                $status = !empty($result['submitted_data']) ? "Credentials Submitted" : "Link Clicked";

                $filteredCampaigns[] = [
                    "campaign_id"   => $campaign['id'] ?? 'N/A',
                    "campaign_name" => $campaign['name'] ?? 'N/A',
                    "status"        => $status,
                    "send_date"     => $result['send_date'] ?? 'N/A',
                    "ip"            => $result['ip'] ?? 'N/A',
                    "reported"      => !empty($result['reported']) ? 'Yes' : 'No'
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #1F2937; font-family: 'Arial', sans-serif; display: flex; min-height: 100vh; }
        .header-nav { background-color: #111827; }
        .header-nav a { color: white; font-weight: bold; text-transform: uppercase; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }
        .card {
            background-color: #2D3748;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.3);
        }
        .card h2 { color: #10B981; font-size: 22px; margin-bottom: 15px; }
        .card p { color: #CBD5E1; margin-bottom: 10px; }
        .card span { color: #F9FAFB; font-weight: bold; }
    </style>
</head>

<body>
<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">
    <h1 class="text-white text-3xl font-bold mb-6">Phishing Campaign Summary</h1>

    <?php if (isset($apiData['error'])): ?>
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-400">
            <?= htmlspecialchars($apiData['error']) ?>
        </div>
    <?php elseif (count($filteredCampaigns) === 0): ?>
        <div class="p-6 text-center bg-green-800 text-green-100 rounded-lg shadow-lg">
            🎉 <strong>Good news!</strong> You were <span class="text-white underline">not targeted</span> by any phishing campaign. Stay vigilant and keep up the great work! 🛡️
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($filteredCampaigns as $campaign): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($campaign['campaign_name']) ?></h2>
                    <p><span>Status:</span> <?= $campaign['status'] ?></p>
                    <p><span>Send Date:</span> <?= $campaign['send_date'] ?></p>
                    <p><span>IP Address:</span> <?= $campaign['ip'] ?></p>
                    <p><span>Reported:</span> <?= $campaign['reported'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
