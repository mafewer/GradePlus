<?php

require '../config.php';

$success = 0;
$invalid_course = 0;
$error = 0;

if ($_POST["authorize"] == "gradeplus") {
    try {
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $course_code = $_POST['course_code'];
            $assignment_name = $_POST['assignment_name'];
            $description = $_POST['description'];
            $due_date = $_POST['due_date'];
            $instructor = $_POST['instructor'];

            // File upload handling
            if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == UPLOAD_ERR_OK) {
                // Read the file content
                $file_dir = "../assignments/";
                $file_temp_path = $_FILES['assignment_file']['tmp_name'];
                $file_name = basename($_FILES['assignment_file']['name']);
                $upload_dir = $file_dir . $file_name;
                // Upload image to /img directory
                if (!move_uploaded_file($file_temp_path, $upload_dir)) {
                    throw new Exception("Failed to upload file to assignments directory");
                }
                //$file_content = file_get_contents($file_temp_path);
                //$file_content = mysqli_real_escape_string($conn, $file_content);
            } else {
                $file_content = null;
            }

            // Verify if the instructor owns the course
            $verifyInstructorSql = "SELECT * FROM courses WHERE course_code = ? AND instructor_name = ?";
            $stmt = $conn->prepare($verifyInstructorSql);
            $stmt->bind_param("ss", $course_code, $instructor);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Instructor owns the course, execute SQL query
                $stmt->close();

                // Prepare the SQL statement with explicit assignment_id handling
                $stmt = $conn->prepare("
                    INSERT INTO assignment (course_code, assignment_name, assignment_file, description, due_date, instructor, assignment_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                // Get the next available assignment_id (auto-increment equivalent)
                $result = $conn->query("SELECT MAX(assignment_id) AS max_id FROM assignment");
                $row = $result->fetch_assoc();
                $next_id = ($row['max_id'] !== null) ? $row['max_id'] + 1 : 1;

                // Bind parameters including assignment_id
                $stmt->bind_param("ssssssi", $course_code, $assignment_name, $upload_dir, $description, $due_date, $instructor, $next_id);

                if ($stmt->execute()) {
                    $success = 1;

                    $enrolledStudentSql = $conn->prepare("
                    SELECT login.username, dname FROM login
                    INNER JOIN enrollment ON login.username = enrollment.username
                    WHERE enrollment.course_code = ?
                    ");
                    $enrolledStudentSql->bind_param("s", $course_code);
                    $enrolledStudentSql->execute();
                    $studentsResult = $enrolledStudentSql->get_result();

                    // Insert each student's grade entry for the new assignment
                    while ($student = $studentsResult->fetch_assoc()) {
                        $username = $student['username'];
                        $display_name = $student['dname'];
                        $initial_grade = 0;
                        $max_grade = 10;
                        $feedback = "Pending";
                        $insertGradeSql = $conn->prepare("
                            INSERT INTO grades (assignment_id, course_code, assignment_name, username, grade, max_grade, feedback)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ");
                        $insertGradeSql->bind_param("isssiis", $next_id, $course_code, $assignment_name, $username, $initial_grade, $max_grade, $feedback);
                        $insertGradeSql->execute();
                        $insertGradeSql->close();
                    }

                    $enrolledStudentSql->close();

                } else {
                    $error = 1;
                }
            } else {
                $invalid_course = 1;
            }
            $stmt->close();
        }
        $conn->close();

    } catch (Exception $e) {
        $success = 0;
        $error = $e->getMessage();
    }
} else {
    header("Location: illegal.php");
}

header('Content-Type: application/json');
echo json_encode(["success" => $success,"invalid_course" => $invalid_course,"error" => $error]);
