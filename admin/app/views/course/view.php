<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<!-- Alpine.js for modal control -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
                    <div x-data="{ openModal: false }" class="bg-white border border-gray-300 rounded-lg shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition-all duration-300">
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

                            <!-- Video Links -->
                            <div class="flex flex-col justify-between">
                                <span class="font-medium text-gray-700">Video Links:</span>
                                <div class="space-y-2">
                                    <?php
                                    $videos = json_decode($course['video_links'], true) ?: [];
                                    foreach ($videos as $video):
                                        if (filter_var($video, FILTER_VALIDATE_URL)):
                                            $videoEmbedUrl = $this->getVideoEmbedUrl($video);
                                            if ($videoEmbedUrl):
                                                echo '<div class="aspect-w-16 aspect-h-9">
                                                        <iframe class="w-full h-full" src="' . $videoEmbedUrl . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                      </div>';
                                            else:
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

                            <!-- Departments -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Departments:</span>
                                <div class="space-y-1">
                                    <?php
                                    $departments = explode(',', $course['departments']);
                                    foreach ($departments as $department) {
                                        echo '<br><span class="text-gray-600">' . htmlspecialchars($department) . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2 mt-4">
                            <a href="/New_Cybertron/admin/public/course/delete/<?= $course['id'] ?>" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-sm transition-all duration-300" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>

                            <button @click="openModal = true" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">Edit</button>

                            <a href="/New_Cybertron/admin/app/controllers/pushToQuiz.php?id=<?= $course['id'] ?>" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md text-sm transition-all duration-300">Push to Quiz Platform</a>
                        </div>

                        <!-- Edit Modal -->
                        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div @click.away="openModal = false" class="bg-white rounded-lg p-6 w-full max-w-lg space-y-4">
                                <h2 class="text-xl font-bold">✏️ Edit Course</h2>
                                <form method="POST" action="/New_Cybertron/admin/public/course/update" enctype="multipart/form-data" class="space-y-4">
                                    <input type="hidden" name="id" value="<?= $course['id'] ?>">

                                    <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" placeholder="Course Name" class="w-full border p-2 rounded" required>

                                    <label class="block text-sm">PDF Material (Upload New)</label>
                                    <input type="file" name="pdf_material" class="w-full border p-2 rounded">

                                    <textarea name="video_links" rows="2" placeholder="JSON Array of Videos" class="w-full border p-2 rounded"><?= htmlspecialchars($course['video_links']) ?></textarea>

                                    <textarea name="quiz_links" rows="2" placeholder="JSON Array of Quizzes" class="w-full border p-2 rounded"><?= htmlspecialchars($course['quiz_links']) ?></textarea>

                                    <div class="flex justify-end space-x-3">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
                                        <button type="button" @click="openModal = false" class="px-4 py-2 border rounded">Cancel</button>
                                    </div>
                                </form>
                            </div>
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
