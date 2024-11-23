<?php

require '../config.php';
header('Content-Type: application/json');
// Service to receive all assignment submissions for a course

// Verify parameters
if (!isset($_POST["authorize"]) || !isset($_POST["invite_code"]) || !isset($_POST["assignment_id"]) || !isset($_POST["student_name"])) {
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

$invite_code = $_POST["invite_code"];
$student_name = $_POST["student_name"];
$assignment_id = $_POST["assignment_id"];

// Retrieve submissions
$get_submissions_sql = sprintf("
SELECT *
FROM grades
WHERE invite_code = '%s' and assignment_id = '%s' and username = '%s' and submitted_flag = '1'
ORDER BY assignment_id ASC", $invite_code, $assignment_id, $student_name);

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
    "invite_code" => $row['invite_code'],
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
