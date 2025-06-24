<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php
$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$apiHost = "https://3.93.236.247:3636/api";
$staticPhishURL = "https://3.93.236.247/";

// Helper to fetch dropdown data
function fetchApiList($endpoint, $apiKey) {
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Fetch dropdown data
$groups = fetchApiList("$apiHost/groups/", $apiKey);
$pages = fetchApiList("$apiHost/pages/", $apiKey);
$templates = fetchApiList("$apiHost/templates/", $apiKey);
$smtpProfiles = fetchApiList("$apiHost/smtp/", $apiKey);

// Initialize response message
$responseMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $url = "$apiHost/campaigns/";

    // Match names from IDs
    $templateName = "";
    $pageName = "";
    $smtpName = "";
    $groupName = "";

    foreach ($templates as $template) {
        if ($template['id'] == $_POST['template_id']) {
            $templateName = $template['name'];
            break;
        }
    }

    foreach ($pages as $page) {
        if ($page['id'] == $_POST['page_id']) {
            $pageName = $page['name'];
            break;
        }
    }

    foreach ($smtpProfiles as $smtp) {
        if ($smtp['id'] == $_POST['smtp_id']) {
            $smtpName = $smtp['name'];
            break;
        }
    }

    foreach ($groups as $group) {
        if ($group['id'] == $_POST['group_id']) {
            $groupName = $group['name'];
            break;
        }
    }

    // Construct payload
    $data = [
        "name"         => $_POST['campaign_name'],
        "launch_date"  => date("c", strtotime($_POST['launch_date'])),
        "url"          => $staticPhishURL,
        "template"     => ["name" => $templateName],
        "page"         => ["name" => $pageName],
        "smtp"         => ["name" => $smtpName],
        "groups"       => [["name" => $groupName]]
    ];

    // Show payload for debugging
    echo "<pre style='color:blue'><strong>Payload:</strong>\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>";

    $payload = json_encode($data, JSON_UNESCAPED_SLASHES);

    $ch = curl_init($url);
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
    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decodedResponse = json_decode($apiResponse, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        if (isset($decodedResponse['id'])) {
            $responseMessage = "<div class='bg-green-100 text-green-800 p-4 rounded shadow'>✅ <strong>Campaign '{$decodedResponse['name']}' launched successfully (ID: {$decodedResponse['id']}).</strong></div>";
        } else {
            $errorMsg = $decodedResponse['message'] ?? 'Unknown error';
            $responseMessage = "<div class='bg-red-100 text-red-800 p-4 rounded shadow'>❌ <strong>Error:</strong> {$errorMsg}</div>";
        }
    } else {
        $responseMessage = "<div class='bg-red-100 text-red-800 p-4 rounded shadow'>❌ <strong>Unexpected response:</strong><br><code>$apiResponse</code></div>";
    }
}
?>

<!-- Response Message -->
<div class="max-w-2xl mx-auto mt-6">
    <?= $responseMessage ?>
</div>

<!-- Campaign Form -->
<form method="POST" class="p-6 bg-white rounded shadow-lg max-w-2xl mx-auto mt-6 space-y-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">🚀 Create New Campaign</h2>

    <label class="block font-medium">Campaign Name:</label>
    <input type="text" name="campaign_name" required class="w-full p-2 border rounded">

    <label class="block font-medium">Launch Date:</label>
    <input type="datetime-local" name="launch_date" required class="w-full p-2 border rounded">

    <label class="block font-medium">Email Template:</label>
    <select name="template_id" required class="w-full p-2 border rounded">
        <?php foreach ($templates as $template): ?>
            <option value="<?= htmlspecialchars($template['id']) ?>"><?= htmlspecialchars($template['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Landing Page:</label>
    <select name="page_id" required class="w-full p-2 border rounded">
        <?php foreach ($pages as $page): ?>
            <option value="<?= htmlspecialchars($page['id']) ?>"><?= htmlspecialchars($page['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Sending Profile:</label>
    <select name="smtp_id" required class="w-full p-2 border rounded">
        <?php foreach ($smtpProfiles as $smtp): ?>
            <option value="<?= htmlspecialchars($smtp['id']) ?>"><?= htmlspecialchars($smtp['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Target Group:</label>
    <select name="group_id" required class="w-full p-2 border rounded">
        <?php foreach ($groups as $group): ?>
            <option value="<?= htmlspecialchars($group['id']) ?>"><?= htmlspecialchars($group['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <p class="text-sm text-gray-500 italic">Phishing URL is fixed to: <strong><?= htmlspecialchars($staticPhishURL) ?></strong></p>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Launch Campaign</button>
</form>
