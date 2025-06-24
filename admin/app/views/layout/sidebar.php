<!-- Sidebar Toggle Script -->
<script src="https://unpkg.com/alpinejs" defer></script>

<!-- Root Layout -->
<div x-data="{ sidebarOpen: true }" class="flex min-h-screen">

  <!-- Sidebar -->
  <div
    :class="sidebarOpen ? 'w-64' : 'w-0'"
    class="bg-gradient-to-b from-gray-900 to-gray-700 text-white shadow-md transition-all duration-300 overflow-hidden flex-shrink-0"
  >
    <div class="p-4">
   

      <nav class="space-y-4">
        <!-- Departments -->
        <div x-data="{ open: false }">
          <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
            🏢 Manage Departments
          </button>
          <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
            <a href="/New_Cybertron/admin/public/department/viewDepartments" class="block py-2 px-4 rounded hover:bg-gray-600">📁 View Departments</a>
            <a href="/New_Cybertron/admin/public/department/create" class="block py-2 px-4 rounded hover:bg-gray-600">➕ Create Department</a>
          </div>
        </div>

        <!-- Employees -->
        <div x-data="{ open: false }">
          <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
            👥 Manage Employees
          </button>
          <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
            <a href="/New_Cybertron/admin/public/employee/create" class="block py-2 px-4 rounded hover:bg-gray-600">👤 Add Employee</a>
            <a href="/New_Cybertron/admin/public/employee/viewAll" class="block py-2 px-4 rounded hover:bg-gray-600">📄 View Employees</a>
            <a href="/New_Cybertron/admin/public/employee/performance" class="block py-2 px-4 rounded hover:bg-gray-600">📊 View Performance</a>
          </div>
        </div>

        <!-- Courses -->
        <div x-data="{ open: false }">
          <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
            📚 Courses & Training
          </button>
          <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
            <a href="/New_Cybertron/admin/public/course/create" class="block py-2 px-4 rounded hover:bg-gray-600">➕ Create Course</a>
            <a href="/New_Cybertron/admin/public/course/viewAll" class="block py-2 px-4 rounded hover:bg-gray-600">📘 View Courses</a>
          </div>
        </div>

        <!-- Email Campaigns -->
        <div x-data="{ open: false }">
          <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
            📢 Email Campaigns
          </button>
          <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
            <a href="/New_Cybertron/admin/public/campaigns/" class="block py-2 px-4 rounded hover:bg-gray-600">📈 View Results</a>
            <a href="/New_Cybertron/admin/public/campaigns/create" class="block py-2 px-4 rounded hover:bg-gray-600"> 🎯 Create Campaigns</a>
            <a href="/New_Cybertron/admin/public/campaigns/user" class="block py-2 px-4 rounded hover:bg-gray-600">👥 Create User Groups</a>
          </div>
        </div>

        <!-- Quiz Portal -->
        <div x-data="{ open: false }">
          <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
            📝 Quiz Making Portal
          </button>
          <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
            <a href="/New_Cybertron/CEE/adminpanel/" class="block py-2 px-4 rounded hover:bg-gray-600">➕ Access Quiz Platform</a>
            <a href="/New_Cybertron/admin/public/quiz/" class="block py-2 px-4 rounded hover:bg-gray-600">📘 View Quiz Results</a>
          </div>
        </div>

        <!-- Feedback -->
        <a href="/New_Cybertron/admin/public/feedback/" class="block py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">🗣️ User Feedback</a>

        <!-- Settings -->
        <a href="/New_Cybertron/admin/public/settings" class="block py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">⚙️ Software Version</a>
      </nav>
    </div>
  </div>

  <!-- Toggle Button -->
  <button
    @click="sidebarOpen = !sidebarOpen"
    class="absolute top-5 left-4 z-50 bg-gray-900 text-white p-2 rounded-full hover:bg-gray-700 transition"
    title="Toggle Sidebar"
  >
    <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
    <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </button>
