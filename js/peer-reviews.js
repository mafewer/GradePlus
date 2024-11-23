if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    $("div.peer-instructor").hide();
} else {
    $("div.peer-student").hide();
}
peerReviews();

function peerReviews() {
    coursecode = $("p.side-nav-course-code").text();

    $.ajax({
        url: 'services/get-all-submissions.php', 
        type: 'POST', 
        data: {
            authorize: "gradeplus", 
            course_code: coursecode
        }, 
        dataType: 'json',  
        success: function(response) {
            if (response['success']==1) {
                submission_data = response['data'];
                loadPeerReviews(submission_data);
            } else {
                window.alert("500 - Server Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Failed to retrieve submitted assignments:", status, error);
        }
    });
}

function loadPeerReviews(submission_data) {    
    var givebody = $("div.sub-assignments");
    var receivedbody = $("div.rev-assignments");
    var instructor_assignments = $("div.instructor-assignments");

    givebody.empty();
    receivedbody.empty();
    instructor_assignments.empty();

    // Populate "Give Reviews" and "Received Reviews" sections
    submission_data.forEach(function(data) {
        if (data.submitted_flag === '1') {
            var pdfFile = data.submitted_pdf ? data.submitted_pdf : 'assignments/demo.pdf'; //Check if the file is uploaded. If not, set temp pdf file for testing. 
            if (data.username != window.username) {
                var card = `
                <div class="card give-feedback review-card std-hover" data-file="${pdfFile}" data-id="${data.assignment_id}" data-assignment-name="${data.assignment_name}" data-student="${data.username}">
                    <div class="card-content">
                        <i class="material-icons card-icon">chat</i>
                        <span style="font-weight: bold;" class="card-title">${data.assignment_name}</span>
                        <p>Submitted by: ${data.username}</p>
                    </div>
                </div>`;
                givebody.append(card);
            }
            else{
                card = `
                <div class="card view-feedback review-card std-hover" data-file="${pdfFile}" data-id="${data.assignment_id}" data-assignment-name="${data.assignment_name}">
                    <div class="card-content">
                        <i class="material-icons card-icon">chat</i>
                        <span style="font-weight: bold;" class="card-title">${data.assignment_name}</span>
                        <p>Your Work</p>
                    </div>
                </div>`;
                receivedbody.append(card);
            }
            if ($("a.addenrolcourse").attr("id")!="enroltrue") { // Instructor
                var card = `
                <div class="card view-feedback review-card std-hover" data-file="${pdfFile}" data-id="${data.assignment_id}" data-assignment-name="${data.assignment_name}" data-student="${data.username}">
                    <div class="card-content">
                        <i class="material-icons card-icon">chat</i>
                        <span style="font-weight: bold;" class="card-title">${data.assignment_name}</span>
                        <p>Submitted by: ${data.username}</p>
                    </div>
                </div>`;
                instructor_assignments.append(card);
            }
            if (receivedbody.children().length == 0) {
                $("h6.rev-assignments-header").text("No Received Reviews");
            } else {
                $("h6.rev-assignments-header").text("Student Reviews");
            }
            if (receivedbody.children().length == 0) {
                $("h6.rev-assignments-header").text("No Received Reviews");
            } else {
                $("h6.rev-assignments-header").text("Received Reviews");
            }
            if (givebody.children().length == 0) {
                $("h6.sub-assignments-header").text("No Assignments to Review");
            } else {
                $("h6.sub-assignments-header").text("Give Reviews");
            }
        }
    });
    
    // Open the "Give Feedback" page and load the assignment PDF
    $("div.give-feedback").on("click", function() {
        var file = $(this).data("file");
        var id = $(this).data("id");
        var student = $(this).data("student");
        var assignmentName = $(this).data("assignment-name");

        $(".give-feedback-page .review-pdf").html(`<embed src="${file}" type="application/pdf" width="100%" height="100%" />`);

        $(".give-feedback-page").data("student-name", student);  
        $(".give-feedback-page").data("assignment-name", assignmentName); 
        $(".give-feedback-page").data("assignment-id", id); 

        $(".peer-reviews-main").hide();
        $(".give-feedback-page").css("display", "flex");
    });
    
    // Save the feedback and close the "Give Feedback" page
    $("button.give-feedback-save").click(function() {
        var feedback = $("textarea#feedback-input").val();
        var assignmentId = $(".give-feedback-page").data("assignment-id");
        var studentName = $(".give-feedback-page").data("student-name");
        var assignmentName = $(".give-feedback-page").data("assignment-name");

        if (feedback.length > 0) {
            $.ajax({
                url: 'services/submit_review.php', 
                type: 'POST', 
                data: {
                    authorize: "gradeplus", 
                    username: studentName, 
                    assignment_id: assignmentId,
                    assignment_name: assignmentName,
                    review: feedback
                }, 
                dataType: 'json',  
                success: function(response) {
                    if (response['success']==1) {
                        $("div.give-feedback-page").hide();
                        $("div.peer-reviews-main").show();
                        window.alert("Feedback submitted successfully!");
                    } else {
                        window.alert("500 - Server Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to submit review:", status, error);
                }
            });
        } else {
            window.alert("Please write a feedback before submitting.");
        }
    });

    $("button.give-feedback-close").click(function() {
        $("div.give-feedback-page").hide();
        $("div.peer-reviews-main").show();
    });

    $("button.view-feedback-close").click(function() {
        $("div.view-feedback-page").hide();
        $("div.peer-reviews-main").show();
    });

    $("div.view-feedback").click(function() {
        var file = $(this).data("file");
        $("div.review-pdf").html(`<embed src="${file}" type="application/pdf" width="100%" height="100%" />`);
        $("div.peer-reviews-main").hide();
        $("div.view-feedback-page").css("display", "flex");

        $.ajax({
            url: 'services/get-reviews.php', 
            type: 'POST', 
            data: {
                authorize: "gradeplus", 
                username: $("a.addenrolcourse").attr("id")==="enroltrue" ? window.username : $(this).data("student"),
                assignment_name: $(this).data("assignment-name"),
                assignment_id: $(this).data("id")
            }, 
            dataType: 'json',  
            success: function(response) {
                if (response['success']==1) {
                    var reviews = response['data'];

                    // Clear the existing feedback list
                    var feedbackList = $("div.view-feedback-page .review-input ul");
                    feedbackList.empty();

                    reviews.forEach(function(feedback) {
                        feedbackList.append(`<li style="background-color: var(--bwcolor); color: var(--font-color);">${feedback}</li>`);
                    });

                    if (reviews.length == 0) {
                        feedbackList.append(`<li style="background-color: var(--bwcolor); color: var(--font-color);">No Feedback Yet</li>`);
                    }

                    $("div.peer-reviews-main").hide();
                    $("div.view-feedback-page").css("display", "flex");
                } else {
                    window.alert("500 - Server Error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to retrieve reviews:", status, error);
            }
        });
    });

}
