<?php

// File: /services/get-assignments.php

require '../config.php';

// Enable error reporting for debugging (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the content type to JSON
header('Content-Type: application/json');

// Helper function to send JSON responses
function sendResponse($success, $error, $data = [], $message = "")
{
    echo json_encode([
        "success" => $success,
        "error" => $error,
        "illegal" => 0,
        "data" => $data,
        "message" => $message
    ]);
    exit();
}

// Validate input parameters
if (empty($_POST["authorize"]) || empty($_POST["invite_code"])) {
    sendResponse(0, 1, [], "Missing or empty parameters.");
}

// Verify authorization
if (htmlspecialchars($_POST["authorize"]) !== "gradeplus") {
    header("Location: illegal.php");
    exit();
}

// Establish a new MySQLi connection
$conn = new mysqli($DB_HOST, 'gradeplusclient', 'gradeplussql', 'gradeplus');

// Check for connection errors
if ($conn->connect_error) {
    sendResponse(0, 1, [], "Connection failed: " . $conn->connect_error);
}

// Sanitize the course code
$invite_code = htmlspecialchars($_POST["invite_code"]);

// Check if the course exists
$checkCourseSql = "SELECT COUNT(*) FROM courses WHERE invite_code = ?";
$checkCourseStmt = $conn->prepare($checkCourseSql);
if (!$checkCourseStmt) {
    sendResponse(0, 1, [], "Preparation failed for course check: " . $conn->error);
}
$checkCourseStmt->bind_param("s", $invite_code);
$checkCourseStmt->execute();
$checkCourseStmt->bind_result($courseExists);
$checkCourseStmt->fetch();
$checkCourseStmt->close();

// If the course does not exist, return an error
if ($courseExists == 0) {
    sendResponse(0, 1, [], "Invite code '$invite_code' does not exist.");
}

// Prepare SQL query to retrieve assignments for the given course code
$sql = "
SELECT 
    assignment_name,
    assignment_file,
    description,
    due_date,
    instructor,
    assignment_id
FROM 
    assignment
WHERE 
    invite_code = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendResponse(0, 1, [], "Preparation failed: " . $conn->error);
}

// Bind course code parameter and execute the query
$stmt->bind_param("s", $invite_code);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    sendResponse(0, 1, [], "Query failed: " . $conn->error);
}

// Fetch all assignments and build the response
$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = [
        "assignment_name" => $row['assignment_name'],
        "assignment_file" => $row['assignment_file'],
        "description" => $row['description'],
        "due_date" => $row['due_date'],
        "instructor" => $row['instructor'],
        "assignment_id" => $row['assignment_id'],
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Send the successful response
sendResponse(1, 0, $assignments, "Assignments retrieved successfully.");
