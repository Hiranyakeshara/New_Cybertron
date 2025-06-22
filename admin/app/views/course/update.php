<?php
session_start();  // If not already started
require_once __DIR__ . '/../../../app/config/database.php';  // Adjust path to DB config

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = intval($_POST['id']);
    $course_name = trim($_POST['course_name']);
    $video_links = json_encode(json_decode($_POST['video_links'], true));  // Validate JSON
    $quiz_links = json_encode(json_decode($_POST['quiz_links'], true));    // Validate JSON

    // File upload handling
    $pdf_name = null;
    if (!empty($_FILES['pdf_material']['name'])) {
        $uploadDir = __DIR__ . '/../../../public/uploads/course_materials/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

        $pdf_name = time() . '_' . basename($_FILES['pdf_material']['name']);
        $targetFile = $uploadDir . $pdf_name;

        if (!move_uploaded_file($_FILES['pdf_material']['tmp_name'], $targetFile)) {
            die("PDF upload failed.");
        }
    }

    try {
        $sql = "UPDATE courses SET course_name = :course_name, video_links = :video_links, quiz_links = :quiz_links";
        if ($pdf_name) {
            $sql .= ", pdf_material = :pdf_material";
        }
        $sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':course_name' => $course_name,
            ':video_links' => $video_links,
            ':quiz_links' => $quiz_links,
            ':id' => $id
        ];
        if ($pdf_name) {
            $params[':pdf_material'] = $pdf_name;
        }

        $stmt->execute($params);

        // Redirect back to course view with success
        header('Location: /New_Cybertron/admin/public/course/view.php?updated=true');
        exit;
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
} else {
    // Invalid access
    header('HTTP/1.1 403 Forbidden');
    echo "Access Denied";
}
?>
