<?php

require "../config.php";
if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Failed to connect to database");
        }

        $invite_code = $_POST['invite_code'];
        $assignment_id = $_POST['assignment_id'];
        $username = $_POST['username'];
        $grade = $_POST['grade'];
        $max_grade = $_POST['max_grade'];
        $feedback = $_POST['feedback'];

        if ($username != null) {
            // Update the grade in the grades table for the specified student, assignment, and course
            $updateGradeQuery = $conn->prepare("
                UPDATE grades
                SET grade = ?, max_grade = ?, feedback = ?
                WHERE invite_code = ? AND assignment_id = ? AND username = ?
            ");
            $updateGradeQuery->bind_param("iissis", $grade, $max_grade, $feedback, $invite_code, $assignment_id, $username);

            if ($updateGradeQuery->execute()) {
                if ($conn->affected_rows > 0) {
                    $success = 1;
                    $error = 0;
                } else {
                    // No rows were updated
                    $success = 0;
                    $error = 1;
                }
            } else {
                $success = 0;
                $error = 1;
            }

            $updateGradeQuery->close();
        } else {
            $success = 0;
            $error = 1;
        }

        $conn->close();

    } catch (Exception $e) {
        $success = 0;
        $error = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error]);
} else {
    header("Location: illegal.php");
}
