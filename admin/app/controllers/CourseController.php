<?php
class CourseController extends Controller {

       public function create() {
        $error = null;

        // Fetch departments for the form dropdown
        $model = $this->model('Course');
        $departments = $model->getAllDepartments();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseName = $_POST['course_name'] ?? '';
            $videoLinks = $_POST['video_links'] ?? [];
            $quizLinks = $_POST['quiz_links'] ?? [];
            $departmentIds = $_POST['department_ids'] ?? [];  // array of selected department IDs

            // Handle PDF upload
            $pdfFileName = null;
            if (isset($_FILES['pdf_material']) && $_FILES['pdf_material']['error'] == 0) {
                $targetDir = __DIR__ . '/../../public/uploads/course_materials/';
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

                $pdfFileName = time() . '_' . basename($_FILES['pdf_material']['name']);
                move_uploaded_file($_FILES['pdf_material']['tmp_name'], $targetDir . $pdfFileName);
            }

            // Validate required fields
            if (!empty($courseName) && !empty($departmentIds)) {
                $model = $this->model('Course');
                $model->create([
                    'course_name' => $courseName,
                    'pdf_material' => $pdfFileName,
                    'video_links' => json_encode(array_filter($videoLinks)),
                    'quiz_links' => json_encode(array_filter($quizLinks)),
                    'department_ids' => $departmentIds
                ]);
                header("Location: /New_Cybertron/admin/public/courses/create?success=1");
                exit;
            } else {
                $error = "Course name and departments are required.";
            }
        }

        // Render the view and pass the data
        $this->view('course/create', [
            'error' => $error ?? null,
            'success' => $_GET['success'] ?? null,
            'departments' => $departments
        ]);
    }

    public function viewAll() {
        $model = $this->model('Course');
        $courses = $model->getAllCourses();

        $this->view('course/view', ['courses' => $courses]);
    }

    // Inside CourseController.php or a helper file
public function getVideoEmbedUrl($url) {
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        // Extract YouTube video ID from the URL and return embed link
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        if ($matches) {
            return "https://www.youtube.com/embed/" . $matches[1];
        }
    }
    return null; // Return null if no valid embed URL
}

}
