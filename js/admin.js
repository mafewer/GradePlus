
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
                modalMessage("Reset Demo Successful", "The database was reset successfully!", true);
            } else {
                modalMessage("Reset Demo Failed", "There was a problem with the server. Please try again later.", false);
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

isPressedSQL = false;
$("a.send-sql").click(function() {
    if (isPressedSQL) {
        return;
    }
    $("a.send-sql").addClass("disabled").text("RUNNING");
    isPressedSQL = true;
    $.ajax({
        url: "services/custom-sql.php",
        type: "POST",
        data: {
            authorize: "gradeplus",
            command: $("#textarea1").val()
        },
        success: (response) => {
            if (response["success"] == 1) {
                modalMessage("SQL Command Successful", "The SQL command was executed successfully!", true);
            } else {
                modalMessage("SQL Command Failed", "There was a problem with the server. Please try again later.", false);
            }
            $("div.modal").fadeIn(100);
            setTimeout(() => {
                $("div.modal").fadeOut();
            }, 3000);
            isPressedSQL = false;
            $("a.send-sql").removeClass("disabled").text("SEND");
        }
    });
});

function modalMessage(title, message, type) {
    if (type) {
        $("div.modal").removeClass("red darken-5").addClass("green darken-5");
        $("div.modal-footer").removeClass("red darken-5").addClass("green darken-5");
        $("div.modal-content").html(
            "<div class='icon-holder'><i class='material-symbols-outlined' style ='font-size: 3rem;'>check_circle</i><h4>"+title+"</h4></div><p>"+message+"</p>"
        );
    } else {
        $("div.modal").removeClass("green darken-5").addClass("red darken-5");
        $("div.modal-footer").removeClass("green darken-5").addClass("red darken-5");
        $("div.modal-content").html(
            "<div class='icon-holder'><i class='material-symbols-outlined' style ='font-size: 3rem;'>warning</i><h4>"+title+"</h4></div><p>"+message+"</p>"
        );
    }
}

$(window).on("load", () => {
    $("div.loader").fadeOut(200); // Hide the loader
    setTimeout(() => {
        $("div.mainapp").fadeIn(200); // Show the main app after a short delay
    }, 200);
});