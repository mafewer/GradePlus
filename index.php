<?php session_start(); ?>
<html>


<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>GradePlus - Home</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <main>
        <div class="container">
            <div class="holder col s12">
                <h1 class="welcome">
                    Welcome to GradePlus!
                </h1>
                <div class="flow-text">
                    View your grades and peer reviews at one glance!
                </div>
                <br>
                <a href="login.php" class="waves-effect waves-light btn-large"
                    style="margin: 10rem; text-align: center;">
                    Get Started <i class="material-icons right">arrow_forward</i>
                </a>
            </div>
        </div>
    </main>
    <?php include("footer.php"); ?>
</body>

<script src="js/theme.js"></script>

</html>