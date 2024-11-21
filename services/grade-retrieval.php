<?php

require '../config.php';

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to database");
        }
    
        $dname = $_POST['dname'];
        $course_code = $_POST['course_code']; // Get the course_code from the request

        // First, retrieve the username based on the provided dname
        $usernameQuery = $conn->prepare("
            SELECT username FROM login WHERE dname = ?
        ");
        $usernameQuery->bind_param("s", $dname);
        $usernameQuery->execute();
        $usernameResult = $usernameQuery->get_result();

        if ($usernameResult->num_rows > 0) {
            // Fetch the username
            $usernameRow = $usernameResult->fetch_assoc();
            $username = $usernameRow['username'];

            // Fetch grades for the specific course associated with the student
            $gradesQuery = $conn->prepare("
                SELECT course_code, assignment_name, grade, max_grade, feedback
                FROM grades
                WHERE username = ? AND course_code = ?
            ");
            $gradesQuery->bind_param("ss", $username, $course_code);
            $gradesQuery->execute();
            $gradesResult = $gradesQuery->get_result();

            // Collect all grades into an array
            $grades = []; // Initialize grades array
            while ($row = $gradesResult->fetch_assoc()) {
                $grades[] = $row;
            }
            $gradesQuery->close();

            $success = 1;
            $error = 0;
        } else {
            $success = 0;
            $error = "No user found with the provided display name (dname).";
        }

        $usernameQuery->close();
        $conn->close();

    } catch (Exception $e) {
        $success = 0;
        $error = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error, "grades" => $grades]);
} else {
    header("Location: illegal.php");
}
