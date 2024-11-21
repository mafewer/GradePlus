<?php

require "../config.php";

// Start the session
session_start();
// Check if the authorization token is correct
if ($_POST['authorize'] == 'gradeplus') {
    try {
        // Connect to the MySQL database
        $conn = mysqli_connect($DB_HOST, 'gradeplusclient', 'gradeplussql', 'gradeplus');
        if (!$conn) {
            // Log an error if the connection fails
            error_log("Connection to MySQL as gradeplusclient failed: " . mysqli_connect_error());
        }

        // Prepare the SQL statement to update the loggedin status
        $sqlUpdate = $conn->prepare("UPDATE login SET loggedin = ? WHERE username = ?");
        $loggedin = 0; // Set loggedin status to 0 (logged out)
        $username = $_SESSION['username']; // Get the username from the session

        // Bind the parameters to the SQL statement
        $sqlUpdate->bind_param("is", $loggedin, $username);

        // Execute the SQL statement
        $sqlUpdate->execute();

        // Unset session variables
        session_unset();

        $success = 1; // Indicate success
    } catch (Exception $e) {
        $success = 0; // Indicate failure
    }

    // Close the database connection
    mysqli_close($conn);

    header('Content-Type: application/json');
    echo json_encode(["success" => $success]);
} else {
    // User is not authorized, redirect to the illegal access page
    header("Location: illegal.php");
}
