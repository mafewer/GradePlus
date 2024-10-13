<?php
// File: /services/get_students.php

// Set the content type to JSON
header('Content-Type: application/json');

// Shared secret for authorization
$sharedSecret = "gradeplus";

// Get POST data
$course_code = $_POST['course_code'] ?? '6610'; // Default course code for testing
$authorize = $_POST['authorize'] ?? null;

$authorize = "gradeplus";

// Check authorization
if ($authorize !== $sharedSecret) {
    header("Location: illegal.php");
    exit();
}

// Database credentials
$db_host = 'localhost';
$db_user = 'gradeplusclient';
$db_pass = 'gradeplussql';
$db_name = 'gradeplus';

// Create a new MySQLi connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    sendResponse(0, 1, [], "Connection failed: " . $conn->connect_error);
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
    sendResponse(0, 1, [], "Preparation failed: " . $conn->error);
}
$stmt->bind_param("s", $course_code);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check for query execution errors
if (!$result) {
    sendResponse(0, 1, [], "Query failed: " . $conn->error);
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
sendResponse(1, 0, $students, "Students retrieved successfully.");

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
?>
