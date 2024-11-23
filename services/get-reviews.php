<?php

require '../config.php';
header('Content-Type: application/json');

// Verify parameters
if (!isset($_POST["authorize"]) || !isset($_POST["assignment_id"]) || !isset($_POST["assignment_name"]) || !isset($_POST["username"])) {
    echo json_encode(["success" => 0, "error" => 1, "data" => [], "message" => "Missing or empty parameters."]);
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

$assignment_id = $_POST["assignment_id"];
$assignment_name = $_POST["assignment_name"];
$username = $_POST["username"];

// Retrieve reviews
$get_reviews_sql = sprintf(
    "SELECT DISTINCT review FROM reviews WHERE assignment_id = '%s' AND assignment_name = '%s'AND student = '%s'",
    mysqli_real_escape_string($conn, $assignment_id),
    mysqli_real_escape_string($conn, $assignment_name),
    mysqli_real_escape_string($conn, $username)
);

$result = mysqli_query($conn, $get_reviews_sql);

if ($result) {
    $success = 1;
    $error = 0;
    // Fetch data and build the response
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row['review'];
    }
} else {
    $success = 0;
    $error = 1;
    $reviews = [];
}

// Close the connection
$conn->close();

// Respond with data
echo json_encode([
    "success" => $success,
    "error" => $error,
    "data" => $reviews
]);
