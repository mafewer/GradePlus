<?php
// File path: /services/studentInfoService.php

// Simulate POST data for testing purposes
$_POST['authorize'] = 'gradeplus';
$_POST['course_code'] = 'CS101';

// Define the shared secret for authorization
$shared_secret = "gradeplus";

// Check if the request is a POST request
if (true) {
    // Get the 'authorize' value from the POST request
    $authorize = $_POST['authorize'] ?? '';

    // Check if the authorization is correct
    if ($authorize !== $shared_secret) {
        header("Location: illegal.php");
        exit();
    }

    // Get the Course Code from POST
    $course_code = $_POST['course_code'] ?? '';

    if (empty($course_code)) {
        echo json_encode(['success' => 0, 'error' => 1, 'message' => 'Course Code is required']);
        exit();
    }

    // Connect to MySQL as admin to check/create the gradeplusclient user
    $conn = mysqli_connect('localhost', 'root', '');
    if (!$conn) {
        die(json_encode(['success' => 0, 'error' => 1, 'message' => 'Connection to MySQL as admin failed: ' . mysqli_connect_error()]));
    }

    // Check if gradeplusclient user exists
    $checkUserSql = "SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = 'gradeplusclient')";
    $result = mysqli_query($conn, $checkUserSql);
    $row = mysqli_fetch_array($result);
    
    // Create user and give privileges if it does not exist
    if ($row[0] == 0) {
        $createUserSql = "CREATE USER 'gradeplusclient'@'localhost' IDENTIFIED BY 'gradeplussql'";
        $result = mysqli_query($conn, $createUserSql);
        if (!$result) {
            die(json_encode(['success' => 0, 'error' => 1, 'message' => 'Create user query failed: ' . mysqli_error($conn)]));
        }

        $grantPrivilegesSql = "GRANT ALL PRIVILEGES ON gradeplus.* TO 'gradeplusclient'@'localhost';";
        $result = mysqli_query($conn, $grantPrivilegesSql);
        if (!$result) {
            die(json_encode(['success' => 0, 'error' => 1, 'message' => 'Grant privileges query failed: ' . mysqli_error($conn)]));
        }

        $result = mysqli_query($conn, "FLUSH PRIVILEGES");
        if (!$result) {
            die(json_encode(['success' => 0, 'error' => 1, 'message' => 'Flush privileges query failed: ' . mysqli_error($conn)]));
        }
    }

    // Close admin connection
    mysqli_close($conn);

    // Connect to MySQL as gradeplusclient
    $mysqli = new mysqli("localhost", "gradeplusclient", "gradeplussql", "gradeplus");

    // Check connection
    if ($mysqli->connect_error) {
        die(json_encode(['success' => 0, 'error' => 1, 'message' => 'Connection to MySQL as gradeplusclient failed: ' . $mysqli->connect_error]));
    }

    // Prepare the query to get student information for the given course
    $stmt = $mysqli->prepare("SELECT profile_picture, dname, username FROM students WHERE course_code = ?");
    $stmt->bind_param("s", $course_code);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch student information
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = [
                'profile_picture' => $row['profile_picture'],
                'dname' => $row['dname'],
                'username' => $row['username']
            ];
        }

        // Return the student list as a JSON response
        echo json_encode(['success' => 1, 'error' => 0, 'students' => $students]);
    } else {
        // Handle MySQL execution failure
        echo json_encode(['success' => 0, 'error' => 1, 'message' => 'Failed to retrieve student data']);
    }

    // Close connections
    $stmt->close();
    $mysqli->close();

} else {
    // If the request method is not POST, redirect to illegal.php
    header("Location: illegal.php");
    exit();
}
?>
