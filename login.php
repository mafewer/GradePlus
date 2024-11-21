<?php
session_start();
$success = 0;
$incorrect = 0;
$error = 0;
$empty = 0;

require "config.php";

//Redirect to account page if already logged in
if (isset($_SESSION['logtime']) && isset($_SESSION['username'])) {

    if ($_SESSION['logtime'] > time()) {
        if ($_SESSION['username'] == 'admin' || $_SESSION['email'] == "admin@gradeplus.com") {
            header('Location: admin.php');
        } else {
            header('Location: account.php');
        }
    } else {
        session_unset();
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
            $conn = mysqli_connect($DB_HOST, 'gradeplusclient', 'gradeplussql', 'gradeplus');


            $sqlCommand = $conn->prepare("SELECT username, dname, email, usertype FROM login WHERE (username = ? OR email = ?) AND password = ?");
            $sqlCommand->bind_param("sss", $username, $email, $password);

            if ($sqlCommand->execute()) {
                $sqlCommand->store_result();
                if ($sqlCommand->num_rows > 0) {
                    $sqlCommand->bind_result($username, $dname, $email, $usertype);
                    $sqlCommand->fetch();

                    $_SESSION['logtime'] = time() + (60 * 6);
                    $_SESSION['username'] = $username;
                    $_SESSION['dname'] = $dname;
                    $_SESSION['email'] = $email;
                    $_SESSION['usertype'] = $usertype;
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
            error_log("Error during login process: " . $e->getMessage());
            // Display error in the browser console
            echo "<script>console.error('Error during login process: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-login.css">
    <title>GradePlus - Login</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <?php include("loader.php"); ?>
    <div class="mainapp">
        <?php include("header.php"); ?>
        <img src="img/loginback.png" class="indexback">
        <img src="img/loginbackdark.png" class="indexback2">
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
                            echo "Incorrect username or password";
                        } elseif ($error == 1) {
                            echo "500 - Server Error";
                        } elseif ($empty == 1) {
                            echo "Fields cannot be left blank";
                        }
?>
                    </p>
                </div>
                <form action="" method="POST">
                    <div class="input-field">
                        <i class="material-icons prefix">person</i>
                        <input id="username" name="username" type="text" class="white-text">
                        <label for="username">Username or Email</label>
                    </div>
                    <br>
                    <div class="input-field">
                        <i class="material-icons prefix">key</i>
                        <input id="password" name="password" type="password" class="white-text">
                        <label for="password">Password</label>
                    </div>
                </form>
                <p>
                    New to GradePlus? <a class="switch">Register</a>
                </p>
                <button class="waves-effect waves-light green btn login-btn">
                    <div class="icon-holder">
                        <i class="material-icons prefix">login</i>LOGIN
                    </div>
                </button>
            </div>
            <div class="login-box-2 bwcolor">
                <h5>
                    Create your account
                </h5>
                <div class="flow-text">
                    <p class="status-text-2" style="margin-bottom: 0.5rem;"></p>
                </div>
                <div class="user-type-form">
                    <div class="user-type-student user-type"><i class="material-icons">person</i>Student</div>
                    <div class="user-type-instructor user-type"><i class="material-icons">school</i>Instructor</div>
                </div>
                <form class="register-form" method="POST">
                    <div class="input-field">
                        <i class="material-icons prefix">person</i>
                        <input id="dname2" name="dname2" type="text" class="white-text">
                        <label for="dname2">Full Name</label>
                    </div>
                    <br>
                    <div class="input-field">
                        <i class="material-icons prefix">person</i>
                        <input id="username2" name="username2" type="text" class="white-text">
                        <label for="username2">Username</label>
                    </div>
                    <br>
                    <div class="input-field">
                        <i class="material-symbols-outlined prefix">mail</i>
                        <input id="email2" name="email2" type="email" class="white-text">
                        <label for="email2">Email</label>
                    </div>
                    <br>
                    <div class="input-field">
                        <i class="material-icons prefix">key</i>
                        <input id="password2" name="password2" type="password" class="white-text">
                        <label for="password2">Password</label>
                    </div>
                </form>
                <p>
                    Already a member? <a class="switch">Login</a>
                </p>
                <button class="waves-effect waves-light green btn create-btn">
                    <div class="icon-holder">
                        <i class="material-symbols-outlined prefix">add_circle</i>CREATE
                    </div>
                </button>
            </div>
            <?php include("footer.php"); ?>
        </div>
</body>

</html>
<script src="js/theme.js"></script>
<script src="js/login.js"></script>