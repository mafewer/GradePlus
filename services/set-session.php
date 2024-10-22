<?php

session_start();
// Helper service to set session values for testing
if (isset($_POST['key']) && isset($_POST['value'])) {
    $key = $_POST['key'];
    $value = $_POST['value'];
    $_SESSION[$key] = $value;
    echo json_encode(["success" => 1]);
}
header('Content-Type: application/json');
