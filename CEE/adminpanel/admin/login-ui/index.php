<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cybertron | Admin Login</title>
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
  </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

  <div class="bg-gray-900 p-10 rounded-lg shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-green-400 mb-6">Admin Dashboard Login</h2>
    <form method="post" id="adminLoginFrm" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Username</label>
        <input type="text" name="username" required placeholder="Enter username"
               class="input-box w-full px-4 py-2 rounded-md" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
        <input type="password" name="pass" required placeholder="Enter password"
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

</body>
</html>
