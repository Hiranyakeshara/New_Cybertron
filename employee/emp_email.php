<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ======== Session Check ========
if (!isset($_SESSION['employee_id']) || !isset($_SESSION['employee_email'])) {
    header("Location: employee_login.php");
    exit();
}

$employeeEmail = $_SESSION['employee_email'];

// ======== GoPhish API Config ========
$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$apiBaseUrl = "https://3.93.236.247:3636/api/campaigns/";

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// ======== cURL Helper ========
function callGoPhishAPI($url, $headers) {
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
            "error" => "HTTP $httpCode | cURL: $curlError",
            "raw_response" => $response
        ];
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "error" => "JSON Decode Error: " . json_last_error_msg(),
            "raw_response" => $response
        ];
    }

    return $data;
}

// ======== Fetch All Campaigns ========
$allCampaigns = callGoPhishAPI($apiBaseUrl, $headers);
$filteredCampaigns = [];

if (!isset($allCampaigns['error']) && is_array($allCampaigns)) {
    foreach ($allCampaigns as $campaign) {
        $campaignId = $campaign['id'] ?? null;
        $campaignName = $campaign['name'] ?? 'Unnamed Campaign';

        if (!$campaignId) continue;

        // ======== Fetch Campaign Results ========
        $resultUrl = $apiBaseUrl . $campaignId . "/results";
        $campaignResults = callGoPhishAPI($resultUrl, $headers);

        if (isset($campaignResults['error']) || !isset($campaignResults['results'])) continue;

        foreach ($campaignResults['results'] as $result) {
            if (!isset($result['email'])) continue;

            if (strtolower($result['email']) === strtolower($employeeEmail)) {
                $status = strtolower($result['status'] ?? '');

                // Only include if status is "clicked link" or "submitted data"
                if ($status === 'clicked link' || $status === 'submitted data') {
                    $filteredCampaigns[] = [
                        "campaign_name" => $campaignName,
                        "status"        => $result['status'] ?? 'N/A',
                        "ip"            => $result['ip'] ?? 'N/A',
                        "send_date"     => $result['send_date'] ?? 'N/A',
                        "reported"      => !empty($result['reported']) ? 'Yes' : 'No'
                    ];
                }
            }
        }
    }
} else {
    echo "<p style='color:red;'>❌ Error fetching campaigns list.</p>";
    if (isset($allCampaigns['error'])) {
        echo "<pre>" . htmlspecialchars($allCampaigns['error']) . "</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CyberTrone - My Email Campaign Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #1F2937; font-family: Arial, sans-serif; display: flex; min-height: 100vh; }
        .content { margin-left: 250px; padding: 20px; width: 100%; }
        .card {
            background-color: #2D3748;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .card h2 { color: #10B981; margin-bottom: 10px; }
        .card p { color: #CBD5E1; }
    </style>
</head>
<body>
<?php include_once("./include/employee_sidebar.php"); ?>

<div class="content">
    <h1 class="text-white text-3xl font-bold mb-6">Phishing Campaign Results for: <?= htmlspecialchars($employeeEmail) ?></h1>

    <?php if (empty($filteredCampaigns)): ?>
        <div class="p-6 text-center bg-green-800 text-green-100 rounded-lg shadow-lg">
            🎉 No Click or Submission activity found for your email.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($filteredCampaigns as $campaign): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($campaign['campaign_name']) ?></h2>
                    <p><strong>Status:</strong> <?= htmlspecialchars($campaign['status']) ?></p>
                    <p><strong>Send Date:</strong> <?= htmlspecialchars($campaign['send_date']) ?></p>
                    <p><strong>IP Address:</strong> <?= htmlspecialchars($campaign['ip']) ?></p>
                    <p><strong>Reported:</strong> <?= htmlspecialchars($campaign['reported']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
