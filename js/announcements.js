// Get Announcements
function retrieveAnnouncements() {
    var announcementBody = $("div.announcements-container");
    $.ajax({
        url: "services/get-announcements.php",
        type: "POST",
        data: {
            "invite_code": $("p.side-nav-course-invite").text(),
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                return;
            } else {
                announcementBody.empty(); // Clear any existing announcement
                //console.log(response);
                let announcement = response["data"];
                announcement.forEach((announcement) => {
                        let announcementCard = `
                            <div class="card announcement-card std-hover">
                                <div class="card-content">
                                    <i class="material-icons card-icon">notifications</i>
                                    <span style="font-weight: bold;" class="card-title">${announcement.header}</span>
                                    <p>${announcement.text}</p>
                                    <p>Posted: ${announcement.date}</p>
                                </div>
                                <a id="remove-btn" data-id="${announcement.id}" style="position: absolute; top: 1rem; right: 1rem;" class='delete-announcement btn-floating halfway-fab waves-effect waves-light red'><i
                                class='material-symbols-outlined'>delete</i></a></span>
                            </div>`;
                        announcementBody.append(announcementCard);
                         
                });
                if (announcementBody.children().length === 0) {
                    $("h6.no-announcements-header").show();
                } else {
                    $("h6.no-announcements-header").hide();
                }
                if ($("a.addenrolcourse").attr("id")!=="enroltrue") { // Instructor
                    addAnnouncement();
                } else {
                    $("a.add-announcement").hide();
                    $("a.delete-announcement").hide();
                }
            }
        }});
}


// Add an Announcement (Only for Instructors)
function addAnnouncement() {
    $("a.add-announcement").click(function() {
        $("div.add-announcement").fadeIn(100);
        $("a.announcement-add-btn").click(function() {
            if ($("input#announcement-header").val() == "" || $("input#announcement-text").val() == "") {
                window.alert("Please fill out all fields");
                return;
            }
            $.ajax({
                url: 'services/add_announcement.php', 
                type: 'POST', 
                data: {
                    "authorize": "gradeplus",
                    "invite_code": $("p.side-nav-course-invite").text(),
                    "header": $("input#announcement-header").val(),
                    "text": $("input#announcement-text").val()
                },
                dataType : "json",
                success: function(response) {
                    if (response['success']!=1) {
                        window.alert("500 - Server Error");
                    }
                    $("a.announcement-cancel-btn").click();
                }
            });
        });
    });
    
    $("a.announcement-cancel-btn").click(function() {
        $("div.add-announcement").fadeOut(100);
        $("input#announcement-header").val("");
        $("input#announcement-text").val("");
    });

    // Delete an Announcement (Only for Instructors)
    $("a.delete-announcement").click(function() {
        let id = $(this).data("id");
        $.ajax({
            url: 'services/delete_announcement.php', 
            type: 'POST', 
            data: {
                invite_code: $("p.side-nav-course-invite").text(),
                id: id,
                authorize: "gradeplus"
            },
            dataType : "json",
            success: function(response) {
                if (response['success']==1) {
                    retrieveAnnouncements();
                } else {
                    window.alert("500 - Server Error");
                }
            }
    })});
};

retrieveAnnouncements();