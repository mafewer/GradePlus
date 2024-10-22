<?php

require '../config.php';

// Service to initialize/reset demo database. Handles creating MySQL user "gradeplusclient", creating "gradeplus" database, creating and filling "login" table.
if ($_POST["authorize"] == "gradeplus") {
    try {
        // Initialize/Reset Demo Database
        // Connect to MySQL as admin
        $conn = mysqli_connect('127.0.0.1', 'root', '');
        if (!$conn) {
            error_log("Connection to MySQL as admin failed: " . mysqli_connect_error());
        }

        // Check if gradeplusclient user exists
        $checkUserSql = "SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = 'gradeplusclient')";
        $result = mysqli_query($conn, $checkUserSql);
        $row = mysqli_fetch_array($result);
        // Create user and give privileges if it does not exist
        if ($row[0] == 0) {
            $createUserSql = "CREATE USER 'gradeplusclient'@$DB_HOST IDENTIFIED BY 'gradeplussql'";
            $result = mysqli_query($conn, $createUserSql);
            if (!$result) {
                error_log("Create user query failed: " . mysqli_error($conn));
            }

            $grantPrivilegesSql = "GRANT ALL PRIVILEGES ON gradeplus.* TO 'gradeplusclient'@$DB_HOST;";
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
        $conn = mysqli_connect($DB_HOST, 'gradeplusclient', 'gradeplussql');
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
            profilePicture LONGBLOB,
            usertype VARCHAR(20) NOT NULL DEFAULT 'Student'
        );";
        $result = mysqli_query($conn, $createTableSql);
        if (!$result) {
            error_log("Create table query failed: " . mysqli_error($conn));
        }

        // Insert dummy data
        $insertDataSql = "
        INSERT INTO login (username, email, password, dname, loggedin, profilePicture, usertype) VALUES
        ('demo', 'demo@gradeplus.com', 'demo', 'Demo', 0, NULL, 'Student'),
        ('admin', 'admin@gradeplus.com', 'admin', 'Administrator', 0, NULL, 'Admin'),
        ('instructor', 'instructor@gradeplus.com', 'instructor', 'Instructor', 0, NULL, 'Instructor'),
        ('student', 'student@gradeplus.com', 'student', 'Student', 0, NULL, 'Student'),
        ('mafewer', 'mafewer@mun.ca', 'password0','mafewer','0',NULL,'Student'),
        ('aaabdulghani', 'aaabdulghani@mun.ca', 'password1','aaabdulghani','0',NULL,'Student'),
        ('jfbrown', 'jfbrown@mun.ca', 'password2','jfbrown','0',NULL,'Student'),
        ('ddolomount', 'ddolomount@mun.ca', 'password3','ddolomount','0',NULL,'Student'),
        ('mmmybelshaba', 'mmmybelshaba@mun.ca', 'password4','mmmybelshaba','0',NULL,'Student'),
        ('sthillier', 'sthillier@mun.ca', 'password5','sthillier','0',NULL,'Student'),
        ('asamanta', 'asamanta@mun.ca', 'password6','asamanta','0',NULL,'Student'),
        ('brjthomas', 'brjthomas@mun.ca', 'password7','brjthomas','0',NULL,'Student'),
        ('karami', 'karami@mun.ca', 'password8','karami','0',NULL,'Instructor'),
        ('huang', 'huang@mun.ca', 'password9','huang','0',NULL,'Instructor'),
        ('abbas', 'abbas@mun.ca', 'password10','abbas','0',NULL,'Instructor'),
        ('nasiri', 'nasiri@mun.ca', 'password11','nasiri','0',NULL,'Instructor'),
        ('meruviaPastor', 'meruviapastor@mun.ca', 'password12','meruviapastor','0',NULL,'Instructor');  
        ";
        $result = mysqli_query($conn, $insertDataSql);
        if (!$result) {
            error_log("Insert dummy data query failed: " . mysqli_error($conn));
        }

        // Drop enrollment table if it exists
        $resetTableSqlEnrollment = "DROP TABLE IF EXISTS enrollment;";
        $result = mysqli_query($conn, $resetTableSqlEnrollment);
        if (!$result) {
            error_log("Drop table query failed: " . mysqli_error($conn));
        }

        $createTableSqlEnrollment = "
        CREATE TABLE enrollment (
            username VARCHAR(50),
            course_code VARCHAR(50),
            course_name VARCHAR(50),
            pinned INT,
            invite_code VARCHAR(50),
            instructor VARCHAR(50)
        );";
        $result = mysqli_query($conn, $createTableSqlEnrollment);
        if (!$result) {
            error_log("Create table query failed: " . mysqli_error($conn));
        }

        // Insert dummy data
        $insertDataSqlEnrollment = "
        INSERT INTO enrollment VALUES
        ('student', 'ECE 6400', 'Software Development', 1 , 'ABCDEF', 'student'),
        ('instructor', 'ECE 6400', 'Software Development', 1 , 'ABCDEF', 'instructor'),

        ('mafewer', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('aaabdulghani', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('jfbrown', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('ddolomount', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('mmmybelshaba', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('sthillier', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('asamanta', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),
        ('brjthomas', 'ECE 6610', 'Communication Networks', 0, 'GHIJKL', 'Ebrahim Karami'),

        ('mafewer', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('aaabdulghani', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('jfbrown', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('ddolomount', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('mmmybelshaba', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('sthillier', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('asamanta', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),
        ('brjthomas', 'ECE 6600', 'Communication Principles', 0, 'GHIJKQ', 'Weimin Huang'),

        ('mafewer', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('aaabdulghani', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('jfbrown', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('ddolomount', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('mmmybelshaba', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('sthillier', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('asamanta', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),
        ('brjthomas', 'ECE 6400', 'Software Devl Practice', 0, 'GHIJKW', 'Raja Abbas'),

        ('mafewer', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('aaabdulghani', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('jfbrown', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('ddolomount', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('mmmybelshaba', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('sthillier', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('asamanta', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),
        ('brjthomas', 'ECE 6500', 'Computer Architecture', 0, 'ABCDEF', 'Hamed Nasiri'),

        ('mafewer', 'CS 3301', 'Visual Computing and Applications', 0, 'GHIJKE', 'Oscar Meruvia-Pastor'),
        ('ddolomount', 'CS 3301', 'Visual Computing and Applications', 0, 'GHIJKE', 'Oscar Meruvia-Pastor');
        ";
        $result = mysqli_query($conn, $insertDataSqlEnrollment);
        if (!$result) {
            error_log("Insert dummy data query failed: " . mysqli_error($conn));
        }

        // Drop assignment table if it exists
        $resetTableSqlAssignment = "DROP TABLE IF EXISTS assignment;";
        $result = mysqli_query($conn, $resetTableSqlAssignment);
        if (!$result) {
            error_log("Drop table query failed: " . mysqli_error($conn));
        }
        
        $createTableSqlAssignment = "
        CREATE TABLE assignment (
            course_code VARCHAR(50),
            assignment_name VARCHAR(50),
            assignment_file LONGBLOB,
            description VARCHAR(50),
            due_date Date,
            instructor VARCHAR(50),
            assignment_id INT PRIMARY KEY
        );";
        $result = mysqli_query($conn, $createTableSqlAssignment);
        if (!$result) {
            error_log("Create table query failed: " . mysqli_error($conn));
        }

        // Insert dummy data
        $insertDataSqlAssignment = "
        INSERT INTO assignment (course_code, assignment_name, assignment_file, description, due_date, instructor, assignment_id) VALUES
        ('ECE 6400', 'A1', NULL , 'I am a description 1' , NULL, 'instructor', 0),
        ('ECE 6500', 'A1', NULL , 'I am a description 2' , NULL, 'Hammed', 1),
        ('ECE 6400', 'A2', NULL , 'I am a description 3' , NULL, 'instructor', 2),

        ('ECE 6610','A1',NULL,'Review of the notes from Chapter 1','2024-09-24', 'karami', 123456),
        ('ECE 6610','A2',NULL,'Questions from Chapter 2','2024-10-16', 'karami', 678910),
        ('ECE 6610','A3',NULL,'Question from Chapter 3','2024-10-25','karami', 111213),

        ('ECE 6500','A1',NULL,'Questions based on first section','2024-09-30', 'nasiri', 141516),
        ('ECE 6500','Abstract',NULL,'Abstract for Report','2024-10-18', 'nasiri', 171819),

        ('ECE 6600','A1',NULL,'Questions based on first section','2024-09-30', 'huang', 202122),

        ('ECE 6400','A1',NULL,'Questions based on first section','2024-09-26', 'abbas', 232425),

        ('CS 3301','A1',NULL,'Question based on Histogram Operations','2024-09-27', 'meruviapastor', 262728),
        ('CS 3301','A2',NULL,'Questions based on smoothing filters','2024-10-13', 'meruviapastor', 293031);
        ('ECE 6400', 'A1', NULL , 'I am a description 1' , NULL, 'instructor', 0),
        ('ECE 6500', 'A1', NULL , 'I am a description 2' , NULL, 'Hammed', 1),
        ('ECE 6400', 'A2', NULL , 'I am a description 3' , NULL, 'instructor', 2);
        ";
        $result = mysqli_query($conn, $insertDataSqlAssignment);
        if (!$result) {
            error_log("Insert dummy data query failed: " . mysqli_error($conn));
        }

        // Drop courses table if it exists
        $resetTableSql = "DROP TABLE IF EXISTS courses;";
        $result = mysqli_query($conn, $resetTableSql);
        if (!$result) {
            error_log("Drop courses table query failed: " . mysqli_error($conn));
        }

        // Create courses table
        $createTableSql = "
        CREATE TABLE courses (
            course_code VARCHAR(255) NOT NULL,
            course_name VARCHAR(255) NOT NULL,
            course_banner VARCHAR(255),
            instructor_name VARCHAR(255) NOT NULL,
            instructor_dname VARCHAR(255) NOT NULL,
            invite_code VARCHAR(10) PRIMARY KEY
        );";

        $result = mysqli_query($conn, $createTableSql);
        if (!$result) {
            error_log("Failed to create courses table: " . mysqli_error($conn));
        }

        // Insert dummy data
        $insertDataSqlCourses = "
                INSERT INTO courses VALUES
                ('ECE 6400', 'Software Development', '../img/card.jpg', 'instructor', 'Instructor', 'ABCDEF'),
                ('ECE 6610', 'Communication Networks', '../img/card.jpg', 'Ebrahim Karami', 'karami', 'GHIJKL'),
                ('ECE 6600', 'Communication Principles', '../img/card.jpg', 'Weimin Huang', 'huang', 'GHIJKQ'),
                ('ECE 6400', 'Software Devl Practice', '../img/card.jpg', 'Raja Abbas', 'abbas', 'GHIJKW'),
                ('CS 3301', 'Visual Computing and Applications', '../img/card.jpg', 'Oscar Meruvia-Pastor', 'meruviapastor', 'GHIJKE'),
                ('ECE 6500', 'Computer Architecture', '../img/card.jpg', 'Hamed Nasiri', 'nasiri', 'ABCDEG');
                ";
        $result = mysqli_query($conn, $insertDataSqlCourses);
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
