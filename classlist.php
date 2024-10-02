<?php session_start(); ?>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-classlist.css">
    <title>Classlist</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body>
    <?php include("loader.php"); ?>
    <div class="mainapp">
        <?php include("header.php"); ?>
        <div class="title">
            <h2>
                Classlist
            </h2>
        </div>
        <div class="table_professor">
            <table class="responsive-table">
                <tr>
                    <td><img src="img/profilepics/batman_profilepic.jpg" class="profile prof-img"></td>
                    <td>
                        <div class=profname>
                            <h4>Professor: Dr. John Doe</h4>
                        </div>
                        <div class=profinfo>
                            <p> Email: examplegmail.com </p>
                            <p> Office Hours: 1:00 PM - 3:00 PM </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="table_students">
            <table class="highlight centered responsive-table">
                <thead>
                    <th></th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="img/profilepics/batman_profilepic.jpg" class="profile student-img"></td>
                        <td>Bat</td>
                        <td>Man</td>
                        <td>theman@marvel.com</td>
                        <td>12345678</td>
                    </tr>
                    <tr>
                        <td><img src="img/profilepics/Superman.png" class="profile student-img"></td>
                        <td>Super</td>
                        <td>Man</td>
                        <td>superman@marvel.com</td>
                        <td>1234444678</td>
                    </tr>
            </table>
        </div>

        <?php include("footer.php"); ?>
    </div>
</body>

<script src="js/theme.js"></script>
<script>
    $(window).on("load", () => {
        $("div.loader").fadeOut(200); // Hide the loader
        setTimeout(() => {
            $("div.mainapp").fadeIn(200); // Show the main app after a short delay
        }, 200);
    });
</script>