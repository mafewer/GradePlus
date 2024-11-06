function main() {
    var isCourseOpen = false;
    var dname = $("span.display-name").html();
    var username = $("span.user-name").html();
    var isAccountEditing = false;
    var isCourseOpenFirst = false;
    $("div.acc-upload-pic").hide();
    //Switch to Account Settings
    $("a.accountservice").click(()=>{
        $("div.acc-upload-pic").css("display", "flex");
        if (isAccountEditing) {
            $(".acc-return-btn").click();
        }
        $("div.course-list").fadeOut(200);
        $("div.account-settings").fadeIn(200);
        $("h2.top-info-header").text("Account Settings");
        if (isCourseOpen){
            $("a.backuserdashboard").click();
        };
    })

    //Switch to Course List
    $("a.account-settings-back").click(()=>{
        $("div.acc-upload-pic").hide();
        retrieve_courses();
        $("div.course-list").fadeIn(200);
        $("div.account-settings").fadeOut(200);
        let currentHour = new Date().getHours();
        let greeting;
        if (currentHour < 12) {
            greeting = "Good Morning";
        } else if (currentHour < 18) {
            greeting = "Good Afternoon";
        } else {
            greeting = "Good Evening";
        }
        $("h2.top-info-header").text(greeting + " " + dname + "!");
        $("a.assignments").click();
        $("div.modal").fadeOut(200);
    })

    //Edit Accounts Settings
    $(".edit-account-settings-btn").click(()=>{
        isAccountEditing = true;
        $("p.acc-item").hide();
        $("input.acc-input").show();
        $(".edit-account-settings-btn").hide();
        $("div.acc-update-form").css("display", "flex");
    })

    //Return to Account Settings
    $(".acc-return-btn").click(() => {
        isAccountEditing = false;
        $("input.acc-input").hide();
        $("p.acc-item").show();
        $("div.acc-update-form").hide();
        $(".edit-account-settings-btn").show();
    });

    //Update Account Settings
    $(".acc-save-btn").click(() => {
        // Get the new values from the input fields
        let newname = $("#new-user-name").val()
        let newdname = $("#new-display-name").val();
        let newemail = $("#new-account-email").val();
        let newpassword = $("#new-account-password").val();

        // Username Change
        if (newname) {
            $.ajax({
                url: 'services/update-username.php', 
                type: 'POST', 
                data: {
                    authorize: "gradeplus", 
                    newname: newname }, 
                dataType: 'json',  
                success: function(response) {
                    if (response['success']==1) {
                        username = newname;
                        $("p.acc-user-name").html(newname);
                        $("#new-user-name").attr("placeholder", newname);
                        $("button.acc-return-btn").click();
                    } else if (response['taken']==1) {
                        window.alert("Username already exists.");
                    } else {
                        window.alert("500 - Server Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to update username:", status, error);
                }
            });
        }

        //Display Name Change
        if (newdname) {
            $.ajax({
                url: 'services/update-dname.php',
                type: 'POST',
                data: { 
                    authorize: "gradeplus",
                    newdname: newdname },
                dataType: 'json',
                success: function(response) {
                    if (response['success']==1) {
                        dname = newdname;
                        $("span.display-name").html(dname);
                        $("#new-display-name").attr("placeholder", dname);
                        $("p.acc-display-name").html(dname);
                        $("button.acc-return-btn").click();
                    } else {
                        window.alert("500 - Server Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to update display name:", status, error);
                }
            });
        }

        //Email Change
        if (newemail) {
            $.ajax({
                url: 'services/update-email.php',
                type: 'POST',
                data: {
                    authorize: "gradeplus",  
                    newemail: newemail },
                dataType: 'json', 
                success: function(response) {
                    if (response['success']==1) {
                        $("p.acc-email").html(newemail);
                        $("#new-account-email").attr("placeholder", newemail);
                        $("p.accountemail").html(newemail);
                        $("button.acc-return-btn").click();
                    } else if (response['taken']==1) {
                        window.alert("Email already exists.");
                    } else {
                        window.alert("500 - Server Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to update Email:", status, error);
                }
            });
        }

        // Password Change
        if (newpassword) {
            $.ajax({
                url: 'services/update-password.php',
                type: 'POST',
                data: { 
                    authorize: "gradeplus",
                    newpassword: newpassword },
                dataType: 'json',  
                success: function(response) {
                    if (response.success) {
                        console.log("Password updated successfully!");
                        // Log out the user after updating the password
                        $.ajax({
                            url: 'services/logout.php',
                            type: 'POST',
                            data: { 
                                authorize: "gradeplus"
                            },
                            dataType: 'json',
                            success: function(logoutResponse) {
                                if (logoutResponse.success) {
                                    window.location.href = 'login.php';
                                } else {
                                    console.error("Error during logout.");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Failed to log out:", status, error);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to update password:", status, error);
                }
            });
        }
    })     
        

    //Delete Account
    $("button.delete-account-btn").click(()=>{
        $("button.delete-account-btn").hide();
        $("div.delete-account-safety").show();
    })

    //Delete Confirmation
    $(".delete-account-confirm-btn").click(()=>{
        $.ajax({
            url: 'services/delete-user.php',
            type: 'POST',
            data: {
                authorize: "gradeplus"
            },
            dataType: 'json', 
            success: function(response) {
                if (response['success']==1) {
                    window.location.href = 'login.php';
                } else if (response.error) {
                    window.alert("500 - Server Error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to delete user: ", status, error);
            }
        });
    })

    //Cancel Delete
    $(".delete-account-cancel-btn").click(()=>{
        $("div.delete-account-safety").hide();
        $("button.delete-account-btn").show();
    })

    //Profile Photo Upload
    $("div.acc-upload-pic").click(()=>{
        $("input#upload-profile-pic").click();
    });

    $("input#upload-profile-pic").change(function() {
        let formData = new FormData();
        let bannerFile = $("input[name='upload-profile-pic']")[0].files[0];
        
        if (!bannerFile) {
            window.alert("No file selected.");
            return;
        }

        formData.append("banner", bannerFile);
        formData.append("username", username);
        formData.append("authorize", "gradeplus");

        $.ajax({
            url: "services/profilepic-upload.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType : "json",
            success: (response) => {
                if (response["success"] != 1) {
                    window.alert("500 - Server Error");
                    return;
                } else {
                    window.location.reload();
                }
            }
        });
    });


    //Add or Enroll Course Modal
    $("a.addenrolcourse").click(()=>{
        if ($("a.addenrolcourse").attr("id")=="enroltrue"){
            $("div.modal-content h4").text("Enter Invite Code");
            $("div.course-name").hide();
            $("div.upload-banner").hide();
            $("a.addenrol-modal-add").hide();
        } else {
            $("div.modal-content h4").text("Add Course");
            $("a.addenrol-modal-enrol").hide();
        }
        $("div.modal").fadeIn(100);
    })

    $("a.addenrol-modal-enrol").click(() => {
        if (!$("input[name='coursecode']").val()) {
            $("p.status-text").text("Field cannot be left blank");
            $("p.status-text").slideDown();
            setTimeout(() => {
                $("p.status-text").slideUp();
            }, 3000);
            return;
        }

        $.ajax({
            url: "services/enroll-course.php",
            type: "POST",
            data: {username: $("span.user-name").text(), invite_code: $("input[name='coursecode']").val(), authorize: "gradeplus"},
            dataType : "json",
            success: (response) => {
                if (response["invalid"] == 1) {
                    $("p.status-text").text("Invalid Invalid Code");
                    $("p.status-text").slideDown();
                    setTimeout(() => {
                        $("p.status-text").slideUp();
                    }, 3000);
                    return;
                } else if (response["error"] == 1) {
                    $("p.status-text").text("500 - Server Error");
                    $("p.status-text").slideDown();
                    setTimeout(() => {
                        $("p.status-text").slideUp();
                    }, 3000);
                    return;
                } else if (response["exists"] == 1) {
                    $("p.status-text").text("Already Enrolled in Course");
                    $("p.status-text").slideDown();
                    setTimeout(() => {
                        $("p.status-text").slideUp();
                    }, 3000);
                    return;
                } else {
                    retrieve_courses();
                    $("a.addenrol-modal-cancel").click();
                }
            }
        });
    })

    $("a.addenrol-modal-add").click(() => {
        let formData = new FormData();
        let courseCode = $("input[name='coursecode']").val();
        let courseName = $("input[name='coursename']").val();
        let bannerFile = $("input[name='coursebanner']")[0].files[0];

        if (!courseCode || !courseName) {
            $("p.status-text").text("Fields cannot be left blank");
            $("p.status-text").slideDown();
            setTimeout(() => {
                $("p.status-text").slideUp();
            }, 3000);
            return;
        }

        formData.append("coursecode", courseCode);
        formData.append("coursename", courseName);
        formData.append("banner", bannerFile);
        formData.append("instructor_name", username);
        formData.append("instructor_dname", dname);
        formData.append("authorize", "gradeplus");

        $.ajax({
            url: "services/add-course.php",
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
                    retrieve_courses();
                    $("a.addenrol-modal-cancel").click();
                }
            }
        });
    })

    $("a.addenrol-modal-cancel").click(() => {
        $("div.modal").fadeOut(100);
        $("div.input-field input").val("");
    })

    //Closing a Course
    $("a.backuserdashboard").click(()=>{
        $("div.modal").fadeOut(200);
        isCourseOpen = false;
        $("ul.side-nav").animate({left: '-20rem'}, {
            duration: 100,
            easing: 'swing'
        });
        $("div.coursedash").fadeOut(200,()=>{
            $("div.courseholder").fadeIn(200);
        });
        retrieve_courses();
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

    $("#file-picker-btn").click(()=>{
        $("input[name='coursebanner']").click();
    });
    $('#coursecode').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    //Retrieving Courses
    function retrieve_courses() {
    $.ajax({
        url: "services/retrieve-course.php",
        type: "POST",
        data: {
            "username": username,
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                return;
            } else {
                let courses = response["courses"];
                let courseHolder = $("div.course-list-holder");
                courseHolder.empty();
                courses.forEach((course) => {
                    let pinnedlogo = "keep_off";
                    if (course["pinned"] == 1) {
                        pinnedlogo = "keep";
                    }
                    let courseCard = `
                    <div class="card course-card std-hover bwcolor" style="background-color: var(--bwcolor); position: relative;">
                        <div class="card-image" style="height: 12rem; overflow: hidden;">
                            <img style="object-fit: cover;" src="${course["course_banner"]}" alt="Unable to Load Image">
                            <span class="card-title"><span class="card-title-code">${course["course_code"]}</span>
                        </div>
                        <div class="card-content">
                            <p><span class="card-course-name">${course["course_name"]}</span></p>
                            <p class='secondary'>${course["instructor_name"]}</p>
                        </div>
                         <a id="${course["invite_code"]}" style="position: absolute; top: 1rem; right: 1rem;" class='pin btn-floating halfway-fab waves-effect waves-light green coursecode'><i
                                class='material-symbols-outlined'>${pinnedlogo}</i></a></span>
                    </div>`;
                    courseHolder.append(courseCard);
                });

                if (courses.length == 0) {
                    courseHolder.append(
                        `<div class="card std-hover bwcolor" style="background-color: var(--bwcolor); position: relative;">
                            <div class="card-image" style="height: 12rem; overflow: hidden;">
                            <img class="addcourseimg" style="object-fit: cover;" src="img/addcourse.png" alt="Unable to Load Image">
                        </div>
                            <div class="card-content">
                                <p>No Courses Found</p>
                            </div>
                        </div>`
                    );
                }

                //Opening a Course
                $("div.course-card").click((event) => {
                    isCourseOpen = true;
                    $("img.side-nav-img").attr("src", $(event.currentTarget).find("img").attr("src"));
                    $("ul.side-nav").animate({left: '0'}, {
                        duration: 100,
                        easing: 'swing'
                    });
                    var coursecode = $(event.currentTarget).find("span.card-title-code").text();
                    var invitecode = $(event.currentTarget).find("a.coursecode").attr("id");
                    var coursename = $(event.currentTarget).find("span.card-course-name").text();
                    $("p.side-nav-course-invite").text(invitecode);
                    $("p.side-nav-course-code").text(coursecode);
                    $("p.side-nav-course-name").text(coursename);
                    $("i.course-invite-copy").click(()=>{
                        var copyText = document.createElement("textarea");
                        copyText.value = $("p.side-nav-course-invite").text();
                        document.body.appendChild(copyText);
                        copyText.select();
                        document.execCommand("copy");
                        document.body.removeChild(copyText);
                        $("i.course-invite-copy").text("check").css("color", "green");
                        setTimeout(() => {
                            $("i.course-invite-copy").text("content_copy").css("color", "rgb(194, 194, 194)");
                        }, 1000);
                    });
                    $("div.courseholder").fadeOut(200,()=>{
                        $("div.coursedash").fadeIn(200).css("display", "flex");
                        if (isCourseOpenFirst == false){
                            isCourseOpenFirst = true;
                            $("a.assignments").click();
                        }
                    });
                });

                //Pinning a Course
                $("a.pin").click((event)=>{
                    event.stopPropagation();
                    let invite_code = $(event.currentTarget).attr("id");
                    isPinned = $(event.currentTarget).find("i").text() == "keep_off" ? true : false;
                    $.ajax({
                        url: "services/pin-course.php",
                        type: "POST",
                        data: {
                            "username": $("span.user-name").text(),
                            "invitecode": invite_code,
                            "pinned": isPinned ? 1 : 0,
                            "authorize": "gradeplus"
                        },
                        dataType : "json",
                        success: (response) => {
                            if (response["success"] != 1) {
                                return;
                            } else {
                                $("div.course-list-holder").empty();
                                retrieve_courses();
                            }
                        }
                    });
                })
            }
        }});
    }
    retrieve_courses();
}

//Finish Loading
$(window).on("load", () => {   
    $("div.loader").fadeOut(200);
    setTimeout(() => {
        $("div.mainapp").fadeIn(200);
    }, 200);
    main();
});