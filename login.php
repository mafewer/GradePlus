<?php
session_start();
$success = 0;
$incorrect = 0;
$error = 0;
$empty = 0;

//Redirect to account page if already logged in
if (isset($_SESSION['logtime']) && isset($_SESSION['username'])) {

    if ($_SESSION['logtime'] > time()) {
        if ($_SESSION['username'] == 'admin' || $_SESSION['email'] == "admin@gradeplus.com") {
            header('Location: admin.php');
        } else {
            header('Location: account.php');
        }
    } else {
        unset($_SESSION['username']);
        unset($_SESSION['logtime']);
        unset($_SESSION['dname']);
    }
}

//Empty check
if (isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    if ((empty($username) || empty($email)) && empty($password)) {
        $empty = 1;
    } else {
        //SQL Connection
        try {
            $conn = mysqli_connect('localhost', 'gradeplusclient', 'gradeplussql', 'gradeplus');


            $sqlCommand = $conn->prepare("SELECT username, dname, email FROM login WHERE (username = ? OR email = ?) AND password = ?");
            $sqlCommand->bind_param("sss", $username, $email, $password);

            if ($sqlCommand->execute()) {
                $sqlCommand->store_result();
                if ($sqlCommand->num_rows > 0) {
                    $sqlCommand->bind_result($username, $dname, $email);
                    $sqlCommand->fetch();

                    $_SESSION['logtime'] = time() + (60 * 6);
                    $_SESSION['username'] = $username;
                    $_SESSION['dname'] = $dname;
                    $_SESSION['email'] = $email;
                    $loggedin = 1;
                    $sqlUpdate = $conn->prepare("UPDATE login SET loggedin = ? WHERE username = ?");
                    $sqlUpdate->bind_param("is", $loggedin, $username);
                    $sqlUpdate->execute();
                    if ($username == "admin" || $email == "admin@gradeplus.com") {
                        header('Location: admin.php');
                    } else {
                        header('Location: account.php');
                    }
                    $success = 1;
                } else {
                    $incorrect = 1;
                }
            }

            $sqlCommand->close();
            $conn->close();

            //Error handling
        } catch (exception $e) {
            $error = 1;
        }
    }
}
?>
<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-login.css">
    <title>GradePlus - Login</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <div class="login-holder">
        <div class="login-box bwcolor">
            <h5>
                Login
            </h5>
            <div class="flow-text">
                <p class="status-text">
                    <?php
                        if ($success == 1) {
                            echo "";
                        } elseif ($incorrect == 1) {
                            echo "Incorrect username or password!";
                        } elseif ($error == 1) {
                            echo "500 - Server Error";
                        } elseif ($empty == 1) {
                            echo "Please fill in all fields!";
                        }
?>
                </p>
            </div>
            <form action="" method="POST">
                <div class="input-field" style="padding: 0;">
                    <i class="material-icons prefix">person</i>
                    <input id="username" name="username" type="text" class="white-text">
                    <label for="username">Username or Email</label>
                </div>
                <br>
                <div class="input-field" style="padding: 0;">
                    <i class="material-icons prefix">key</i>
                    <input id="password" name="password" type="password" class="white-text">
                    <label for="password">Password</label>
                </div>
            </form>
            <p>
                New to GradePlus? <a>Register</a>
            </p>
            <button class="waves-effect waves-light btn login-btn">
                <div class="icon-holder">
                    <i class="material-icons prefix">login</i>LOGIN
                </div>
            </button>
        </div>
</body>

</html>
<script src="js/theme.js"></script>
<script>
    $(".login-btn").click(function() {
        $(".login-btn").addClass("disabled").text("LOGGING IN");
        $("form")[0].submit();
    });
    if ($("p.status-text").text() != "") {
        $("p.status-text").slideDown();
        setTimeout(() => {
            $("p.status-text").slideUp();
        }, 3000);
    };

    $(document).keypress(function(e) {
        if (e.which == 13 && $("#password").is(":focus")) {
            $(".login-btn").click();
        }
    });
</script>