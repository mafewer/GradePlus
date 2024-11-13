<?php

require "../config.php";

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to database");
        }

        $course_code = $_POST['course_code'];
        $assignment_name = $_POST['assignment_name'];
        $dname = $_POST['dname'];  
        $grade = $_POST['grade'];

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

            // Update the grade in the grades table for the specified student, assignment, and course
            $updateGradeQuery = $conn->prepare("
                UPDATE grades
                SET grade = ?
                WHERE course_code = ? AND assignment_name = ? AND username = ?
            ");
            $updateGradeQuery->bind_param("isss", $grade, $course_code, $assignment_name, $username);

            if ($updateGradeQuery->execute()) {
                $success = 1;
                $error = 0;
            } else {
                $success = 0;
                $error = 1;
            }

            $updateGradeQuery->close();
        } else {
            $success = 0;
            $error = 1;
        }

        $usernameQuery->close();
        $conn->close();

    } catch (Exception $e) {
        $success = 0;
        $error = 1;
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error]);
} else {
    header("Location: illegal.php");
}
?>