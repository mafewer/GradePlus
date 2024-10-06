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

//User Type
$usertype = "instructor";

//Dummy Data
$courses = [["ECE 6400","Software Development","Raja Abbas"]];
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-account.css">
    <title>GradePlus - Dashboard</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <?php include("loader.php"); ?>
    <div class="mainapp">
        <?php include("header.php"); ?>
        <img src="img/loginback.png" class="indexback">
        <img src="img/loginbackdark.png" class="indexback2">
        <!-- Side Nav -->
        <ul id="slide-out" class="side-nav bwcolor sidenav-fixed">
            <img class="side-nav-img" src='img/card.jpg'>
            <p class="side-nav-course-code">Loading</p>
            <li><a class="side-nav-item bwcolor backuserdashboard"><i class="material-icons">reply</i>Back
                    to Course List</a>
            </li>
            <li><a class="side-nav-item bwcolor assignments"><i class="material-icons">assignment</i>Assignments</a>
            </li>
            <li><a class="side-nav-item bwcolor grades"><i class="material-icons">bar_chart</i>Grades</a></li>
            <li><a class="side-nav-item bwcolor peer-reviews"><i class="material-icons">reviews</i>Peer Reviews</a></li>
            <li><a class="side-nav-item bwcolor discussions"><i class="material-icons">chat</i>Discussions</a></li>
            <li><a class="side-nav-item bwcolor classlist"><i class="material-icons">group</i>Classlist</a></li>
            <li><a class="side-nav-item bwcolor csettings"><i class="material-icons">settings</i>Course Settings</a>
            </li>
            <div style="flex-grow: 1;"></div>
            <li><a class="waves-effect green std-hover waves-light btn backuserdashboard"
                    style="position: relative; bottom: 4rem;"><i class="material-icons left">arrow_back</i>BACK TO
                    DASHBOARD</a></li>
        </ul>
        <!-- Course Dashboard -->
        <div class="coursedash">
            <div style="width: 20rem"></div>
            <div class="coursedash-holder bwcolortext">
                <h3 class="coursedash-header">
                    Assignments
                </h3>
                <p>Not Implemented Yet</p>
            </div>

        </div>
        <div class="courseholder bwcolortext">
            <!-- Top Info -->
            <div class="top-icon-holder">
                <i class="material-symbols-outlined accounticon">account_circle</i>
                <div class="top-info-holder">
                    <h2 class="top-info-header">
                        Welcome
                        <span class="display-name"><?php echo $_SESSION['dname'];?></span>!
                    </h2>
                    <p class="accountemail">
                        <?php echo $_SESSION['email']; ?>
                    </p>
                </div>
            </div>
            <!-- Account Settings -->
            <div class="account-settings">
                Not Implemented Yet
                <br>
                <br>
                <a class="waves-effect green std-hover waves-light btn account-settings-back"><i
                        class="material-icons left">arrow_back</i>BACK TO
                    DASHBOARD</a>
            </div>
            <!-- Add or Enrol Modals -->
            <div class="modal bwcolor">
                <div class="modal-content">
                    <h4>Loading</h4>
                    <div class="input-field course-code">
                        <i class="material-icons prefix">key</i>
                        <input id="coursecode" name="coursecode" type="text">
                        <label for="coursecode">Course Code</label>
                    </div>
                    
                    <!--Below is not visible to Students -->
                    <div class="input-field course-name">
                        <i class="material-icons prefix">menu_book</i>
                        <input id="coursename" name="coursename" type="text">
                        <label for="coursename">Course Name</label>
                    </div>
                    <div class="input-field file-field upload-banner">
                        <div class="btn banner-btn">
                            <span>File</span>
                            <input type="file" name="coursebanner" accept="image/*" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" id="bannername" name="bannername" type="text">
                            <label for="bannername">Upload Banner</label>
                         </div>
                        
                    </div>
                </div>
                <div class="modal-footer bwcolor">
                    <a class="addenrol-modal-cancel waves-effect bwcolortext btn-flat">CANCEL</a>
                    <a class="addenrol-modal-close waves-effect white-text green btn-flat">DONE</a>
                </div>
            </div>
            <!-- Course List -->
            <div class="course-list">
                <p class="course-list-head">
                    Your Courses
                </p>
                <div class="course-list-holder">
                    <?php
                    foreach ($courses as $course) {
                        echo "<div class='card course-card std-hover'>
                        <div class='card-image'>
                          <img src='img/card.jpg'>
                          <span class='card-title'>" . $course[0] . "</span>
                          <a class='btn-floating halfway-fab waves-effect waves-light green'><i class='material-symbols-outlined'>keep</i></a>
                        </div>
                        <div class='card-content bwcolor'>
                          <p>" . $course[1] . "</p>
                          <p class='secondary'>" . $course[2] . "</p>
                        </div>
                      </div>";
                    }
?>
                    <div class='card addenrolcourse std-hover'>
                        <div class='card-image'>
                            <img class="addcourseimg" src='img/addcourse.png'>
                            <a class='btn-floating halfway-fab waves-effect waves-light green addenrolcourse'><i
                                    class='material-symbols-outlined'>add_circle</i></a>
                        </div>
                        <div class='card-content bwcolor'>
                            <p class="addenrolcourse-text" id=<?php echo $usertype == "student" ? "enroltrue" : "enrolfalse"; ?>><?php echo $usertype == "student" ? "Enrol in a Course" : "Add a Course"; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("footer.php"); ?>
</body>
<script src="js/account.js"></script>
<script src="js/theme.js"></script>