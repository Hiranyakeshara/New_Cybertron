<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-7xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📚 All Courses</h2>

        <!-- Search Bar -->
        <form method="GET" action="" class="mb-6">
            <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search courses..." class="w-full p-3 border border-gray-300 rounded-md">
        </form>

        <?php if (!empty($data['courses'])): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($data['courses'] as $index => $course): ?>
                    <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition-all duration-300">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4"><?= htmlspecialchars($course['course_name']) ?></h3>

                        <div class="space-y-4 flex-grow">
                            <!-- PDF Material -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">PDF Material:</span>
                                <?php if (!empty($course['pdf_material'])): ?>
                                    <a href="/New_Cybertron/admin/public/uploads/course_materials/<?= htmlspecialchars($course['pdf_material']) ?>" target="_blank" class="text-indigo-600 hover:underline text-sm">Download PDF</a>
                                <?php else: ?>
                                    <span class="text-gray-500">No PDF</span>
                                <?php endif; ?>
                            </div>

                            <!-- Video Links (Display as playable videos) -->
                            <div class="flex flex-col justify-between">
                                <span class="font-medium text-gray-700">Video Links:</span>
                                <div class="space-y-2">
                                    <?php
                                    $videos = json_decode($course['video_links'], true) ?: [];
                                    foreach ($videos as $video):
                                        if (filter_var($video, FILTER_VALIDATE_URL)):  // Check if valid URL
                                            // You can use an iframe for YouTube or other video platforms
                                            $videoEmbedUrl = $this->getVideoEmbedUrl($video);
                                            if ($videoEmbedUrl): // If the video URL can be embedded
                                                echo '<div class="aspect-w-16 aspect-h-9">
                                                        <iframe class="w-full h-full" src="' . $videoEmbedUrl . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                      </div>';
                                            else: // If not embeddable, provide a simple link
                                                echo '<a href="' . htmlspecialchars($video) . '" target="_blank" class="text-blue-600 hover:underline">' . htmlspecialchars($video) . '</a>';
                                            endif;
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>

                            <!-- Quiz Links -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Quiz Links:</span>
                                <div class="space-y-1">
                                    <?php
                                    $quizzes = json_decode($course['quiz_links'], true) ?: [];
                                    foreach ($quizzes as $quiz) {
                                        echo '<a href="' . htmlspecialchars($quiz) . '" target="_blank" class="block text-green-600 hover:underline text-sm">' . htmlspecialchars($quiz) . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Departments associated with the course -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Departments:</span>
                                <div class="space-y-1">
                                  
                                    <?php
                                    // Display departments associated with the course
                                    $departments = explode(',', $course['departments']); // Split by comma
                                    foreach ($departments as $department) {
                                           echo '<br>';
                                        echo '<span class="text-gray-600">' . htmlspecialchars($department) . '</span>' ;  
                                        
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                      
                      <!-- Action Buttons -->
<div class="flex flex-wrap gap-2 mt-4">
    <a href="/cyber-training-platform/public/courses/view/<?= $course['id'] ?>"
       class="inline-block text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md text-sm transition-all duration-300">
       View Course
    </a>
    <a href="/cyber-training-platform/public/courses/edit/<?= $course['id'] ?>"
       class="inline-block text-white bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-md text-sm transition-all duration-300">
       Edit
    </a>
 <a href="/New_Cybertron/admin/app/controllers/pushToQuiz.php?id=<?= $course['id'] ?>"
   class="inline-block text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md text-sm transition-all duration-300">
   Push to Quiz Platform
</a>


</div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 py-6">No courses found.</p>
        <?php endif; ?>
    </div>
</div>

</div>
</body>
</html>
