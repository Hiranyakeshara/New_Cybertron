<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cybertron Quiz Platform | Employee Login</title>
  <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #0f172a;
      font-family: 'Segoe UI', sans-serif;
      color: #e2e8f0;
    }

    .input-box {
      background-color: #1e293b;
      border: 1px solid #334155;
      color: #e2e8f0;
    }

    .input-box::placeholder {
      color: #94a3b8;
    }

    .input-box:focus {
      border-color: #10b981;
      outline: none;
      box-shadow: 0 0 0 1px #10b981;
    }

    .section-title {
      color: #22d3ee;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-7xl w-full">

    <!-- Left Panel -->
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
      <h2 class="text-3xl font-bold section-title mb-4">🚀 Welcome to Cybertron Security Quiz Platform</h2>
      <p class="mb-4 text-gray-300 text-sm">Cybersecurity is not just IT's job—it's everyone's responsibility. Here's why we built this platform:</p>
      <ul class="list-disc pl-6 text-sm text-gray-300 space-y-2">
        <li><strong>Train Employees:</strong> Role-based awareness training</li>
        <li><strong>Prevent Attacks:</strong> Detect phishing and malicious behavior early</li>
        <li><strong>Stay Compliant:</strong> Meet data protection regulations</li>
        <li><strong>Build Culture:</strong> Foster a proactive security mindset</li>
      </ul>
      <hr class="my-4 border-gray-700"/>
      <p class="text-xs text-gray-500">Empower your people to be your first line of defense with Cybertron.</p>
    </div>

    <!-- Login Panel -->
    <div class="bg-gray-900 p-10 rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Sign In to Employee Dashboard</h2>
      <form method="post" id="examineeLoginFrm" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
          <input type="email" name="username" required placeholder="Enter your email"
                 class="input-box w-full px-4 py-2 rounded-md" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
          <input type="password" name="pass" required placeholder="Enter your password"
                 class="input-box w-full px-4 py-2 rounded-md" />
        </div>
        <div class="flex justify-end">
          <button type="submit"
                  class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-semibold">
            Login
          </button>
        </div>
      </form>
    </div>

  </div>

</body>
</html>
