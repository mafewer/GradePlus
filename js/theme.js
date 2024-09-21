
function themeSet() {
    if (localStorage.getItem("isDark") == "true") {
        $("i.theme").animate({
            rotate: '360deg',
            opacity: '0'
        }, 250, () => {
            $("i.theme").text("light_mode");
            $("span.theme").text("Light");
            $("i.theme").animate({
                "opacity": "1",
                "rotate": '0deg'
            }, 250);
        });
        $(".nav-wrapper").removeClass("green darken-1").addClass("grey darken-4");
        $("body").removeClass("green darken-4").addClass("black accent-4");
    } else {
        $("i.theme").animate({
            rotate: '360deg',
            opacity: '0'
        }, 250, () => {
            $("i.theme").text("dark_mode");
            $("span.theme").text("Dark");
            $("i.theme").animate({
                "opacity": "1",
                "rotate": '0deg'
            }, 250);
        });
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
