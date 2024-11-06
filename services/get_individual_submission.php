<?php

require '../config.php';
header('Content-Type: application/json');
// Service to recieve all assignment submissions for a course

// Verify parameters
if (empty($_POST["authorize"]) || empty($_POST["course_code"]) || empty($_POST["assignment_id"]) || empty($_POST["student_name"])) {
    echo json_encode(["success" => 0, "error" => 1, "data" => [], "message" => "Missing or empty parameters."]);
    exit();
}

// Verify authorization
if ($_POST["authorize"] != "gradeplus") {
    header("Location: illegal.php");
    exit();
}

// Establish database connection
$conn = mysqli_connect($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");
if (!$conn) {
    echo json_encode(["success" => 0, "error" => 1, "message" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

$course_code = $_POST["course_code"];
$student_name = $_POST["student_name"];
$assignment_id = $_POST["assignment_id"];

// Retrieve submissions
$get_submissions_sql = sprintf("
SELECT *
FROM grades
WHERE course_code = '%s' and assignment_id = '%s' and username = '%s'
ORDER BY assignment_id ASC", $course_code, $assignment_id, $student_name);

$result = mysqli_query($conn, $get_submissions_sql);
if ($result) {
    $success = 1;
    $error = 0;
} else {
    $success = 0;
    $error = 1;
}

// Fetch data and build the response
$submissions = [];
while ($row = $result->fetch_assoc()) {
    $submissions[] = [
    "assignment_id" => $row['assignment_id'],
    "course_code" => $row['course_code'],
    "assignment_name" => $row['assignment_name'],
    "username" => $row['username'],
    "grade" => $row['grade'],
    "max_grade" => $row['max_grade'],
    "feedback" => $row['feedback'],
    "submitted_pdf" => $row['submitted_pdf'],
    "submitted_flag" => $row['submitted_flag'],
    "submitted_date" => $row['submitted_date']
    ];
}

$conn->close();
echo json_encode(["success" => $success,"error" => $error,"data" => $submissions]);
