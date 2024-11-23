<?php

header('Content-Type: application/json');
require '../config.php';

// Start the session
session_start();

// Check if the authorization token is correct
if ($_POST['authorize'] === 'gradeplus') {
    $conn = null;
    try {
        // Connect to the MySQL database using prepared statements
        header('Content-Type: application/json');
        $conn = new mysqli($DB_HOST, 'gradeplusclient', 'gradeplussql', 'gradeplus');
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Update the course code using a prepared statement
        if ($_POST['invitecode'] != null) {
            $inviteCode = $_POST['invitecode'];

            // Delete from courses
            $stmt = $conn->prepare("DELETE FROM courses WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from courses: " . $stmt->error);
            }
            $stmt->close();

            // Delete from announcements
            $stmt = $conn->prepare("DELETE FROM announcements WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from announcements: " . $stmt->error);
            }
            $stmt->close();

            // Delete from enrollment
            $stmt = $conn->prepare("DELETE FROM enrollment WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from enrollment: " . $stmt->error);
            }
            $stmt->close();

            // Delete from reviews
            $stmt = $conn->prepare("DELETE FROM reviews WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from reviews: " . $stmt->error);
            }
            $stmt->close();

            // Delete from grades
            $stmt = $conn->prepare("DELETE FROM grades WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from grades: " . $stmt->error);
            }
            $stmt->close();

            // Delete from assignment
            $stmt = $conn->prepare("DELETE FROM assignment WHERE invite_code = ?");
            $stmt->bind_param("s", $inviteCode);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete from assignment: " . $stmt->error);
            }
            $stmt->close();
        }

        // Close the connection
        $conn->close();

        // Return success response
        echo json_encode(['success' => 1, 'error' => 0]);
    } catch (Exception $e) {
        // Close the connection if it exists
        if ($conn) {
            $conn->close();
        }

        // Return error response with a message for debugging
        echo json_encode(['success' => 0, 'error' => 1]);
    }
} else {
    // Redirect to the illegal access page
    header("Location: illegal.php");
}
