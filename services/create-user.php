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

?>