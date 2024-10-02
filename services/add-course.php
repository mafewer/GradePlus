<?php

// Check if the authorization key matches the shared secret
if ($_POST["authorize"] == "gradeplus") {
    try {
        // Create a new MySQLi connection 
        $conn = new mysqli("localhost", "gradeplusclient", "gradeplussql", "gradeplus");

        // If the connection fails, throw an exception
        if (!$conn) {
            throw new Exception();
        }

        // Generate a unique course code by hashing the course name and instructor name
        // and taking the first 10 characters of the resulting SHA-256 hash
        $course_code = substr(hash('sha256', $_POST['coursename'] . $_POST['instructorname']), 0, 10);

        // Prepare an SQL query to insert the new course into the 'courses' table
        $addCourseSql = $conn->prepare("
            INSERT INTO courses (course_code, course_name, course_banner, instructor_name)
            VALUES (?, ?, ?, ?)
        ");

        // Bind the values to the prepared statement
        $addCourseSql->bind_param("ssss", $course_code, $_POST['coursename'], $_POST['banner'], $_POST['instructorname']);

        // Execute the prepared SQL statement
        $result = $addCourseSql->execute();

        // Check if the execution was successful
        if (!$result) {
            $success = 0; 
            $error = 1;   
        } else {
            $success = 1; 
            $error = 0;   
        }

    } catch (Exception $e) {
        $success = 0;
        $error = 1;
    }

    // Close the MySQLi connection
    mysqli_close($conn);

    // Send a JSON response back to the client with the success and error status
    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error]);

} else {
    // If authorization fails, redirect the user to the 'illegal.php' page
    header("Location: illegal.php");
}
