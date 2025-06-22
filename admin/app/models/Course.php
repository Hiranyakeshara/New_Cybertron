<?php
require_once '../core/Database.php';

class Course extends Database {

    // Method to fetch all courses
public function getAllCourses() {
    // Get all courses with associated departments
    $stmt = $this->dbh->prepare("
        SELECT courses.id, courses.course_name, courses.pdf_material, courses.video_links, courses.quiz_links,
               GROUP_CONCAT(departments.department_name) AS departments
        FROM courses
        LEFT JOIN course_departments ON courses.id = course_departments.course_id
        LEFT JOIN departments ON course_departments.department_id = departments.id
        GROUP BY courses.id
        ORDER BY courses.id DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Method to create a new course
    public function create($data) {
        $stmt = $this->dbh->prepare("
            INSERT INTO courses (course_name, pdf_material, video_links, quiz_links)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['course_name'],
            $data['pdf_material'],
            $data['video_links'],
            $data['quiz_links']
        ]);

        // Get the last inserted course ID
        $courseId = $this->dbh->lastInsertId();

        // Insert relationships into course_departments table
        if (!empty($data['department_ids'])) {
            foreach ($data['department_ids'] as $departmentId) {
                $stmt = $this->dbh->prepare("INSERT INTO course_departments (course_id, department_id) VALUES (?, ?)");
                $stmt->execute([$courseId, $departmentId]);
            }
        }
    }

    //delete course from the system
    public function delete($id) {
$stmt = $this->dbh->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$id]);
}

public function getById($id) {
    $stmt = $this->dbh->prepare("SELECT * FROM courses WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//update course details
public function update($data) {
    $stmt = $this->dbh->prepare("UPDATE courses SET course_name = ?, pdf_material = ?, video_links = ?, quiz_links = ? WHERE id = ?");
    $stmt->execute([
        $data['course_name'],
        $data['pdf_material'],
        $data['video_links'],
        $data['quiz_links'],
        $data['id']
    ]);
}




    // Fetch all departments for the dropdown
    public function getAllDepartments() {
        $stmt = $this->dbh->query("SELECT id, department_name FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
