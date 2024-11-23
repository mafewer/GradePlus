<?php

require '../config.php';
header('Content-Type: application/json');

// Verify parameters
if (empty($_POST["authorize"]) || empty($_POST["invite_code"])) {
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

// Retrieve announcements for the course
$sql = "
SELECT announcement_id, header, text, date
FROM announcements
WHERE invite_code = '$course_code'
ORDER BY date DESC
";

$result = mysqli_query($conn, $sql);

if ($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            "id" => $row["announcement_id"],
            "header" => $row["header"],
            "text" => $row["text"],
            "date" => $row["date"]
        ];
    }
    echo json_encode(["success" => 1, "error" => 0, "data" => $data]);
} else {
    echo json_encode(["success" => 0, "error" => 1, "message" => "Failed to fetch announcements: " . mysqli_error($conn)]);
}

$conn->close();
