<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php
$apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
$apiHost = "https://3.93.236.247:3636/api";

// Fetch helpers
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

$groups = fetchApiList("$apiHost/groups/", $apiKey);
$pages = fetchApiList("$apiHost/pages/", $apiKey);
$templates = fetchApiList("$apiHost/templates/", $apiKey);
$smtpProfiles = fetchApiList("$apiHost/smtp/", $apiKey);

// Handle form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $url = "$apiHost/campaigns/";
    $data = [
        "name" => $_POST['campaign_name'],
        "template" => ["name" => $_POST['template_name']],
        "page" => ["name" => $_POST['page_name']],
        "smtp" => ["name" => $_POST['smtp_name']],
        "url" => $_POST['phish_url'],
        "groups" => [["name" => $_POST['group_name']]],
        "launch_date" => $_POST['launch_date']
    ];
    $payload = json_encode($data);

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
    $response = curl_exec($ch);
    curl_close($ch);
    echo "<pre>Campaign Creation Response:\n" . htmlspecialchars($response) . "</pre>";
}
?>

<form method="POST" class="p-6 bg-white rounded shadow-lg max-w-2xl mx-auto mt-10 space-y-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">🚀 Create New Campaign</h2>

    <label class="block font-medium">Campaign Name:</label>
    <input type="text" name="campaign_name" required class="w-full p-2 border rounded">

    <label class="block font-medium">Phishing URL (redirect):</label>
    <input type="text" name="phish_url" required class="w-full p-2 border rounded" placeholder="http://your-phish-url.com">

    <label class="block font-medium">Launch Date (ISO Format):</label>
    <input type="datetime-local" name="launch_date" required class="w-full p-2 border rounded">

    <label class="block font-medium">Email Template:</label>
    <select name="template_name" required class="w-full p-2 border rounded">
        <?php foreach ($templates as $template): ?>
            <option value="<?= htmlspecialchars($template['name']) ?>"><?= htmlspecialchars($template['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Landing Page:</label>
    <select name="page_name" required class="w-full p-2 border rounded">
        <?php foreach ($pages as $page): ?>
            <option value="<?= htmlspecialchars($page['name']) ?>"><?= htmlspecialchars($page['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Sending Profile:</label>
    <select name="smtp_name" required class="w-full p-2 border rounded">
        <?php foreach ($smtpProfiles as $smtp): ?>
            <option value="<?= htmlspecialchars($smtp['name']) ?>"><?= htmlspecialchars($smtp['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="block font-medium">Target Group:</label>
    <select name="group_name" required class="w-full p-2 border rounded">
        <?php foreach ($groups as $group): ?>
            <option value="<?= htmlspecialchars($group['name']) ?>"><?= htmlspecialchars($group['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Launch Campaign</button>
</form>
