<?php

require '../config.php';
header('Content-Type: application/json');

// Verify parameters
if (empty($_POST["authorize"]) || empty($_POST["invite_code"]) || empty($_POST["id"])) {
    echo json_encode(["success" => 0, "error" => 1, "message" => "Missing or empty parameters."]);
    exit();
}

// Verify authorization
if ($_POST["authorize"] != "gradeplus") {
    header("Location: illegal.php");
    exit();
}

// Establish database connection
$conn = mysqli_connect($DB_HOST, "gradeplusclient", "gradeplussql", "gradeplus");
if (!$conn) {
    echo json_encode(["success" => 0, "error" => 1, "message" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

$course_code = mysqli_real_escape_string($conn, $_POST["invite_code"]);
$id = mysqli_real_escape_string($conn, $_POST["id"]);

// Delete the announcement
$sql = "
DELETE FROM announcements
WHERE announcement_id = '$id' AND course_code = '$course_code'
";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => 1, "error" => 0, "message" => "Announcement deleted successfully."]);
} else {
    echo json_encode(["success" => 0, "error" => 1, "message" => "Failed to delete announcement: " . mysqli_error($conn)]);
}

$conn->close();
