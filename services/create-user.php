<?php

header('Content-Type: application/json');

// Shared secret for authroization
$sharedSecret = "gradeplus";

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: illegal.php");
    exit();
}

// Get POST data
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$authorize = $_POST['authorize'] ?? null;

// Check authroization
if ($authorize != $sharedSecret){
    header("Location: illegal.php");
    exit();
}

// Database connection
$servername = "localhost";          
$usernameDB = "gradeplusclient";   
$passwordDB = "gradeplussql"; 
$dbname = "gradeplus";

// Connect to the database
$conn = new mysqil($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error){
    echo json_encode(["success" => 0, "exists" => 0, "error" => 1]);
    exit();
}

// Check if username already exists
$sql = $conn->prepare("SELECT id from users WHERE username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$sql->store_result();

if ($sql->num_rows > 0){
    echo json_encode(["success" => 0, "exists" => 1, "error" => 0]);
} else {
    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertSql = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insertSql->bind_param("sss", $username, $email, $hashedPassword);

    if ($insertSql->execute()){
        echo json_encode(["success" => 1, "exists" => 0, "error" => 0]);
    } else {
        echo json_encode(["success" => 0, "exists" => 0, "error" => 1]);
    }

    $insertSql->close();
}

$sql->close();
$conn->close();

?>