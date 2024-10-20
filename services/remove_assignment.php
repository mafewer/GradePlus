<?php

$success = 0;
$invalid_course = 0;
$assignment_not_found = 0;
$error = 0;

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli("localhost", "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $course_code = $_POST['course_code'];
            $assignment_id = $_POST['assignment_id'];
            $instructor = $_POST['instructor'];
            // Verify if the instructor owns the course
            $verifyInstructorSql = "SELECT * FROM courses WHERE course_code = ? AND instructor_name = ?";
            $stmt = $conn->prepare($verifyInstructorSql);
            $stmt->bind_param("ss", $course_code, $instructor);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                // Instructor owns the course, proceed with deleting the assignment
                $stmt->close();
                
                // Check if the assignment exists
                $verifyAssignmentSql = "SELECT * FROM assignment WHERE assignment_id = ? AND course_code = ?";
                $stmt = $conn->prepare($verifyAssignmentSql);
                $stmt->bind_param("is", $assignment_id, $course_code);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Assignment exists, proceed with deletion
                    $stmt->close();
                    
                    $deleteSql = "DELETE FROM assignment WHERE assignment_id = ? AND course_code = ?";
                    $stmt = $conn->prepare($deleteSql);
                    $stmt->bind_param("is", $assignment_id, $course_code);

                    if ($stmt->execute()) {
                        $success = 1;
                    } else {
                        $error = 1;
                    }
                } else {
                    // Assignment not found
                    $assignment_not_found = 1;
                }
                $stmt->close();
            } else {
                $invalid_course = 1;
            }
        }
        $conn->close();

    } catch (Exception $e) {
        $success = 0;
        $error = 1;
    }
} else {
    header("Location: illegal.php");
}

header('Content-Type: application/json');
echo json_encode(["success" => $success,"invalid_course" => $invalid_course,"assignment_not_found" => $assignment_not_found,"error" => $error]);
?>
