var topinfo = $("h2.top-info-header").text();
var isCourseOpen = false;
//Switch to Account Settings
$("a.accountservice").click(()=>{
    $("div.course-list").fadeOut(200);
    $("div.account-settings").fadeIn(200);
    $("h2.top-info-header").text("Account Settings");
    if (isCourseOpen){
        $("a.backuserdashboard").click();
    };
})

//Switch to Course List
$("a.account-settings-back").click(()=>{
    $("div.course-list").fadeIn(200);
    $("div.account-settings").fadeOut(200);
    $("h2.top-info-header").text(topinfo);
})

//Add or Enroll Course Modal
$(".addenrolcourse").click(()=>{
    if ($("p.addenrolcourse-text").attr("id")==="enroltrue"){
        $("div.modal-content h4").text("Enter Course Invite Code");
    } else {
        $("div.modal-content h4").text("Add Course");
    }
    $("div.modal").fadeIn(100);
})

$("a.addenrol-modal-close").click(()=>{
    $("div.modal").fadeOut(100);
})

//Opening a Course
$("div.course-card").click((event) => {
    isCourseOpen = true;
    $("ul.side-nav").animate({left: '0'}, {
        duration: 100,
        easing: 'swing'
    });
    console.log(event);
    var coursecode = $(event.currentTarget).find("span.card-title").text();
    $("p.side-nav-course-code").text(coursecode);
    $("div.courseholder").fadeOut(200);
    $("div.coursedash").fadeIn(400).css("display", "flex");
    $("h3.coursedash-header").text("Assignments");
});

//Closing a Course
$("a.backuserdashboard").click(()=>{
    isCourseOpen = false;
    $("ul.side-nav").animate({left: '-20rem'}, {
        duration: 100,
        easing: 'swing'
    });
    $("div.coursedash").fadeOut(200,()=>{
        $("div.courseholder").fadeIn(200);
    });
})

function loadContent(url, headerText) {
    $("h3.coursedash-header").text(headerText);
    $("div.coursedash-content").fadeOut(200, function () {
        $.ajax({
            url: url,
            method: 'GET',
            success: function (data) {
                $("div.coursedash-content").html(data).fadeIn(200);
            },
            error: function (xhr, status, error) {
                console.error("Failed to load content:", status, error);
            }
        });
    });
}

//Testing Only
//$("div.course-card").click();

// Assignments
$("a.assignments").click(() => {
    loadContent('assignments.php', 'Assignments');
});

// Grades
$("a.grades").click(() => {
    loadContent('grades.php', 'Grades');
});

// Peer Reviews
$("a.peer-reviews").click(() => {
    loadContent('peer_reviews.php', 'Peer Reviews');
});

// Discussions
$("a.discussions").click(() => {
    loadContent('discussions.php', 'Discussions');
});

// Classlist
$("a.classlist").click(() => {
    loadContent('classlist.php', 'Classlist');
});

// Settings
$("a.csettings").click(() => {
    loadContent('course_settings.php', 'Course Settings');
});

//Finish Loading
$(window).on("load", () => {
    $("div.loader").fadeOut(200);
    setTimeout(() => {
        $("div.mainapp").fadeIn(200);
    }, 200);
});