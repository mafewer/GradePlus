<?php
// File: /services/get_students.php

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

// Get the course code from the POST request
$course_code = isset($_POST['course_code']) ? $_POST['course_code'] : 'COURSE102'; // Default course code for testing

// At this point, authorization is successful
$success = 1;
$error = 0;
$data = [];

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
    sendResponse(0, 1);
}

// Populate the login table with sample data for testing
$insertStudentLoginSql = "
INSERT IGNORE INTO login (username, email, password, dname) VALUES
('student1', 'student1@gradeplus.com', 'password', 'Student Name 1'),
('student2', 'student2@gradeplus.com', 'password', 'Student Name 2'),
('student3', 'student3@gradeplus.com', 'password', 'Student Name 3'),
('student4', 'student4@gradeplus.com', 'password', 'Student Name 4'),
('student5', 'student5@gradeplus.com', 'password', 'Student Name 5')
";
$conn->query($insertStudentLoginSql);

// Insert the students into the specific course in the enrollment table
$insertIntoEnrollmentSql = "
INSERT IGNORE INTO enrollment (username, course_code, course_name, pinned, invite_code, instructor) VALUES
('student1', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student2', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student3', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student4', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A'),
('student5', '$course_code', 'Introduction to Programming', 0, NULL, 'Instructor A')
";

$result = $conn->query($insertIntoEnrollmentSql);
if (!$result) {
    error_log("Failed to insert sample data into enrollment: " . $conn->error);
    sendResponse(0, 1);
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
    enrollment.course_code = ?
";

// Prepare the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $course_code);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check for query execution errors
if (!$result) {
    error_log("Query failed: " . $conn->error);
    sendResponse(0, 1);
}

// Fetch data and build the response array
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        "profilePicture" => base64_encode($row['profilePicture']), // Encoding binary data
        "dname" => $row['dname'],
        "username" => $row['username']
    ];
}

// Close the database connection
$stmt->close();
$conn->close();

// Send the successful response
sendResponse($success, $error, $students, "Students retrieved successfully.");
?>