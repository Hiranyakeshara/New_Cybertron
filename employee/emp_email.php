<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$baseUrl = "https://3.93.236.247:3636/api/campaigns";

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

function fetchFromGoPhish($url, $headers) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        http_response_code(500);
        die("❌ cURL Error: " . curl_error($ch));
    }

    echo "<pre style='color:red'>RAW API RESPONSE:\n" . htmlspecialchars($response) . "</pre>"; // 🧪 Print response for debugging

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        die("❌ JSON Decode Error: " . json_last_error_msg());
    }

    return $data;
}


// Get campaigns
$allCampaigns = fetchFromGoPhish($baseUrl, $headers);

// Filter for current employee
$empEmail = $_SESSION['employee_email'] ?? '';
$matchedResults = [];

foreach ($allCampaigns as $campaign) {
    if (!isset($campaign['results']) || !is_array($campaign['results'])) continue;

    foreach ($campaign['results'] as $result) {
        if (!isset($result['email']) || $result['email'] !== $empEmail) continue;

        // Determine action taken
        $status = "Email Sent";
        if (!empty($result['opened'])) $status = "Email Opened";
        if (!empty($result['clicked'])) $status = "Link Clicked";
        if (!empty($result['submitted_data'])) $status = "Credentials Submitted";

        $matchedResults[] = [
            'campaign_id'   => $campaign['id'],
            'campaign_name' => $campaign['name'],
            'status'        => $status,
            'send_date'     => $result['send_date'] ?? 'N/A',
            'ip'            => $result['ip'] ?? 'N/A',
            'reported'      => !empty($result['reported']) ? 'Yes' : 'No'
        ];
    }
}

// Return result as JSON
header('Content-Type: application/json');
echo json_encode([
    'employee_email' => $empEmail,
    'campaign_count' => count($matchedResults),
    'campaigns' => $matchedResults
], JSON_PRETTY_PRINT);
?>
