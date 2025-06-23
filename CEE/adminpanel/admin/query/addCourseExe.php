<?php
include("../../../conn.php");

extract($_POST);

$cou_id = intval($course_id);  // Sanitize the ID input
$cou_name = strtoupper(trim($course_name)); // Normalize input

// Check if the course name already exists
$selCourse = $conn->query("SELECT * FROM course_tbl WHERE cou_name = '$cou_name' ");

if ($selCourse->rowCount() > 0) {
    $res = array("res" => "exist", "course_name" => $cou_name);
} else {
    // Insert new course with ID and name
    $insCourse = $conn->query("INSERT INTO course_tbl (cou_id, cou_name) VALUES ('$cou_id', '$cou_name')");

    if ($insCourse) {
        $res = array("res" => "success", "course_name" => $cou_name);
    } else {
        $res = array("res" => "failed", "course_name" => $cou_name);
    }
}

echo json_encode($res);
?>
