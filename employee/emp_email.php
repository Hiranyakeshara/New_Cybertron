<?php
// GoPhish API Configuration
$apiKey = "3bfca2468a7982f81288b76af71d16e3005b5b294792efa9ea0990fb9e449fcb";
$baseUrl = "https://localhost:3333/api/campaigns";

// cURL headers
$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

// Function to GET from GoPhish API
function fetchCampaigns($url, $headers) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($response === false) {
        die("❌ API Fetch Error: $error");
    }

    $json = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("❌ JSON Decode Error: " . json_last_error_msg());
    }

    return $json;
}

// 🟢 Fetch All Campaigns
$allCampaigns = fetchCampaigns($baseUrl, $headers);

// Optional: Output JSON for testing
header('Content-Type: application/json');
echo json_encode($allCampaigns, JSON_PRETTY_PRINT);
?>
