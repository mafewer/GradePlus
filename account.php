<?php session_start();
//Dummy User Type Retrieve
$usertype = "instructor";
$usertype = "student";


//Dummy Courses
$courses = [["ECE 6400","Software Development Practices","Raja Abbas"],["ECE 6500","Computer Architecture","Hamed Nasiri"],["ECE 6600","Communication Principles","Weimin Huang"]]
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
                        <?php echo $_SESSION['dname']; ?>!
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
                    <p>Not Implemented Yet</p>
                </div>
                <div class="modal-footer bwcolor">
                    <a href="#!" class="addenrol-modal-close waves-effect white-text green btn-flat">DONE</a>
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