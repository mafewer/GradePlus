<?php
// Set response header to JSON
header('Content-Type: application/json');

// Initialize response structure
$response = [
    "success" => 0,
    "error" => 0,
    "illegal" => 0,
    "assignments" => [],
    "message" => ""
];

// Function to send JSON response and terminate script
function sendResponse($response) {
    echo json_encode($response);
    exit();
}

// Check if 'authorize' parameter is set and correct
if (!isset($_POST["authorize"]) || $_POST["authorize"] !== "gradeplus") {
    $response["illegal"] = 1;
    $response["message"] = "Unauthorized access.";
    sendResponse($response);
}

// Database credentials
$DB_HOST = 'localhost';
$DB_USER = 'root'; // Change if different
$DB_PASS = '';     // Change if different
$DB_NAME = 'gradeplus';

// Connect to MySQL as root to create database and user if needed
$adminConn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);

// Check connection
if ($adminConn->connect_error) {
    $response["error"] = 1;
    $response["message"] = "Admin DB Connection failed: " . $adminConn->connect_error;
    sendResponse($response);
}

// Create database if it doesn't exist
$createDbSql = "CREATE DATABASE IF NOT EXISTS $DB_NAME";
if (!$adminConn->query($createDbSql)) {
    $response["error"] = 1;
    $response["message"] = "Database creation failed: " . $adminConn->error;
    sendResponse($response);
}

// Create gradeplusclient user if not exists and grant privileges
$checkUserSql = "SELECT COUNT(*) as count FROM mysql.user WHERE user = 'gradeplusclient' AND host='localhost'";
$result = $adminConn->query($checkUserSql);
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    // Create user
    $createUserSql = "CREATE USER 'gradeplusclient'@'localhost' IDENTIFIED BY 'gradeplussql'";
    if (!$adminConn->query($createUserSql)) {
        $response["error"] = 1;
        $response["message"] = "User creation failed: " . $adminConn->error;
        sendResponse($response);
    }

    // Grant privileges
    $grantPrivilegesSql = "GRANT ALL PRIVILEGES ON $DB_NAME.* TO 'gradeplusclient'@'localhost'";
    if (!$adminConn->query($grantPrivilegesSql)) {
        $response["error"] = 1;
        $response["message"] = "Granting privileges failed: " . $adminConn->error;
        sendResponse($response);
    }

    // Flush privileges
    if (!$adminConn->query("FLUSH PRIVILEGES")) {
        $response["error"] = 1;
        $response["message"] = "Flushing privileges failed: " . $adminConn->error;
        sendResponse($response);
    }
}

// Close admin connection
$adminConn->close();

// Connect to MySQL as gradeplusclient
$conn = new mysqli($DB_HOST, 'gradeplusclient', 'gradeplussql', $DB_NAME);

// Check connection
if ($conn->connect_error) {
    $response["error"] = 1;
    $response["message"] = "gradeplusclient DB Connection failed: " . $conn->connect_error;
    sendResponse($response);
}

// Function to initialize tables and insert fake data
function initializeDatabase($conn, &$response) {
    // Create 'login' table
    $createLoginTable = "
    CREATE TABLE IF NOT EXISTS login (
        username VARCHAR(50) PRIMARY KEY,
        email VARCHAR(50),
        password VARCHAR(255),
        dname VARCHAR(100),
        loggedin INT,
        profilePicture LONGBLOB,
        usertype VARCHAR(20) NOT NULL DEFAULT 'Student'
    ) ENGINE=InnoDB;
    ";
    if (!$conn->query($createLoginTable)) {
        $response["error"] = 1;
        $response["message"] = "Failed to create 'login' table: " . $conn->error;
        sendResponse($response);
    }

    // Create 'courses' table
    $createCoursesTable = "
    CREATE TABLE IF NOT EXISTS courses (
        course_code VARCHAR(50) PRIMARY KEY,
        course_name VARCHAR(100) NOT NULL,
        course_banner VARCHAR(255),
        instructor_name VARCHAR(50) NOT NULL,
        instructor_dname VARCHAR(100) NOT NULL,
        invite_code VARCHAR(10) UNIQUE NOT NULL
    ) ENGINE=InnoDB;
    ";
    if (!$conn->query($createCoursesTable)) {
        $response["error"] = 1;
        $response["message"] = "Failed to create 'courses' table: " . $conn->error;
        sendResponse($response);
    }

    // Create 'enrollment' table
    $createEnrollmentTable = "
    CREATE TABLE IF NOT EXISTS enrollment (
        username VARCHAR(50),
        course_code VARCHAR(50),
        course_name VARCHAR(100),
        pinned INT,
        invite_code VARCHAR(10),
        instructor VARCHAR(50),
        PRIMARY KEY (username, course_code),
        FOREIGN KEY (username) REFERENCES login(username) ON DELETE CASCADE,
        FOREIGN KEY (course_code) REFERENCES courses(course_code) ON DELETE CASCADE,
        FOREIGN KEY (instructor) REFERENCES login(username) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    if (!$conn->query($createEnrollmentTable)) {
        $response["error"] = 1;
        $response["message"] = "Failed to create 'enrollment' table: " . $conn->error;
        sendResponse($response);
    }

    // Create 'assignment' table
    $createAssignmentTable = "
    CREATE TABLE IF NOT EXISTS assignment (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(50),
        assignment_name VARCHAR(50),
        assignment_file LONGBLOB,
        description VARCHAR(255),
        due_date DATE,
        instructor VARCHAR(50),
        FOREIGN KEY (course_code) REFERENCES courses(course_code) ON DELETE CASCADE,
        FOREIGN KEY (instructor) REFERENCES login(username) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";
    if (!$conn->query($createAssignmentTable)) {
        $response["error"] = 1;
        $response["message"] = "Failed to create 'assignment' table: " . $conn->error;
        sendResponse($response);
    }

    // Check if 'login' table is empty
    $checkLoginData = "SELECT COUNT(*) as count FROM login";
    $result = $conn->query($checkLoginData);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) {
            // Insert fake data into 'login' table
            $insertLoginData = "
            INSERT INTO login (username, email, password, dname, loggedin, usertype) VALUES
            ('admin', 'admin@gradeplus.com', 'adminpass', 'Administrator', 0, 'Admin'),
            ('instructor1', 'instructor1@gradeplus.com', 'instr1pass', 'Instructor One', 0, 'Instructor'),
            ('instructor2', 'instructor2@gradeplus.com', 'instr2pass', 'Instructor Two', 0, 'Instructor'),
            ('student1', 'student1@gradeplus.com', 'stud1pass', 'Student One', 0, 'Student'),
            ('student2', 'student2@gradeplus.com', 'stud2pass', 'Student Two', 0, 'Student'),
            ('student3', 'student3@gradeplus.com', 'stud3pass', 'Student Three', 0, 'Student');
            ";
            if (!$conn->query($insertLoginData)) {
                $response["error"] = 1;
                $response["message"] = "Failed to insert fake data into 'login' table: " . $conn->error;
                sendResponse($response);
            }

            // Insert fake data into 'courses' table
            $insertCoursesData = "
            INSERT INTO courses (course_code, course_name, course_banner, instructor_name, instructor_dname, invite_code) VALUES
            ('ECE6400', 'Software Development', '../img/software_dev.jpg', 'instructor1', 'Instructor One', 'ABCDEF'),
            ('ECE6500', 'Advanced Algorithms', '../img/advanced_algorithms.jpg', 'instructor2', 'Instructor Two', 'GHIJKL'),
            ('ECE6600', 'Data Structures', '../img/data_structures.jpg', 'instructor1', 'Instructor One', 'MNOPQR');
            ";
            if (!$conn->query($insertCoursesData)) {
                $response["error"] = 1;
                $response["message"] = "Failed to insert fake data into 'courses' table: " . $conn->error;
                sendResponse($response);
            }

            // Insert fake data into 'enrollment' table
            $insertEnrollmentData = "
            INSERT INTO enrollment (username, course_code, course_name, pinned, invite_code, instructor) VALUES
            -- Enrollments for ECE6400
            ('student1', 'ECE6400', 'Software Development', 1, 'ABCDEF', 'instructor1'),
            ('student2', 'ECE6400', 'Software Development', 0, 'ABCDEF', 'instructor1'),
            ('student3', 'ECE6400', 'Software Development', 0, 'ABCDEF', 'instructor1'),
            ('instructor1', 'ECE6400', 'Software Development', 1, 'ABCDEF', 'instructor1'),

            -- Enrollments for ECE6500
            ('student1', 'ECE6500', 'Advanced Algorithms', 1, 'GHIJKL', 'instructor2'),
            ('student2', 'ECE6500', 'Advanced Algorithms', 0, 'GHIJKL', 'instructor2'),
            ('student3', 'ECE6500', 'Advanced Algorithms', 0, 'GHIJKL', 'instructor2'),
            ('instructor2', 'ECE6500', 'Advanced Algorithms', 1, 'GHIJKL', 'instructor2'),

            -- Enrollments for ECE6600
            ('student1', 'ECE6600', 'Data Structures', 1, 'MNOPQR', 'instructor1'),
            ('student2', 'ECE6600', 'Data Structures', 0, 'MNOPQR', 'instructor1'),
            ('student3', 'ECE6600', 'Data Structures', 0, 'MNOPQR', 'instructor1'),
            ('instructor1', 'ECE6600', 'Data Structures', 1, 'MNOPQR', 'instructor1');
            ";
            if (!$conn->multi_query($insertEnrollmentData)) {
                $response["error"] = 1;
                $response["message"] = "Failed to insert fake data into 'enrollment' table: " . $conn->error;
                sendResponse($response);
            }
            // Flush multi_queries
            while ($conn->more_results() && $conn->next_result()) {}

            // Insert fake data into 'assignment' table
            $insertAssignmentData = "
            INSERT INTO assignment (course_code, assignment_name, assignment_file, description, due_date, instructor) VALUES
            -- Assignments for ECE6400
            ('ECE6400', 'A1', NULL, 'Introduction to Software Development', '2024-11-15', 'instructor1'),
            ('ECE6400', 'A2', NULL, 'Object-Oriented Design', '2024-12-01', 'instructor1'),
            ('ECE6400', 'A3', NULL, 'Database Integration', '2024-12-15', 'instructor1'),

            -- Assignments for ECE6500
            ('ECE6500', 'A1', NULL, 'Algorithm Analysis', '2024-11-20', 'instructor2'),
            ('ECE6500', 'A2', NULL, 'Graph Algorithms', '2024-12-05', 'instructor2'),
            ('ECE6500', 'A3', NULL, 'Dynamic Programming', '2024-12-20', 'instructor2'),

            -- Assignments for ECE6600
            ('ECE6600', 'A1', NULL, 'Arrays and Linked Lists', '2024-11-25', 'instructor1'),
            ('ECE6600', 'A2', NULL, 'Trees and Graphs', '2024-12-10', 'instructor1'),
            ('ECE6600', 'A3', NULL, 'Hash Tables', '2024-12-25', 'instructor1');
            ";
            if (!$conn->multi_query($insertAssignmentData)) {
                $response["error"] = 1;
                $response["message"] = "Failed to insert fake data into 'assignment' table: " . $conn->error;
                sendResponse($response);
            }
            // Flush multi_queries
            while ($conn->more_results() && $conn->next_result()) {}
        }
    }

// Initialize database and insert fake data if necessary
initializeDatabase($conn, $response);

// Retrieve and sanitize POST parameters
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';
$assignment_name = isset($_POST['assignment_name']) ? trim($_POST['assignment_name']) : '';

// Validate required parameters
if (empty($username) || empty($course_code) || empty($assignment_name)) {
    $response["error"] = 1;
    $response["message"] = "Missing required parameters.";
    sendResponse($response);
}

// Determine user type from 'login' table
$userType = '';
$getUserTypeSql = "SELECT usertype FROM login WHERE username = ?";
$stmt = $conn->prepare($getUserTypeSql);
if (!$stmt) {
    $response["error"] = 1;
    $response["message"] = "Prepare failed: " . $conn->error;
    sendResponse($response);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userType);
if (!$stmt->fetch()) {
    // User not found
    $stmt->close();
    $response["error"] = 1;
    $response["message"] = "User not found.";
    sendResponse($response);
}
$stmt->close();

// Function to fetch assignments for instructors/admins
function fetchAssignmentsInstructor($conn, $course_code, $assignment_name, &$response) {
    // Verify instructor is assigned to the course
    $verifyCourseSql = "SELECT instructor_name FROM courses WHERE course_code = ?";
    $stmt = $conn->prepare($verifyCourseSql);
    if (!$stmt) {
        $response["error"] = 1;
        $response["message"] = "Prepare failed: " . $conn->error;
        sendResponse($response);
    }
    $stmt->bind_param("s", $course_code);
    $stmt->execute();
    $stmt->bind_result($instructor_name);
    if (!$stmt->fetch()) {
        // Course not found
        $stmt->close();
        $response["error"] = 1;
        $response["message"] = "Course not found.";
        sendResponse($response);
    }
    $stmt->close();

    // Check if the requester is the instructor for the course
    $isInstructorSql = "SELECT COUNT(*) as count FROM courses WHERE course_code = ? AND instructor_name = ?";
    $stmt = $conn->prepare($isInstructorSql);
    if (!$stmt) {
        $response["error"] = 1;
        $response["message"] = "Prepare failed: " . $conn->error;
        sendResponse($response);
    }
    $stmt->bind_param("ss", $course_code, $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        // Not the instructor for the course
        $response["error"] = 1;
        $response["message"] = "Unauthorized: You are not the instructor for this course.";
        sendResponse($response);
    }

    // Fetch assignments with the given name for the course
    $getAssignmentsSql = "SELECT assignment_name, description, due_date FROM assignment WHERE course_code = ? AND assignment_name = ?";
    $stmt = $conn->prepare($getAssignmentsSql);
    if (!$stmt) {
        $response["error"] = 1;
        $response["message"] = "Prepare failed: " . $conn->error;
        sendResponse($response);
    }
    $stmt->bind_param("ss", $course_code, $assignment_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if assignments exist
    if ($result->num_rows === 0) {
        $stmt->close();
        $response["success"] = 1;
        $response["assignments"] = [];
        sendResponse($response);
    }

    // Fetch assignments
    while ($row = $result->fetch_assoc()) {
        $response["assignments"][] = [
            "assignment_name" => $row['assignment_name'],
            "description" => $row['description'],
            "due_date" => $row['due_date']
        ];
    }
    $stmt->close();

    // Set success flag
    $response["success"] = 1;
    sendResponse($response);
}

// Function to fetch assignments for students
function fetchAssignmentsStudent($conn, $username, $course_code, $assignment_name, &$response) {
    // Verify student is enrolled in the course
    $verifyEnrollmentSql = "SELECT COUNT(*) as count FROM enrollment WHERE username = ? AND course_code = ?";
    $stmt = $conn->prepare($verifyEnrollmentSql);
    if (!$stmt) {
        $response["error"] = 1;
        $response["message"] = "Prepare failed: " . $conn->error;
        sendResponse($response);
    }
    $stmt->bind_param("ss", $username, $course_code);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        // Not enrolled in the course
        $response["error"] = 1;
        $response["message"] = "Unauthorized: You are not enrolled in this course.";
        sendResponse($response);
    }

    // Fetch the assignment details
    $getAssignmentsSql = "SELECT assignment_name, description, due_date FROM assignment WHERE course_code = ? AND assignment_name = ?";
    $stmt = $conn->prepare($getAssignmentsSql);
    if (!$stmt) {
        $response["error"] = 1;
        $response["message"] = "Prepare failed: " . $conn->error;
        sendResponse($response);
    }
    $stmt->bind_param("ss", $course_code, $assignment_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if assignments exist
    if ($result->num_rows === 0) {
        $stmt->close();
        $response["success"] = 1;
        $response["assignments"] = [];
        sendResponse($response);
    }

    // Fetch assignments
    while ($row = $result->fetch_assoc()) {
        $response["assignments"][] = [
            "assignment_name" => $row['assignment_name'],
            "description" => $row['description'],
            "due_date" => $row['due_date']
        ];
    }
    $stmt->close();

    // Set success flag
    $response["success"] = 1;
    sendResponse($response);
}

// Process based on user type
if ($userType === 'Instructor' || $userType === 'Admin') {
    fetchAssignmentsInstructor($conn, $course_code, $assignment_name, $response);
} elseif ($userType === 'Student') {
    fetchAssignmentsStudent($conn, $username, $course_code, $assignment_name, $response);
} else {
    // Unknown user type
    $response["error"] = 1;
    $response["message"] = "Unknown user type.";
    sendResponse($response);
}

// Close the database connection
$conn->close();

// Send the response (in case no earlier sendResponse was called)
sendResponse($response);
?>
