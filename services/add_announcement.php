<?php

require '../config.php';
header('Content-Type: application/json');

// Verify parameters
if (
    empty($_POST["authorize"]) ||
    empty($_POST["invite_code"]) ||
    empty($_POST["header"]) ||
    empty($_POST["text"])
) {
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
$header = mysqli_real_escape_string($conn, $_POST["header"]);
$text = mysqli_real_escape_string($conn, $_POST["text"]);
$date = date("Y-m-d"); // Current date

// Check if the same announcement already exists
$checkSql = "
SELECT 1 FROM announcements 
WHERE invite_code = '$course_code' AND header = '$header' AND text = '$text' AND date = '$date'
LIMIT 1
";
$checkResult = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($checkResult) > 0) {
    // Announcement already exists
    echo json_encode(["success" => 1, "error" => 0, "message" => "Duplicate announcement ignored."]);
} else {
    // Generate a new unique ID
    $result = mysqli_query($conn, "SELECT MAX(announcement_id) AS max_id FROM announcements");
    $row = mysqli_fetch_assoc($result);
    $new_id = $row["max_id"] ? intval($row["max_id"]) + 1 : 1;

    // Insert the new announcement
    $sql = "
    INSERT INTO announcements (announcement_id, invite_code, header, text, date)
    VALUES ('$new_id', '$course_code', '$header', '$text', '$date')
    ";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => 1, "error" => 0, "message" => "Announcement added successfully."]);
    } else {
        echo json_encode(["success" => 0, "error" => 1, "message" => "Failed to add announcement: " . mysqli_error($conn)]);
    }
}

$conn->close();
