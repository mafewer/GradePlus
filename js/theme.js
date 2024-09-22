
function themeSet() {
    if (localStorage.getItem("isDark") == "true") {
        $("i.theme").animate({
            rotate: '360deg',
            opacity: '0'
        }, 250, () => {
            $("i.theme").animate({
                "opacity": "1",
                "rotate": '0deg'
            }, 250);
            checkTheme();
        });
    } else {
        $("i.theme").animate({
            rotate: '360deg',
            opacity: '0'
        }, 250, () => {
            $("i.theme").animate({
                "opacity": "1",
                "rotate": '0deg'
            }, 250);
            checkTheme();
        });
    }
}

$("a.theme").click(function() {
    var isDark = localStorage.getItem("isDark");
    localStorage.setItem("isDark", isDark == "true" ? "false" : "true");
    themeSet();
});

function checkTheme() {
    if (localStorage.getItem("isDark") == null) {
        localStorage.setItem("isDark", "false");
    }
    if (localStorage.getItem("isDark") == "true") {
        $("i.theme").text("light_mode");
        $("span.theme").text("Light");
        $(".nav-wrapper").removeClass("green darken-1").addClass("grey darken-4");
        $("body").removeClass("green darken-4").addClass("black accent-4");
        $(".bwcolor").removeClass("white black-text").addClass("grey darken-4 white-text");
        $("body").css({"--font-color": "black", "--warn-color": "yellow"});
    } else {
        $("i.theme").text("dark_mode");
        $("span.theme").text("Dark");
        $(".nav-wrapper").removeClass("grey darken-4").addClass("green darken-1");
        $("body").removeClass("black accent-4").addClass("green darken-4");
        $(".bwcolor").removeClass("grey darken-4 white-text").addClass("white black-text");
        $("body").css({"--font-color": "black", "--warn-color": "red"});
    }
}

checkTheme();
