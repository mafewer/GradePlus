<?php

// File: /services/get_students.php

// Set the content type to JSON
header('Content-Type: application/json');

// Shared secret for authorization
if (empty($_POST["authorize"]) || empty($_POST["invite_code"])) {
    sendResponse(0, 1, [], "Missing or empty parameters.");
}

// Verify authorization
if (htmlspecialchars($_POST["authorize"]) !== "gradeplus") {
    header("Location: illegal.php");
    exit();
}

// Create a new MySQLi connection
$conn = new mysqli('localhost', 'gradeplusclient', 'gradeplussql', 'gradeplus');

// Check for connection errors
if ($conn->connect_error) {
    sendResponse(0, 1, [], "Connection failed: " . $conn->connect_error);
}

// Bind the course_code variable
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

// If the course does not exist, send an error response
if ($courseExists == 0) {
    sendResponse(0, 1, [], "Course code '$invite_code' does not exist.");
}

// Prepare SQL query to fetch student information
$sql = "
SELECT 
    login.profile_picture,
    login.dname,
    login.username,
    login.email
FROM 
    login
JOIN 
    enrollment ON login.username = enrollment.username
WHERE 
    enrollment.invite_code = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendResponse(0, 1, [], "Preparation failed: " . $conn->error);
}

// Bind parameter and execute the query
$stmt->bind_param("s", $invite_code);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    sendResponse(0, 1, [], "Query failed: " . $conn->error);
}

// Fetch data and build the response
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        "profile_picture" => $row['profile_picture'],
        "dname" => $row['dname'],
        "username" => $row['username'],
        "email" => $row['email']
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Send the successful response
sendResponse(1, 0, $students, "Students retrieved successfully.");

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
