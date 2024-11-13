<?php

// Include database configuration
require '../config.php';

$success = 0;
$error = 0;

if ($_POST["authorize"] == "gradeplus") {
    try {
        // Connect to the database
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Get required fields
        $username = htmlspecialchars($_POST['username'] ?? '');
        $assignment_name = htmlspecialchars($_POST['assignment_name'] ?? '');
        $assignment_id = htmlspecialchars($_POST['assignment_id'] ?? '');
        $review = htmlspecialchars($_POST['review'] ?? '');  // Get the review text

        // Check if the record already exists
        $checkSql = $conn->prepare("SELECT 1 FROM reviews WHERE student = ? AND assignment_id = ?");
        $checkSql->bind_param("si", $username, $assignment_id);
        $checkSql->execute();
        $checkSql->store_result();

        if ($review != "") {
            $submitSql = $conn->prepare("
                INSERT INTO reviews (assignment_id, assignment_name, student, review)
                VALUES (?, ?, ?, ?)
            ");
            $submitSql->bind_param("isss", $assignment_id, $assignment_name, $username, $review);
            $result = $submitSql->execute();
            if (!$result) {
                throw new Exception("Submission query failed: " . $submitSql->error);
            }
            $success = 1;
        }

        $checkSql->close();
        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $success = 0;
        $error = 1;
    }
}

header('Content-Type: application/json');
echo json_encode(["success" => $success, "error" => $error]);
?>
