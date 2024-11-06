<?php

require '../config.php';

// Start the session
session_start();
$success = 0; // Indicate failure
$error = 0;
// Check if authorization value is correct
if ($_POST['authorize'] == 'gradeplus') {
    try {
        // Connect to the MySQL database
        $conn = mysqli_connect($DB_HOST, 'gradeplusclient', 'gradeplussql', 'gradeplus');
        if (!$conn) {
            error_log("SQL connection failed: " . mysqli_connect_error());
        }
        // Prepare the SQL statement to delete the enrollment
        $sqlDelete = $conn->prepare("DELETE FROM enrollment WHERE username = ? AND invite_code = ?");
        $username = htmlspecialchars($_POST['username'] ?? '');
        $invite_code = htmlspecialchars($_POST['invite_code'] ?? '');
        // enter Paramters
        $sqlDelete->bind_param("ss", $username, $invite_code);

        // Execute the SQL command
        $sqlDelete->execute();

        // Check if any rows were affected
        if ($sqlDelete->affected_rows > 0) {
            $success = 1;
        } else {
            $success = 0;
        }
        mysqli_close($conn);

    } catch (Exception $e) {
        error_log("Error occurred during de-enrollment: " . $e->getMessage());
        $error = 1; // Server error
    }
    // Return the response in JSON format
    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "error" => $error ]);
} else {
    header("Location: illegal.php");
}
