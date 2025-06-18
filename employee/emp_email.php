<?php
session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$emp_email = $_SESSION['employee_email'];
$emp_name = $_SESSION['username'];

// Gophish API config
$apiKey = "5abc3c5be853578ceea84effc4085a764754cbed789090ccdfa26cce7ad18cfa";  // Replace with real API key
$apiUrl = "https://localhost:3333/api/campaigns/";  // Adjust if hosted differently

// Prepare headers
$options = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: Bearer $apiKey\r\n"
    ]
];
$context = stream_context_create($options);

// Get campaigns
$response = @file_get_contents($apiUrl, false, $context);
$campaignData = json_decode($response, true);

$matchedCampaigns = [];

if ($campaignData && is_array($campaignData)) {
    foreach ($campaignData as $campaign) {
        if (isset($campaign['results']) && is_array($campaign['results'])) {
            foreach ($campaign['results'] as $result) {
                if ($result['email'] === $emp_email) {
                    $matchedCampaigns[] = [
                        'campaign' => $campaign['name'],
                        'email' => $result['email'],
                        'status' => $result['status'],
                        'timestamp' => $result['modified_date']
                    ];
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Phishing Campaign Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white p-6">
    <?php include_once("./include/employee_sidebar.php"); ?>
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Hello, <?= htmlspecialchars($emp_name) ?> 👋</h1>

        <div class="bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-2xl mb-4 font-semibold">📬 Phishing Campaigns Targeting You</h2>

            <?php if (empty($matchedCampaigns)): ?>
                <p class="text-gray-400">You haven't been part of any phishing campaigns.</p>
            <?php else: ?>
                <table class="table-auto w-full text-left text-gray-300 mt-4">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="p-2">Campaign</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matchedCampaigns as $entry): ?>
                            <tr class="bg-gray-800 border-b border-gray-700">
                                <td class="p-2"><?= htmlspecialchars($entry['campaign']) ?></td>
                                <td class="p-2"><?= htmlspecialchars(ucfirst($entry['status'])) ?></td>
                                <td class="p-2"><?= date("Y-m-d H:i:s", strtotime($entry['timestamp'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
