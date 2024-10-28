<?php

require '../config.php';

if ($_POST["authorize"] == "gradeplus") {
    try {
        // Create a new MySQLi connection
        $conn = new mysqli($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");

        // If the connection fails, throw an exception
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Get the review_id from the POST request
        $review_id = $_POST['review_id'];

        // Prepare the SQL query to retrieve the review based on review_id
        $retrieveSql = $conn->prepare("
            SELECT 
                reviewer, 
                reviewee, 
                assignment_id, 
                assignment_name, 
                review 
            FROM reviews 
            WHERE review_id = ?
        ");

        // Bind the review_id to the query
        $retrieveSql->bind_param("i", $review_id);

        // Execute the query
        $result = $retrieveSql->execute();

        // Check if the query was successful
        if ($result) {
            $retrieveSql->store_result();

            // Bind the result columns to PHP variables
            $retrieveSql->bind_result($reviewer, $reviewee, $assignment_id, $assignment_name, $review);

            // Fetch the review data if it exists
            if ($retrieveSql->fetch()) {
                $reviewData = [
                    "reviewer" => $reviewer,
                    "reviewee" => $reviewee,
                    "assignment_id" => $assignment_id,
                    "assignment_name" => $assignment_name,
                    "review" => $review
                ];
                $success = 1;
                $error = 0;
            } else {
                // No review found with the provided review_id
                $reviewData = null;
                $success = 0;
                $error = 1;
            }
        } else {
            // Query execution failed
            $reviewData = null;
            $success = 0;
            $error = 1;
        }

        // Close the prepared statement
        $retrieveSql->close();
    } catch (Exception $e) {
        // Handle exceptions
        error_log("Error: " . $e->getMessage());
        $reviewData = null;
        $success = 0;
        $error = 1;
    }

    // Close the MySQLi connection
    $conn->close();

    // Send a JSON response back to the client
    header('Content-Type: application/json');
    echo json_encode([
        "success" => $success,
        "error" => $error,
        "review" => $reviewData
    ]);
} else {
    // Redirect the user to the illegal access page
    header("Location: illegal.php");
}
