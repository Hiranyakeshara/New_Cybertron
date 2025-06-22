<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .settings-container {
        max-width: 1080px;
        margin: 50px auto;
        background: #ffffff;
        padding: 40px 50px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .settings-container h2 {
        text-align: center;
        font-size: 32px;
        color: #2c3e50;
        margin-bottom: 40px;
    }

    .info-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .info-table thead th {
        background-color: #212529;
        color: #ffffff;
        text-align: center;
        padding: 12px 18px;
        border-radius: 6px 6px 0 0;
        font-size: 16px;
    }

    .info-table tbody tr {
        background-color: #f8f9fa;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        border-radius: 6px;
    }

    .info-table td {
        padding: 16px 20px;
        vertical-align: middle;
        font-size: 15px;
        color: #333;
    }

    .info-table td:first-child {
        font-weight: bold;
        width: 35%;
        color: #0d6efd;
    }

    .highlight-box {
        background-color: #eaf6ff;
        padding: 20px 25px;
        border-left: 5px solid #0d6efd;
        border-radius: 6px;
        margin: 40px 0;
    }

    .highlight-box h4 {
        color: #0b3058;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .default-credentials {
        margin: auto;
        width: 80%;
    }

    .default-credentials th {
        background-color: #e0e0e0;
        text-align: center;
    }

    .default-credentials td {
        text-align: center;
    }

    .note {
        font-size: 13px;
        text-align: center;
        margin-top: 10px;
        color: #666;
    }

    ul {
        padding-left: 20px;
    }

    ul li {
        margin-bottom: 8px;
    }
</style>

<div class="settings-container">
    <h2>Cybertrone Platform System Overview</h2>

    <table class="info-table">
        <thead>
            <tr>
                <th>🧾 Property</th>
                <th>📋 Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($platformInfo as $key => $value): ?>
            <tr>
                <td><?= htmlspecialchars($key) ?></td>
                <td><?= htmlspecialchars($value) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="highlight-box">
        <h4>🌐 What is Cybertrone?</h4>
        <p>
            Cybertrone is a centralized, modular cybersecurity administration platform that helps organizations efficiently conduct awareness training, monitor employee participation, and simulate phishing threats.
        </p>
    </div>

    <div class="highlight-box">
        <h4>🎯 Features & Capabilities</h4>
        <ul>
            <li>Launch phishing simulations using <strong>GoPhish</strong></li>
            <li>Assign training modules and quizzes to employees</li>
            <li>Generate department-wise performance dashboards</li>
            <li>Collect and review real-time feedback and logs</li>
        </ul>
    </div>

    <div class="highlight-box">
        <h4>🛠 Supported Tools & Integrations</h4>
        <ul>
            <li><strong>GoPhish</strong> — Simulated email phishing attacks</li>
            <li><strong>Burp Suite</strong> — Security vulnerability scanner</li>
            <li><strong>Cybertrone REST API</strong> — For external integrations</li>
            <li><strong>JWT Auth</strong> — Secure token-based login</li>
        </ul>
    </div>

    <div class="highlight-box">
        <h4>🔐 Default Login Credentials</h4>
        <table class="table table-bordered default-credentials">
            <thead>
                <tr>
                    <th>User Role</th>
                    <th>Username</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Admin</td>
                    <td>admin</td>
                    <td>admin</td>
                </tr>
                <tr>
                    <td>Employee</td>
                    <td>admin1234</td>
                    <td>admin1234</td>
                </tr>
            </tbody>
        </table>
        <p class="note">⚠️ For security reasons, please change default credentials after first login.</p>
    </div>

    <div class="highlight-box">
        <h4>🔑 API Key & Security Tokens</h4>
        <ul>
            <li>Each user is issued a unique API key at registration</li>
            <li>API keys can be refreshed from the Developer Panel</li>
            <li>Tokens auto-expire after 30 days for enhanced security</li>
        </ul>
    </div>

    <div class="highlight-box">
        <h4>📌 Recommendations & Notes</h4>
        <ul>
            <li>Cybertrone works best on Chrome, Firefox, or Edge browsers</li>
            <li>Ensure VPN or corporate firewall integration for production use</li>
            <li>Audit logs are securely stored and accessible to super admins</li>
        </ul>
    </div>
</div>
