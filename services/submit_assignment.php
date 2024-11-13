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
        $course_code = htmlspecialchars($_POST['course_code'] ?? '');
        $assignment_id = htmlspecialchars($_POST['assignment_id'] ?? '');

        // Initialize PDF data and flags
        $submitted_pdf = null;
        $submitted_flag = 0;
        $submitted_date = null;

        // Check if a PDF file was uploaded
        if (isset($_FILES['submitted_pdf']) && $_FILES['submitted_pdf']['error'] === UPLOAD_ERR_OK) {
            $submitted_pdf = file_get_contents($_FILES['submitted_pdf']['tmp_name']);
            $submitted_flag = 1;
            $submitted_date = date('Y-m-d');
        }

        // Check if the record already exists
        $checkSql = $conn->prepare("SELECT 1 FROM grades WHERE username = ? AND assignment_id = ?");
        $checkSql->bind_param("si", $username, $assignment_id);
        $checkSql->execute();
        $checkSql->store_result();

        if ($checkSql->num_rows > 0) {
            // Record exists, update submission
            $updateSql = $conn->prepare("
                UPDATE grades 
                SET submitted_pdf = ?, submitted_flag = ?, submitted_date = ?, course_code = ?, assignment_name = ?
                WHERE username = ? AND assignment_id = ?
            ");
            
            $updateSql->bind_param("sissssi", $submitted_pdf, $submitted_flag, $submitted_date, $course_code, $assignment_name, $username, $assignment_id);
            $result = $updateSql->execute();
            
            if (!$result) {
                throw new Exception("Update query failed: " . $updateSql->error);
            }
            
            $success = 1;
            
        } else {
            $submitSql = $conn->prepare("
                INSERT INTO grades (assignment_id, course_code, assignment_name, username, submitted_pdf, submitted_flag, submitted_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    submitted_pdf = VALUES(submitted_pdf), 
                    submitted_flag = VALUES(submitted_flag), 
                    submitted_date = VALUES(submitted_date)
            ");
            $submitSql->bind_param("issssis", $assignment_id, $course_code, $assignment_name, $username, $submitted_pdf, $submitted_flag, $submitted_date);
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
echo json_encode(["success" => $success,"error" => $error]);
?>

