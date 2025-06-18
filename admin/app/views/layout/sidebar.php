<script src="https://unpkg.com/alpinejs" defer></script>
<div class="flex min-h-screen">

  <!-- Sidebar -->
  <div class="w-64 bg-gradient-to-b from-gray-900 to-gray-700 text-white p-4 shadow-md flex-shrink-0">
    <nav class="space-y-4">
      <!-- Manage Departments -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
          🏢 Manage Departments
        </button>
        <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
          <a href="/New_Cybertron/admin/public/department/viewDepartments" class="block py-2 px-4 rounded hover:bg-gray-600">📁 View Departments</a>
          <a href="/New_Cybertron/admin/public/department/create" class="block py-2 px-4 rounded hover:bg-gray-600">➕ Create Department</a>
        </div>
      </div>

      <!-- Manage Employees -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
          👥 Manage Employees
        </button>
        <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
          <a href="/New_Cybertron/admin/public/employee/create" class="block py-2 px-4 rounded hover:bg-gray-600">👤 Add Employee</a>
          <a href="/New_Cybertron/admin/public/employee/viewAll" class="block py-2 px-4 rounded hover:bg-gray-600">📄 View Employees</a>
          <a href="/New_Cybertron/admin/public/performance" class="block py-2 px-4 rounded hover:bg-gray-600">📊 View Performance</a>
        </div>
      </div>

      <!-- Courses & Training -->
      <div x-data="{ openCourses: false }">
        <button @click="openCourses = !openCourses" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
          📚 Courses & Training
        </button>
        <div x-show="openCourses" class="pl-4 mt-1 space-y-1" x-transition>
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
         
        </div>
      </div>

      <!-- User Feedback -->
      <a href="/New_Cybertron/admin/public/feedback/" class="block py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">🗣️ User Feedback</a>

      <!-- Settings -->
      <a href="/New_Cybertron/admin/public/settings" class="block py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">⚙️ Settings</a>

      <!-- New Quiz Making Portal -->
      <div x-data="{ openQuiz: false }">
        <button @click="openQuiz = !openQuiz" class="w-full text-left py-2 px-4 bg-gray-800 rounded-lg hover:bg-gray-600">
          📝 Quiz Making Portal
        </button>
        <div x-show="openQuiz" class="pl-4 mt-1 space-y-1" x-transition>
          <a href="/New_Cybertron/CEE/adminpanel/" class="block py-2 px-4 rounded hover:bg-gray-600">➕ Access QuizPlatform</a>
          <a href="/New_Cybertron/admin/public/quiz/" class="block py-2 px-4 rounded hover:bg-gray-600">📘 View Quiz Results</a>
        </div>
      </div>
    </nav>
  </div>

