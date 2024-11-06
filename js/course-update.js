if ($("a.addenrolcourse").attr("id")==="enroltrue"){ //Student
    $("div.course-details").hide();
    $("div.save").hide();
    $("div.delete").hide();
    $("div.settings-footer").css("margin-top", "1rem");
} else {
    $("div.withdraw").hide();
    $("input#updatedcode").attr("placeholder", $("p.side-nav-course-code").text());
    $("input#updatedname").attr("placeholder", $("p.side-nav-course-name").text());
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
                window.alert("500 - Server Error");
            } else {
                if (bannerFile) {
                    $("img.side-nav-img").attr("src", "img/"+$("input[name='updatedbanner']")[0].files[0].name);
                }
                if (courseCode) {
                    $("p.side-nav-course-code").html(courseCode);
                    $("input[name='updatedcode']").attr("placeholder", courseCode);
                    $("input[name='updatedcode']").val("");
                }
                if (courseName) {
                    $("input[name='updatedname']").attr("placeholder", courseName);
                    $("input[name='updatedcode']").val("");
                }
            }
        }
    });
});

$("a.course-withdraw-btn").click(() => {
    $("a.agree-btn").text("WITHDRAW");
    $("div.modal").fadeIn(200);
    $("span.confirm-modal-text").text("withdraw from this course");
    
    $("a.agree-btn").click(() => {
        $.ajax({
            url: "services/de-enroll-user.php",
            type: "POST",
            data: {username: $("span.user-name").text(), invitecode: $("p.side-nav-course-invite").text(), authorize: "gradeplus"},
            dataType : "json",
            success: (response) => {
                if (response["success"] != 1) {
                    window.alert("500 - Server Error");
                } else {
                    $("a.backuserdashboard").click();
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
    $("span.confirm-modal-text").text("delete this course");
    
    $("a.agree-btn").click(() => {
        $.ajax({
            url: "services/delete-course.php",
            type: "POST",
            data: {invitecode: $("p.side-nav-course-invite").text(), authorize: "gradeplus"},
            dataType : "json",
            success: (response) => {
                if (response["success"] != 1) {
                    window.alert("500 - Server Error");
                } else {
                    $("a.backuserdashboard").click();
                }
            }
        });
        });

        $("a.cancel-btn").click(() => {
            $("div.modal").fadeOut(200);
        });
});