<?php

require '../config.php';

session_start();
ini_set('display_errors', 0);

// Service to delete user account
if ($_POST["authorize"] == "gradeplus") {
    if (!isset($_SESSION['username']) || $_SESSION['username'] == 'admin') {
        header('Location: ../login.php');
    }
    try {
        $currentName = $_SESSION['username'];
        $conn = mysqli_connect($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");
        if (!$conn) {
            error_log("SQL connection failed: " . mysqli_connect_error());
        }

        // Check if user exists
        $checkUserSql = sprintf("SELECT 1 FROM login WHERE username = '%s'", $currentName);
        $result = mysqli_query($conn, $checkUserSql);
        $row = mysqli_fetch_array($result);

        if (!$result) {
            error_log("User exists check failed: " . mysqli_error($conn));
        }

        if ($row[0] != 0) {
            // Check if user is an instructor
            $checkInstructorSql = sprintf("SELECT 1 FROM login WHERE username = '%s' AND usertype = 'Instructor'", $currentName);
            $result = mysqli_query($conn, $checkInstructorSql);
            $row = mysqli_fetch_array($result);

            if ($row == null) {
                $row = [0];
            }

            if ($row[0] != 0) {
                $getInviteCodesSql = sprintf("SELECT invite_code FROM courses WHERE instructor_name = '%s'", $currentName);
                $result = mysqli_query($conn, $getInviteCodesSql);

                if ($result) {
                    $inviteCodes = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $inviteCodes[] = $row['invite_code'];
                    }

                    if (!empty($inviteCodes)) {
                        $inviteCodesList = "'" . implode("','", $inviteCodes) . "'";

                        $deleteEnrollmentSql = sprintf("DELETE FROM enrollment WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteEnrollmentSql);

                        $deleteCoursesSql = sprintf("DELETE FROM courses WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteCoursesSql);

                        $deleteCoursesSql = sprintf("DELETE FROM announcements WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteCoursesSql);

                        $deleteCoursesSql = sprintf("DELETE FROM assignment WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteCoursesSql);

                        $deleteCoursesSql = sprintf("DELETE FROM grades WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteCoursesSql);

                        $deleteCoursesSql = sprintf("DELETE FROM reviews WHERE invite_code IN (%s)", $inviteCodesList);
                        mysqli_query($conn, $deleteCoursesSql);
                    }
                }
            }

            // Delete user from login
            $deleteUserSql = sprintf("DELETE FROM login WHERE username = '%s'", $currentName);
            $result = mysqli_query($conn, $deleteUserSql);

            $deleteUserSql = sprintf("DELETE FROM enrollment WHERE username = '%s'", $currentName);
            $result = mysqli_query($conn, $deleteUserSql);

            $deleteUserSql = sprintf("DELETE FROM grades WHERE username = '%s'", $currentName);
            $result = mysqli_query($conn, $deleteUserSql);

            $deleteUserSql = sprintf("DELETE FROM reviews WHERE student = '%s'", $currentName);
            $result = mysqli_query($conn, $deleteUserSql);


            if ($result) {
                $success = 1;
                $error = 0;
                session_unset();
            } else {
                error_log("Delete user failed: " . mysqli_error($conn));
                $error = 1;
            }
        }
    } catch (Exception $e) {
        // SQL error
        $success = 0;
        $error = 1;
    }
    mysqli_close($conn);
    header('Content-Type: application/json');
    echo json_encode(["success" => $success,"error" => $error]);
} else {
    // User is not authorized
    header("Location: illegal.php");
}
