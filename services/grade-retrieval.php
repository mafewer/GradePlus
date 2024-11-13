<?php

require '../config.php';

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to database");
        }
    
        $dname = $_POST['dname'];

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

            // Fetch all grades associated with the student
            $gradesQuery = $conn->prepare("
                SELECT course_code, assignment_name, grade, max_grade, feedback
                FROM grades
                WHERE username = ?
            ");
            $gradesQuery->bind_param("s", $username);
            $gradesQuery->execute();
            $gradesResult = $gradesQuery->get_result();

            // Collect all grades into an array
            while ($row = $gradesResult->fetch_assoc()) {
                $grades[] = $row;
            }
            $gradesQuery->close();

            $success = 1;
            $error = 0;
        } else {
            $success = 0;
            $error = 1;
        }
    } catch (Exception $e) {
        $success = 0;
        $error = 1;
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error, "grade" => $grades]);
} else {
    header("Location: illegal.php");
}