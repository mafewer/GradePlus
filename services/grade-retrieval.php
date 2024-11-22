<?php

require '../config.php';

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to database");
        }

        $username = $_POST['username'];
        $course_code = $_POST['course_code']; // Get the course_code from the request

        if ($username != null) {
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
            $error = "Username not found or empty";
        }

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
