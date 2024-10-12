<?php
// File: /services/get_students.php

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the content type to JSON
header('Content-Type: application/json');

// Function to handle unauthorized access
function redirectIllegal($message = "Unauthorized access.") {
    sendResponse(0, 1, [], $message);
}

// Function to send JSON response and exit
function sendResponse($success, $error, $data = [], $message = "") {
    echo json_encode([
        "success" => $success,
        "error" => $error,
        "illegal" => 0,
        "data" => $data,
        "message" => $message
    ]);
    exit();
}

// Example Authorization Check (Adjust as needed)
// Assuming you have an authorization mechanism in place
// For demonstration, let's assume authorization is successful
$authorized = true; // Replace with actual authorization logic

if (!$authorized) {
    redirectIllegal("You are not authorized to perform this action.");
}

// Get the course code from the POST request
$course_code = isset($_POST['course_code']) ? $_POST['course_code'] : 'COURSE102'; // Default course code for testing

// Database credentials
$db_host = 'localhost';
$db_user = 'gradeplusclient';
$db_pass = 'gradeplussql';
$db_name = 'gradeplus';

// Create a new MySQLi connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    sendResponse(0, 1, [], "Database connection failed.");
}

// Populate the login table with sample data for testing
$insertStudentLoginSql = "
INSERT IGNORE INTO login (username, email, password, dname, loggedin, usertype) VALUES
('student1', 'student1@gradeplus.com', 'password', 'Student Name 1', 0, 'Student'),
('student2', 'student2@gradeplus.com', 'password', 'Student Name 2', 0, 'Student'),
('student3', 'student3@gradeplus.com', 'password', 'Student Name 3', 0, 'Student'),
('student4', 'student4@gradeplus.com', 'password', 'Student Name 4', 0, 'Student'),
('student5', 'student5@gradeplus.com', 'password', 'Student Name 5', 0, 'Student')
";
if (!$conn->query($insertStudentLoginSql)) {
    error_log("Failed to insert sample data into login: " . $conn->error);
    sendResponse(0, 1, [], "Failed to insert sample login data.");
}

// Insert the students into the specific course in the enrollment table
$insertIntoEnrollmentSql = "
INSERT IGNORE INTO enrollment (username, courseCode, courseName, pinned, inviteCode, instructor) VALUES
('student1', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student2', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student3', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student4', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student5', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A')
";

if (!$conn->query($insertIntoEnrollmentSql)) {
    error_log("Failed to insert sample data into enrollment: " . $conn->error);
    sendResponse(0, 1, [], "Failed to insert sample enrollment data.");
}

// Prepare SQL query to fetch student information for the specific course
$sql = "
SELECT 
    login.profilePicture,
    login.dname,
    login.username
FROM 
    login
JOIN 
    enrollment ON login.username = enrollment.username
WHERE 
    enrollment.courseCode = ?
";

// Prepare the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    sendResponse(0, 1, [], "Failed to prepare the SQL statement.");
}

$stmt->bind_param("s", $course_code);

// Execute the query
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    sendResponse(0, 1, [], "Failed to execute the SQL statement.");
}

$result = $stmt->get_result();

// Check for query execution errors
if (!$result) {
    error_log("Get result failed: " . $stmt->error);
    sendResponse(0, 1, [], "Failed to retrieve the query results.");
}

// Fetch data and build the response array
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        "profilePicture" => $row['profilePicture'] ? base64_encode($row['profilePicture']) : null, // Encoding binary data or null
        "dname" => $row['dname'],
        "username" => $row['username']
    ];
}

// Close the statement and the database connection
$stmt->close();
$conn->close();

// Send the successful response
sendResponse(1, 0, $students, "Students retrieved successfully.");
?>
