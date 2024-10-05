<?php

// Service to initialize/reset demo database. Handles creating MySQL user "gradeplusclient", creating "gradeplus" database, creating and filling "login" table.
if ($_POST["authorize"] == "gradeplus") {
    try {
        // Initialize/Reset Demo Database
        // Connect to MySQL as admin
        $conn = mysqli_connect('localhost', 'root', '');
        if (!$conn) {
            error_log("Connection to MySQL as admin failed: " . mysqli_connect_error());
        }

        // Check if gradeplusclient user exists
        $checkUserSql = "SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = 'gradeplusclient')";
        $result = mysqli_query($conn, $checkUserSql);
        $row = mysqli_fetch_array($result);
        // Create user and give privileges if it does not exist
        if ($row[0] == 0) {
            $createUserSql = "CREATE USER 'gradeplusclient'@'localhost' IDENTIFIED BY 'gradeplussql'";
            $result = mysqli_query($conn, $createUserSql);
            if (!$result) {
                error_log("Create user query failed: " . mysqli_error($conn));
            }

            $grantPrivilegesSql = "GRANT ALL PRIVILEGES ON gradeplus.* TO 'gradeplusclient'@'localhost';";
            $result = mysqli_query($conn, $grantPrivilegesSql);
            if (!$result) {
                error_log("Grant privileges query failed: " . mysqli_error($conn));
            }
            $result = mysqli_query($conn, "FLUSH PRIVILEGES");
            if (!$result) {
                error_log("Flush privileges query failed: " . mysqli_error($conn));
            }
        }

        //Close admin connection
        mysqli_close($conn);

        // Create gradeplusclient connection
        $conn = mysqli_connect('localhost', 'gradeplusclient', 'gradeplussql');
        if (!$conn) {
            error_log("Connection to MySQL as gradeplusclient failed: " . mysqli_connect_error());
        }

        // Create database if it does not exist
        $createDbSql = "CREATE DATABASE IF NOT EXISTS gradeplus";
        $result = mysqli_query($conn, $createDbSql);
        mysqli_select_db($conn, 'gradeplus');
        if (!$result) {
            error_log("Create database query failed: " . mysqli_error($conn));
        }

        // Drop table if it exists
        $resetTableSql = "DROP TABLE IF EXISTS login;";
        $result = mysqli_query($conn, $resetTableSql);
        if (!$result) {
            error_log("Drop table query failed: " . mysqli_error($conn));
        }

        // Create table
        $createTableSql = "
        CREATE TABLE login (
            username VARCHAR(50) PRIMARY KEY,
            email VARCHAR(50),
            password VARCHAR(50),
            dname VARCHAR(50),
            loggedin INT,
            profilePicture LONGBLOB
        );";
        $result = mysqli_query($conn, $createTableSql);
        if (!$result) {
            error_log("Create table query failed: " . mysqli_error($conn));
        }

        // Insert dummy data
        $insertDataSql = "
        INSERT INTO login (username, email, password, dname, loggedin) VALUES
        ('demo', 'demo@gradeplus.com', 'demo', 'Demo', 0),
        ('admin', 'admin@gradeplus.com', 'admin', 'Administrator', 0);
        ";
        $result = mysqli_query($conn, $insertDataSql);
        if (!$result) {
            error_log("Insert dummy data query failed: " . mysqli_error($conn));
        }

        $success = 1;
        $error = 0;
    } catch (Exception $e) {
        // SQL error
        $success = 0;
        $error = 1;
    }

    mysqli_close($conn);
    header('Content-Type: application/json');
    echo json_encode(["success" => $success,"error" => $error,"illegal" => 0]);
} else {
    // User is not authorized
    header("Location: illegal.php");
}
