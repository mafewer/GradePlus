<?php
session_start();
$success = 0;
$error = 0;
if (isset($_SESSION['logtime']) && isset($_SESSION['username'])) {
    if ($_SESSION['logtime'] > time()) {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];
            $_SESSION['logtime'] = time() + (60 * 6);
            try {
                $conn = mysqli_connect('localhost', 'gradeplusclient', 'gradeplussql', 'gradeplus');
                $loggedin = 1;
                $sqlUpdate = $conn->prepare("UPDATE login SET loggedin = ? WHERE username = ?");
                $sqlUpdate->bind_param("is", $loggedin, $username);
                $sqlUpdate->execute();
                $success = 1;
                $sqlUpdate->close();
                $conn->close();
            } catch (exception $e) {
                $error = 1;
            }
        }
    } else {
        header('Location: login.php');
        session_unset();
    }
} else {
    header('Location: login.php');
    session_unset();
}
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>GradePlus - Dashboard</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <?php include("loader.php"); ?>
    <div class="mainapp">
        <?php include("header.php"); ?>
        <div class="container">
            <h2>
                Welcome
                <?php echo $_SESSION['dname']; ?>!
            </h2>
            <div class="flow-text">
                <?php echo $_SESSION['email']; ?>
            </div>
        </div>
    </div>
</body>
<script>
    $(window).on("load", () => {
        $("div.loader").fadeOut(200); // Hide the loader
        setTimeout(() => {
            $("div.mainapp").fadeIn(200); // Show the main app after a short delay
        }, 200);
    });
</script>
<script src="js/theme.js"></script>