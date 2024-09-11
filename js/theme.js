
function themeSet() {
    if (localStorage.getItem("isDark") == "true") {
        $("a.theme").text("Light");
        $(".nav-wrapper").removeClass("green darken-1").addClass("grey darken-4");
        $("body").removeClass("green darken-4").addClass("black accent-4");
    } else {
        $("a.theme").text("Dark");
        $(".nav-wrapper").removeClass("grey darken-4").addClass("green darken-1");
        $("body").removeClass("black accent-4").addClass("green darken-4");
    }
}

$("a.theme").click(function() {
    var isDark = localStorage.getItem("isDark");
    localStorage.setItem("isDark", isDark == "true" ? "false" : "true");
    themeSet();
});

themeSet();
