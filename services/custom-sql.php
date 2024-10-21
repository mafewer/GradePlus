<?php

require '../config.php';

// Service to send custom SQL commands from the admin page
if ($_POST["authorize"] == "gradeplus") {
    if (isset($_POST['command'])) {
        try {
            $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

            if (!$conn) {
                throw new Exception();
            }

            $sqlCommand = $_POST['command'];

            if ($conn->query($sqlCommand)) {
                $success = 1;
            } else {
                $success = 0;
            }
        } catch (Exception $e) {
            $success = 0;
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode([
            "success" => $success
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            "success" => 0
        ]);
    }
} else {
    header("Location: illegal.php");
}
