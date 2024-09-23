<?php session_start(); ?>
<html>
<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>GradePlus - Dashboard</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <div class="container">
        <h2>
            Welcome
            <?php echo $_SESSION['dname']; ?>!
        </h2>
        <div class="flow-text">
            <?php echo $_SESSION['email']; ?>
        </div>
    </div>
</body>
<script src="js/theme.js"></script>