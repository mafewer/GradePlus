<?php session_start(); ?>
<html>


<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>GradePlus</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
<?php include("header.php"); ?>
<main>
    <div class="container">
        <div class="c1">
            <h1>
                Welcome to GradePlus!
            </h1>
            <div class="flow-text">
                View your grades and peer reviews at one glance!
            </div>
            <br>
            <div class="divider"></div>
            <a href="login.php" class="waves-effect waves-light btn-large" style="margin: 10rem; text-align: center;">
                Get Started <i class="material-icons right">arrow_forward</i>
            </a>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>
</body>

<script src="js/theme.js"></script>

</html>