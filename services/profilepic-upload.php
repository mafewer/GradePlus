<?php
$success = 0;
$error = 0;
$missingFeild = 0;
try {
    $conn = mysqli_connect('localhost', 'gradeplusclient', 'gradeplussql', 'gradeplus');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST['username'];
        

        if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
            $profilePicture = $_FILES['profilePicture']['tmp_name'];


            $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->close();

                $imageData = file_get_contents($profilePicture);

                $stmt = $conn->prepare("SELECT * FROM login WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows == 0) {

                    $stmt->close();
                    $stmt = $conn->prepare("INSERT INTO login (username, profilePicture) VALUES (?, ?)");
                    $stmt->bind_param("sb", $username, $imageData);
                    $stmt->send_long_data(1, $imageData); 

                    if ($stmt->execute()) {
                        echo "Profile picture uploaded successfully!";
                    } else {
                        echo "Error uploading profile picture: " . $stmt->error;
                    }
                } else {

                    $stmt->close();
                    $stmt = $conn->prepare("UPDATE login SET profilePicture = ? WHERE username = ?");
                    $stmt->bind_param("bs", $imageData, $username);
                    $stmt->send_long_data(0, $imageData); 

                    if ($stmt->execute()) {
                        $success = 1;
                    } else {
                        $error = 1;
                    }
                }
            }

            $stmt->close();
        } else {
            $missingFeild = 0;
        }
    }

    $conn->close();
} catch (exception $e) {
    $error = 1;
}
    header('Content-Type: application/json');
    echo json_encode(["success" => $success,"missingFeild" => $missingFeild,"error" => $error]);
?>


