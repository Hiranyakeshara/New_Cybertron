<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberTrone - Cyber Awareness Training Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Body background */
        body {
            background-color: #1F2937;
            font-family: 'Arial', sans-serif;
        }

        /* Header navigation styling */
        .header-nav {
            background-color: #111827;
        }

        .header-nav a {
            text-transform: uppercase;
            color: #fff;
            padding: 10px 20px;
            transition: color 0.3s ease;
        }

        .header-nav a:hover {
            color: #10B981;
        }

        /* Hero section styling */
        .hero-section {
            position: relative;
            background: url('https://img.freepik.com/free-photo/cyber-security-protection-firewall-interface-concept_53876-125636.jpg?t=st=1739475961~exp=1739479561~hmac=8ef337d77e856e0785ec04fe198a19682d91d350978e0e7ed2e276e0cc88ab87&w=1380') no-repeat center center;
            background-size: cover;
            padding: 120px 0;
            text-align: center;
            color: #fff;
            z-index: 1;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .cta-button {
            background-color: #10B981;
            color: white;
            padding: 12px 24px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 30px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #047857;
        }

        .feature-box {
            background-color: #1F2937;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .footer {
            background-color: #111827;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }

        .footer a {
            color: #10B981;
            text-decoration: none;
        }

        .flip-card {
            width: 100%;
            height: 250px;
            perspective: 1000px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        /* Front and Back of the Card */
        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
        }

        .flip-card-front {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .flip-card-back {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transform: rotateY(180deg);
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Header Section -->
<header class="header-nav sticky top-0 z-10 bg-grey shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="text-3xl font-bold">CyberTrone</a>
        <nav>
            <ul class="flex space-x-8">
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#security-standards">Security Standards</a></li>
                <li><a href="#feedback">Feedback</a></li>

                <li class="relative group">
                    <a href="#" class="cursor-pointer">Portal Access</a>
                    <ul class="absolute left-0 mt-2 w-48 bg-grey shadow-lg rounded-lg hidden group-hover:block">
                        <li><a href="company_owner.php" class="block px-4 py-2 hover:bg-gray-200">Quiz Portal</a></li>
                        <li><a href="employee_login.php" class="block px-4 py-2 hover:bg-gray-200">Cybertron Employee</a></li>
                    </ul>
                </li> 

            </ul>
        </nav>
    </div>
</header>


    <!-- Hero Section -->
    <section class="hero-section relative">
        <div class="hero-overlay absolute top-0 left-0 w-full h-full bg-black opacity-60"></div>
        <div class="relative z-10 text-center text-white">
            <h1 class="text-5xl font-extrabold mb-4">Cyber Awareness Training for Your Team</h1>
            <p class="text-lg mb-6">Equip your employees with the skills to recognize and prevent cyber threats in the workplace.</p>
            <a href="#features" class="cta-button">Get Started</a>
        </div>
    </section>

    <!-- Why Awareness Section -->
    <section id="why-awareness" class="py-20 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-bold text-white mb-6">Why Awareness is More Important to Your Company</h2>
            <p class="text-lg text-gray-400 mb-6">Cybersecurity awareness is crucial to prevent costly breaches and protect sensitive data. By investing in employee education, your company can proactively mitigate risks and foster a secure work environment.</p>
            <button id="viewVideoButton" class="cta-button">View Video</button>
        </div>
    </section>

    <!-- Modal for Video -->
    <div id="videoModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg relative">
            <button id="closeModalButton" class="absolute top-2 right-2 text-gray-700 font-bold text-xl">X</button>
            <iframe id="videoPlayer" class="w-full h-80" src="https://www.youtube.com/embed/G5t7T6krW9U?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>

    <!-- JavaScript for Modal Functionality -->
    <script>
        document.getElementById('viewVideoButton').addEventListener('click', function() {
            document.getElementById('videoModal').classList.remove('hidden');
        });

        document.getElementById('closeModalButton').addEventListener('click', function() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('videoPlayer').src = '';  // Stop the video when closed
        });
    </script>

    <!-- About Section -->
    <section id="about" class="py-20 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-bold text-white mb-6">About CyberTrone</h2>
            <p class="text-lg text-gray-400">
                CyberTrone offers a comprehensive cyber awareness training platform designed for corporate teams. Our platform helps businesses upskill employees, reduce the risk of cyber attacks, and enhance overall security awareness within organizations. Through engaging content and real-world simulations, employees learn to recognize, prevent, and report potential cyber threats.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gray-900 py-20">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl font-bold text-white mb-12">Key Features</h2>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="feature-box">
                    <h3 class="text-2xl font-bold text-white mb-4">Tailored Training Modules</h3>
                    <p class="text-gray-400">Customizable training content tailored to your organization’s specific needs and threats.</p>
                </div>
                <div class="feature-box">
                    <h3 class="text-2xl font-bold text-white mb-4">Real-World Simulations</h3>
                    <p class="text-gray-400">Interactive scenarios and simulations that mimic real-world cyber attacks.</p>
                </div>
                <div class="feature-box">
                    <h3 class="text-2xl font-bold text-white mb-4">Progress Tracking</h3>
                    <p class="text-gray-400">Track employee progress with detailed reports and dashboards to ensure the effectiveness of training.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Standards Section -->
    <section id="security-standards" class="py-20 text-center bg-gray-800">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-bold text-white mb-6">Our Commitment to Security Standards</h2>
            <p class="text-lg text-gray-400 mb-8">We adhere to the highest industry standards to ensure that your data and your team’s privacy are always protected.</p>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="https://img.freepik.com/free-photo/woman-with-gdpr-speech-bubbe-holding-padlock-icon_53876-71509.jpg?t=st=1739490943~exp=1739494543~hmac=ff8dc146e730baecbb5e6d923425db2f34a17aed488f91c57810470292257a0b&w=996" alt="Compliance" class="rounded-lg mb-4">
                            <h3 class="text-xl font-bold text-white">GDPR Compliant</h3>
                        </div>
                        <div class="flip-card-back">
                            <p class="text-white">We follow GDPR standards to protect personal data and ensure privacy for all our users.</p>
                        </div>
                    </div>
                </div>
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="https://img.freepik.com/free-vector/grunge-certified-seal-stamp-rubber-look_78370-664.jpg?t=st=1739490882~exp=1739494482~hmac=e5224e030b1504d9e45bfb49cca85e6dde172ccbcccbf84efaf1a69af8fec484&w=826" alt="ISO Certified" class="rounded-lg mb-4">
                            <h3 class="text-xl font-bold text-white">ISO Certified</h3>
                        </div>
                        <div class="flip-card-back">
                            <p class="text-white">Our platform is ISO certified for security and quality management.</p>
                        </div>
                    </div>
                </div>
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="https://img.freepik.com/free-psd/online-data-protection-banner-3d-illustration_1419-2753.jpg?t=st=1739490817~exp=1739494417~hmac=68a878c3aca8002fe6694eafccff460368c38aebb014e4c0187687d713adad3a&w=826" alt="Secure Data" class="rounded-lg mb-4">
                            <h3 class="text-xl font-bold text-white">Secure Data Handling</h3>
                        </div>
                        <div class="flip-card-back">
                            <p class="text-white">We use the highest encryption standards to ensure your data is always secure.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- Feedback Section -->
<section id="feedback" class="py-20 text-center bg-gray-900">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-4xl font-bold text-white mb-6">Give Us Your Feedback</h2>
        <p class="text-lg text-gray-400 mb-8">
            CyberTrone offers a comprehensive cyber awareness training platform designed for corporate teams. Help us improve by sharing your feedback.
        </p>

        <!-- Feedback Form -->
        <form action="submit_feedback.php" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="name" class="block text-white text-lg font-medium mb-2">Your Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-white text-lg font-medium mb-2">Your Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="message" class="block text-white text-lg font-medium mb-2">Your Feedback</label>
                <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                Submit Feedback
            </button>
        </form>
    </div>
</section>



    
    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2025 CyberTrone. All rights reserved.</p>
        <div class="mt-4">
            <a href="#" class="mx-2">Privacy Policy</a>
            <a href="#" class="mx-2">Terms of Service</a>
        </div>
    </footer>

</body>
</html>
