<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$apiKey = "3bfca2468a7982f81288b76af71d16e3005b5b294792efa9ea0990fb9e449fcb";
$baseUrl = "https://localhost:3333/api/campaigns";

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// Debug Helper
function fetchFromGoPhish($url, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false || empty($response)) {
        http_response_code(500);
        die("❌ API Fetch Error: $error");
    }

    $json = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo "❌ JSON Decode Error: " . json_last_error_msg() . "<br><pre>$response</pre>";
        exit();
    }

    return $json;
}

// Fetch campaigns
$allCampaigns = fetchFromGoPhish($baseUrl, $headers);

// Filter for logged employee
$emp_email = $_SESSION['employee_email'] ?? '';
$matchedResults = [];

foreach ($allCampaigns as $campaign) {
    if (!isset($campaign['results'])) continue;

    foreach ($campaign['results'] as $result) {
        if (isset($result['email']) && $result['email'] === $emp_email) {
            $matchedResults[] = [
                'campaign_id'   => $campaign['id'],
                'campaign_name' => $campaign['name'],
                'status'        => $result['status'],
                'ip'            => $result['ip'],
                'first_name'    => $result['first_name'],
                'last_name'     => $result['last_name'],
                'position'      => $result['position'],
                'send_date'     => $result['send_date'],
                'reported'      => $result['reported'] ? 'Yes' : 'No'
            ];
        }
    }
}

// Output result as JSON
header('Content-Type: application/json');
echo json_encode([
    'employee' => $emp_email,
    'total_campaigns' => count($allCampaigns),
    'matched_results' => $matchedResults
], JSON_PRETTY_PRINT);
?>
