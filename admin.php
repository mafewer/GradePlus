<?php
session_start();
if (!isset($_SESSION['logtime']) || $_SESSION['logtime'] < time()) {
    session_unset();
}
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
}
?>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-admin.css">
    <title>Administrator Dashboard</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body>
    <?php include("loader.php"); ?>
    <div class="mainapp">
        <?php include("header.php"); ?>
        <img src="img/adminback.png" class="indexback">
        <img src="img/adminbackdark.png" class="indexback2">
        <img src="img/admin.svg" class="adminback">
        <div class="admin-holder bwcolortext">
            <h2>
                Administrator Dashboard
            </h2>
            <br>
            <div class="section">
                <div class="icon-holder">
                    <i class="material-icons">build</i>
                    <h5>Reset Demo</h5>
                </div>
                <p>
                    Resets the localhost database to its default state. Use this to also initialize the
                    database for the first time.
                </p>
                <a class="waves-effect green waves-light btn reset-demo">RESET DEMO</a>
            </div>
            <br>
            <div class="section">
                <div class="icon-holder">
                    <i class="material-icons">code</i>
                    <h5>Custom SQL</h5>
                </div>
                <p>
                    Warning - Custom SQL commands executes directly in the back-end. Please be
                    careful!
                </p>
                <div class="row" style="margin: 0;">
                    <div class="input-field col s6" style="padding: 0;">
                        <textarea id="textarea1" class="materialize-textarea bwcolortext"></textarea>
                        <label style="left: 0;" for="textarea1">Command</label>
                    </div>
                </div>
                <a class="waves-effect green waves-light btn send-sql">SEND</a>
            </div>
        </div>
        <div class="modal white-text">
            <div class="modal-content">
            </div>
            <div class="modal-footer">
                <a class="white-text modal-close waves-effect waves-green btn-flat">OK</a>
            </div>
        </div>
        <?php include("footer.php"); ?>
    </div>
</body>
<script src="js/admin.js"></script>
<script src="js/theme.js"></script>

</html>
</body>