<html>
<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" type="text/css" href="css/styles-admin.css">
    <title>Administrator Dashboard</title>
    <link rel="icon" href="img/logoGreen.png">
</head>

<body class="white-text">
    <div class="container">
        <h2>
            Administrator Dashboard
        </h2>
        <div class="flow-text">
        </div>
        <div class="section">
            <div class="icon-holder">
                <i class="material-icons">build</i>
                <h5>Reset Demo</h5>
            </div>
            <p>
                Resets the localhost database to its default state. Use this to also initialize the
                database for the first time.
            </p>
            <a class="waves-effect waves-light btn reset-demo">RESET DEMO</a>
        </div>
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
                    <textarea id="textarea1" class="materialize-textarea white-text"></textarea>
                    <label style="left: 0;" for="textarea1">Command</label>
                </div>
            </div>
            <a class="waves-effect waves-light btn">SEND</a>
        </div>
    </div>
    <div id="modal1" class="modal teal darken-5">
        <div class="modal-content">
            <h4></h4>
            <p></p>
        </div>
        <div class="modal-footer teal darken-5">
            <a class="white-text modal-close waves-effect waves-green btn-flat">OK</a>
        </div>
    </div>
</body>
<script>
    isPressed = false;
    $("a.reset-demo").click(function() {
        if (isPressed) {
            return;
        }
        $("a.reset-demo").addClass("disabled").text("RUNNING");
        isPressed = true;
        $.ajax({
            url: "services/reset-demo.php",
            type: "POST",
            data: {
                authorize: "gradeplus"
            },
            success: (response) => {
                if (response["success"] == 1) {
                    $("div.modal-content h4").text("Reset Demo Successful");
                    $("div.modal-content p").text("Database has been reset successfully!");
                } else {
                    $("div.modal-content h4").text("Reset Demo Failed");
                    $("div.modal-content p").text(
                        "There was a problem with the server. Please try again later.");
                }
                $("div.modal").fadeIn(100);
                setTimeout(() => {
                    $("div.modal").fadeOut();
                }, 3000);
                isPressed = false;
                $("a.reset-demo").removeClass("disabled").text("RESET DEMO");
            }
        });
    });
    $("a.modal-close").click(function() {
        $("div.modal").fadeOut(100);
    });
</script>
<script src="js/theme.js"></script>

</html>
</body>