<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $apiKey = "b20973835c57000dfbe82a33bb93bb2e122ef21acb9771736e4aa0630795052a";
    $url = "https://3.93.236.247:3636/api/groups/";

    $groupName = $_POST['group_name'];
    $targets = json_decode($_POST['targets'], true); // Must be an array of target objects

    $payload = json_encode([
        "name" => $groupName,
        "targets" => $targets
    ]);

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
    echo "<pre>Group Creation Response:\n" . htmlspecialchars($response) . "</pre>";
}
?>

<form method="POST" class="p-6 bg-white rounded shadow-lg max-w-2xl mx-auto mt-10 space-y-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">➕ Create New User Group</h2>
    <label class="block font-medium">Group Name:</label>
    <input type="text" name="group_name" required class="w-full p-2 border rounded">

    <label class="block font-medium">Targets (JSON array):</label>
    <textarea name="targets" rows="6" required class="w-full p-2 border rounded placeholder:text-sm placeholder:text-gray-500" placeholder='[{"email":"john@example.com","first_name":"John","last_name":"Doe","position":""}]'></textarea>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Group</button>
</form>
