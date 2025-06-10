<?php include_once __DIR__ . '/../layout/header.php'; ?>
<?php include_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="flex-1 p-10 bg-gray-50">
    <div class="bg-white p-6 rounded shadow max-w-3xl">
        <h2 class="text-2xl font-bold mb-4">➕ Create Course</h2>

        <?php if (!empty($data['success'])): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">Course created successfully!</div>
        <?php elseif (!empty($data['error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-medium text-gray-700 mb-1">Course Name</label>
                <input type="text" name="course_name" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Course Material (PDF)</label>
                <input type="file" name="pdf_material" accept="application/pdf" class="w-full" />
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Video Links</label>
                <div id="video-links-wrapper" class="space-y-2">
                    <input type="url" name="video_links[]" placeholder="https://example.com/video1" class="w-full p-2 border rounded" />
                </div>
                <button type="button" id="add-video-link" class="mt-2 text-indigo-600 hover:underline">+ Add another video link</button>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Quiz Links</label>
                <div id="quiz-links-wrapper" class="space-y-2">
                    <input type="url" name="quiz_links[]" placeholder="https://example.com/quiz1" class="w-full p-2 border rounded" />
                </div>
                <button type="button" id="add-quiz-link" class="mt-2 text-indigo-600 hover:underline">+ Add another quiz link</button>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Departments</label>
                <select name="department_ids[]" multiple class="w-full p-2 border rounded">
                    <?php foreach ($data['departments'] as $department): ?>
                        <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['department_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Create Course</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-video-link').addEventListener('click', function() {
        const wrapper = document.getElementById('video-links-wrapper');
        const input = document.createElement('input');
        input.type = 'url';
        input.name = 'video_links[]';
        input.placeholder = 'https://example.com/videoX';
        input.className = 'w-full p-2 border rounded';
        wrapper.appendChild(input);
    });

    document.getElementById('add-quiz-link').addEventListener('click', function() {
        const wrapper = document.getElementById('quiz-links-wrapper');
        const input = document.createElement('input');
        input.type = 'url';
        input.name = 'quiz_links[]';
        input.placeholder = 'https://example.com/quizX';
        input.className = 'w-full p-2 border rounded';
        wrapper.appendChild(input);
    });
</script>

</div>
</body>
</html>
