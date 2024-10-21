if ($("a.addenrolcourse").attr("id")==="enroltrue"){ //if is student
    $("div.course-details").hide();
    $("div.save").hide();
    $("div.right-footer").hide();
    $("div.settings-footer").css("margin-top", "1rem");
} else {
    $("div.withdraw").hide();
}

$("a.save-course-info").click(() => {

    let formData = new FormData();
    let courseCode = $("input[name='updatedcode']").val();
    let courseName = $("input[name='updatedname']").val();
    let bannerFile = $("input[name='updatedbanner']")[0].files[0];

    formData.append("coursecode", courseCode);
    formData.append("coursename", courseName);
    formData.append("banner", bannerFile);
    formData.append("authorize", "gradeplus");
    formData.append("invitecode", $("p.side-nav-course-invite").text());

    $.ajax({
        url: "services/course-update.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                $("p.status-text").text("500 - Server Error");
                $("p.status-text").slideDown();
                setTimeout(() => {
                    $("p.status-text").slideUp();
                }, 3000);
                return;
            } else {
                window.location.reload();
            }
        }
    });
});

$("a.course-withdraw-btn").click(() => {
    $("a.agree-btn").text("WITHDRAW");
    $("div.modal").fadeIn(200);
    
    $("a.agree-btn").click(() => {
        $.ajax({
            url: "services/de-enroll-user.php",
            type: "POST",
            data: {username: $("span.user-name").text(), invitecode: $("p.side-nav-course-invite").text(), authorize: "gradeplus"},
            dataType : "json",
            success: (response) => {
                if (response["success"] != 1) {
                    $("p.status-text").text("500 - Server Error");
                    $("p.status-text").slideDown();
                    setTimeout(() => {
                        $("p.status-text").slideUp();
                    }, 3000);
                    return;
                } else {
                    window.location.reload();
                }
            }
        });
    });

    $("a.cancel-btn").click(() => {
        $("div.modal").fadeOut(200);
    });

});

$("a.delete-course-btn").click(() => {
    $("a.agree-btn").text("DELETE");
    $("div.modal").fadeIn(200);
    
    $("a.agree-btn").click(() => {
        $.ajax({
            url: "services/delete-course.php",
            type: "POST",
            data: {invitecode: $("p.side-nav-course-invite").text(), authorize: "gradeplus"},
            dataType : "json",
            success: (response) => {
                if (response["success"] != 1) {
                    $("p.status-text").text("500 - Server Error");
                    $("p.status-text").slideDown();
                    setTimeout(() => {
                        $("p.status-text").slideUp();
                    }, 3000);
                    return;
                } else {
                    window.location.reload();
                }
            }
        });
        });

        $("a.cancel-btn").click(() => {
            $("div.modal").fadeOut(200);
        });
});