<?php

// Set response header to JSON
header('Content-Type: application/json');

// Initialize response structure
$response = [
    "success" => false,
    "error" => null,
    "assignments" => []
];

// Function to send JSON response and terminate script
function sendResponse($response, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($response);
    exit();
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Retrieve and sanitize input parameters (assuming POST request)
$course_code = isset($_POST['course_code']) ? sanitizeInput($_POST['course_code']) : null;
$username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : null;

// Validate required parameters
if (!$course_code || !$username) {
    $response['error'] = 'Missing course_code or username parameter.';
    sendResponse($response, 400); // Bad Request
}

// Database credentials
$DB_HOST = 'localhost';
$DB_USER = 'gradeplusclient';
$DB_PASS = 'gradeplussql';
$DB_NAME = 'gradeplus';

// Connect to MySQL using mysqli
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    $response['error'] = 'Database connection failed.';
    sendResponse($response, 500); // Internal Server Error
}

// Step 1: Determine user type
$stmt = $conn->prepare("SELECT usertype FROM login WHERE username = ?");
if (!$stmt) {
    $response['error'] = 'Database query preparation failed.';
    sendResponse($response, 500);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($usertype);
if (!$stmt->fetch()) {
    // User not found
    $stmt->close();
    $response['error'] = 'User not found.';
    sendResponse($response, 404); // Not Found
}
$stmt->close();

// Normalize usertype
$usertype = strtolower($usertype);

// Step 2: Fetch course details
$stmt = $conn->prepare("SELECT instructor_name FROM courses WHERE course_code = ?");
if (!$stmt) {
    $response['error'] = 'Database query preparation failed.';
    sendResponse($response, 500);
}
$stmt->bind_param("s", $course_code);
$stmt->execute();
$stmt->bind_result($instructor_name);
if (!$stmt->fetch()) {
    // Course not found
    $stmt->close();
    $response['error'] = 'Course not found.';
    sendResponse($response, 404);
}
$stmt->close();

// Authorization based on usertype
if ($usertype === 'admin') {
    // Admin has access to all assignments in any course
    // No additional checks needed
} elseif ($usertype === 'instructor') {
    // Verify that the instructor is assigned to this course
    if ($instructor_name !== $username) {
        $response['error'] = 'Unauthorized: You are not the instructor for this course.';
        sendResponse($response, 403); // Forbidden
    }
} elseif ($usertype === 'student') {
    // Verify that the student is enrolled in the course
    $stmt = $conn->prepare("SELECT COUNT(*) FROM enrollment WHERE username = ? AND course_code = ?");
    if (!$stmt) {
        $response['error'] = 'Database query preparation failed.';
        sendResponse($response, 500);
    }
    $stmt->bind_param("ss", $username, $course_code);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        $response['error'] = 'Unauthorized: You are not enrolled in this course.';
        sendResponse($response, 403); // Forbidden
    }
} else {
    // Unknown user type
    $response['error'] = 'Invalid user type.';
    sendResponse($response, 400); // Bad Request
}

// Step 3: Fetch assignments for the course
$stmt = $conn->prepare("SELECT assignment_name, assignment_file, description, due_date, instructor_username FROM assignment WHERE course_code = ?");
if (!$stmt) {
    $response['error'] = 'Database query preparation failed.';
    sendResponse($response, 500);
}
$stmt->bind_param("s", $course_code);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];

while ($row = $result->fetch_assoc()) {
    $assignments[] = [
        "assignment_name" => $row['assignment_name'],
        "assignment_file" => $row['assignment_file'], // Assuming this is a URL or path
        "description" => $row['description'],
        "due_date" => $row['due_date'],
        "instructor_username" => $row['instructor_username']
    ];
}

$stmt->close();
$conn->close();

// Populate response
$response['success'] = true;
$response['assignments'] = $assignments;

// Send response
sendResponse($response);
?>
