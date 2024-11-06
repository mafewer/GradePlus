<?php

require '../config.php';
header('Content-Type: application/json');
// Service to recieve all assignment submissions for a course

// Verify parameters
if (empty($_POST["authorize"]) || empty($_POST["course_code"])) {
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

// Retrieve submissions
$get_submissions_sql = sprintf("
SELECT assignment_id, assignment_name, submitted_pdf, username, submitted_flag
FROM grades
WHERE course_code = '%s' and submitted_flag = '1'
ORDER BY assignment_id ASC", $course_code);

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
    "assignment_name" => $row['assignment_name'],
    "submitted_pdf" => $row['submitted_pdf'],
    "username" => $row['username'],
    "submitted_flag" => $row['submitted_flag']
    ];
}

$conn->close();
echo json_encode(["success" => $success,"error" => $error,"data" => $submissions]);
