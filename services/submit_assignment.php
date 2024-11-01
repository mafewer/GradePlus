<?php

// Include database configuration
require '../config.php';

$success = 0;
$error = 0;

$_POST['assignment_id'] = 0;
$_POST['course_code'] = 'ECE 6400';
$_POST['assignment_name'] = 'A1';
$_POST['username'] = 'student2';
$_POST["authorize"] = 'gradeplus';

if ($_POST["authorize"] == "gradeplus") {
    try {
        // Connect to the database
        $conn = new mysqli("localhost", "gradeplusclient", "gradeplussql", "gradeplus");

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
        echo "username : " . $username;
        echo "<br>";
        // Prepare SQL statement
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

    } catch (Exception $e) {
        error_log($e->getMessage());
        $success = 0;
        $error = 1;
    }

    // Close connection
    $submitSql->close();
    $conn->close();
}
#header('Content-Type: application/json');
#echo json_encode(["success" => $success,"error" => $error]);
echo "Success : " . $success;
echo "<br>";
echo "Error : " . $error;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Assignment</title>
</head>
<body>

<h2>Submit Assignment</h2>

<form id="submitAssignmentForm" method="POST" enctype="multipart/form-data">
    <!-- Hidden input to set authorize key -->
    <input type="hidden" name="authorize" value="gradeplus">

    <label for="submitted_pdf">Upload PDF (required):</label>
    <input type="file" id="submitted_pdf" name="submitted_pdf" accept="application/pdf" required>
    
    <input type="submit" value="Submit Assignment">
</form>

</body>
</html>

