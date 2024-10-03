
//Login Monolitihic Form Submit
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

//Create Account AJAX
$(".create-btn").click(function() {
    $(ajax({
        url: "services/register.php",
        type: "POST",
        data: {
            username: $("input[name='username2']").val(),
            email: $("input[name='email2']").val(),
            password: $("input[name='password2']").val()
        },
        success: (response) => {
            if (response["success"] == 1) {
                $("a.switch").click();
            } else if (response["exists"] == 1) {
                $("p.status-text-2").text("Username or Email already exists");
            } else if (response["empty"] == 1) {
                $("p.status-text-2").text("Fields cannot be left blank");
            } else {
                $("p.status-text-2").text("500 - Server Error");
            }
            $("p.status-text-2").slideDown();
            setTimeout(() => {
                $("p.status-text-2").slideUp();
            }, 3000);
        }
    }));
});

//Rotate Animation
isLogin = true;
$("a.switch").click(function() {
    isLogin = !isLogin;
    if (!isLogin) {
        $("div.login-box-2").css({
            transform: "rotateY(0deg)"
        });
        $("div.login-box").css({
            transform: "rotateY(-90deg)"
        });
    } else {
        $("div.login-box-2").css({
            transform: "rotateY(90deg)"
        });
        setTimeout(() => {
            $("div.login-box").css({
                transform: "rotateY(0deg)"
            });
        }, 500);
    }
});

//Keyboard Binding
$(document).keypress(function(e) {
    if (e.which == 13 && $("#password").is(":focus")) {
        $(".login-btn").click();
    }
});

$(document).keypress(function(e) {
    if (e.which == 13 && $("#password2").is(":focus")) {
        $(".create-btn").click();
    }
});

//Finish loading
$(window).on("load", () => {
    $("div.loader").fadeOut(200);
    setTimeout(() => {
        $("div.mainapp").fadeIn(200);
    }, 200);
});