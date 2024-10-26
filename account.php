<?php
session_start();
$success = 0;
$error = 0;
if (isset($_SESSION['logtime']) && isset($_SESSION['username'])) {
    if ($_SESSION['logtime'] > time()) {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $_SESSION['logtime'] = time() + (60 * 6);
            try {
                $conn = mysqli_connect('localhost', 'gradeplusclient', 'gradeplussql', 'gradeplus');

                //Password is not natively stored in the session for security reasons. Need to fetch it from database to display in account settings.
                $sql = $conn->prepare("SELECT password, profile_picture FROM login WHERE username = ?");
                $sql->bind_param("s", $username);
                $sql->execute();
                $sql->bind_result($password, $profile_picture);
                $sql->fetch();
                $sql->close();

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

//Update account setting variables from session on window load. Important for the account settings functionality.
$usertype = $_SESSION['usertype'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$dname = $_SESSION['dname'];

//Courses Data
$courses = [];
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-account.css">
    <link rel="stylesheet" type="text/css" href="css/account-settings.css">
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
            <img class="side-nav-img"
                style="height: 10rem; object-fit: cover; filter: brightness(0.5); user-select: none;" />
            <p class="side-nav-course-code">Loading</p>
            <p class="side-nav-course-invite">Loading</p>
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
                <div class="coursedash-content">
                </div>
            </div>
        </div>
        <div class="courseholder bwcolortext">
            <!-- Top Info -->
            <div class="top-icon-holder">
                <div class="acc-upload-pic" style="justify-content: center;"><i class="material-icons"
                        style="align-self: center; font-size:2.5rem; transition: all 0.5s;">photo_camera</i>
                </div>
                <input type="file" style="display: none;" name="upload-profile-pic" id="upload-profile-pic"
                    accept="image/*" required>
                <?php if ($profile_picture): ?>
                <img src="<?php echo $profile_picture; ?>"
                    alt="Profile Picture" class="profile-pic">
                <?php else: ?>
                <i class="material-symbols-outlined accounticon">account_circle</i>
                <?php endif; ?>
                <div class="top-info-holder">
                    <h2 class="top-info-header">
                        <?php
                        $hour = date('H');
if ($hour >= 5 && $hour < 12) {
    echo 'Good Morning';
} elseif ($hour >= 12 && $hour < 17) {
    echo 'Good Afternoon';
} else {
    echo 'Good Evening';
}
?>
                        <span
                            class="display-name"><?php echo $dname;?></span>!
                        <span class="user-name"
                            style="display: none;"><?php echo $username;?></span>
                    </h2>
                    <p class="accountemail">
                        <?php echo $_SESSION['email']; ?>
                    </p>
                </div>
            </div>
            <!-- Account Settings -->
            <div class="account-settings">
                <div class="account-item bwcolortext">
                    <!-- Account Settings Display -->
                    <p>Account Details</p>
                    <div class="account-item-holder">
                        <p>Username:</p>
                        <p class="acc-item acc-user-name">
                            <?php echo $username;?>
                        </p>
                        <input class="acc-input bwcolor" type="text" id="new-user-name"
                            placeholder="<?php echo $username; ?>">
                    </div>
                    <div class="account-item-holder">
                        <p>Display Name:</p>
                        <p class="acc-item acc-display-name">
                            <?php echo $dname;?>
                        </p>
                        <input class="acc-input bwcolor" type="text" id="new-display-name"
                            placeholder="<?php echo $dname; ?>">
                    </div>
                    <div class="account-item-holder">
                        <p>Email:</p>
                        <p class="acc-item acc-email">
                            <?php echo $email; ?>
                        </p>
                        <input class="acc-input bwcolor" type="email" id="new-account-email"
                            placeholder="<?php echo $email; ?>">
                    </div>
                    <div class="account-item-holder">
                        <p>Password:</p>
                        <p class="acc-item acc-email">
                            <?php echo isset($password) ? str_repeat('*', strlen($password)) : 'Password Not Found'; ?>
                        </p>
                        <input class="acc-input bwcolor" type="password" id="new-account-password"
                            placeholder="<?php echo isset($password) ? str_repeat('*', strlen($password)) : 'Password Not Found'; ?>">
                    </div>
                    <button class="waves-effect green std-hover waves-light btn edit-account-settings-btn"><i
                            class="material-icons left">edit</i>Edit
                        Details</button>
                    <!-- Account Settings Update Form -->
                    <div class="acc-update-form">
                        <button class="waves-effect red std-hover waves-light btn acc-return-btn"><i
                                class="material-icons left">arrow_back</i>Cancel</a></button>
                        <button class="waves-effect green std-hover waves-light btn acc-save-btn"><i
                                class="material-icons left">save</i>Save</a></button>
                    </div>
                    <p>Delete Account</p>
                    <button class="delete-account-btn waves-effect red std-hover waves-light btn delete-account-btn"><i
                            class="material-icons left">delete</i>Delete My Account</button>
                    <!-- Delete Account Safety Modal -->
                    <div class="delete-account-safety bwcolor">
                        <i class="material-icons delete-account-close">warning</i>
                        <p>Are you sure you want to delete your account permanently?</p>
                        <div class="delete-account-form-actions">
                            <a class="waves-effect red std-hover waves-light btn delete-account-confirm-btn"><i
                                    class="material-icons left">delete</i>Yes, delete
                                my
                                account </a>
                            <a class="waves-effect green std-hover waves-light btn delete-account-cancel-btn"><i
                                    class="material-icons left">arrow_back</i>No</a>
                        </div>
                    </div>
                    <p>Go back to Dashboard</p>
                    <a class="waves-effect green std-hover waves-light btn account-settings-back"><i
                            class="material-icons left">arrow_back</i>DASHBOARD</a>
                </div>

            </div>
            <!-- Add or Enrol Modals -->
            <div class="modal bwcolor">
                <div class="modal-content">
                    <h4 style="margin-bottom: 1.5rem;">Loading</h4>
                    <p class="status-text"></p>
                    <div class="modal-addenrol-holder">
                        <div class="input-field course-code">
                            <i class="material-icons prefix">key</i>
                            <input id="coursecode" name="coursecode" type="text">
                            <label for="coursecode">Course Code</label>
                        </div>
                        <!--Instructors Only -->
                        <div class="input-field course-name">
                            <i class="material-symbols-outlined prefix">import_contacts</i>
                            <input id="coursename" name="coursename" type="text">
                            <label for="coursename">Course Name</label>
                        </div>
                        <div style="display: flex; align-items: center;" class="input-field file-field upload-banner">
                            <i class="material-symbols-outlined prefix">add_photo_alternate</i>
                            <button style="position: relative; margin-left: 3rem; margin-top: 0.3rem;"
                                id="file-picker-btn" class="waves-effect green white-text btn-flat">
                                BANNER IMAGE
                                <input type="file" name="coursebanner" id="coursebanner" accept="image/*" required>
                            </button>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bwcolor">
                    <a class="addenrol-modal-cancel waves-effect bwcolortext btn-flat">CANCEL</a>
                    <a class="addenrol-modal-add waves-effect white-text green btn-flat">ADD</a>
                    <a class="addenrol-modal-enrol waves-effect white-text green btn-flat">ENROLL</a>
                </div>
            </div>
            <!-- Course List -->
            <div class="course-list">
                <p class="course-list-head">
                    Your Courses
                </p>
                <div class="course-list-holder">
                </div>
                <a class="waves-effect green addenrolcourse std-hover waves-light btn add-enrol"
                    id=<?php echo $usertype == "Student" ? "enroltrue" : "enrolfalse"; ?>><i
                        class="material-symbols-outlined left">add_circle</i><?php echo $usertype == "Student" ? "Enroll in a Course" : "Add a Course"; ?></a>
            </div>
        </div>
        <?php include("footer.php"); ?>
</body>
<script src="js/account.js"></script>
<script src="js/theme.js"></script>