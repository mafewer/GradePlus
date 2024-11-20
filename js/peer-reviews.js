if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    $("div.instructor").hide();
    peerReviews();
} else {
    $("div.peer-reviews-main").hide();
}


function peerReviews() {

    /* Dummy data for testing
    var submission_data = [
        ['123', 'Assignment A', 'assignments/demo.pdf', 'Ben Thomas', true],
        ['124', 'Assignment B', 'assignments/demo.pdf', 'Sarah Johnson', true],
        ['125', 'Assignment C', 'assignments/demo.pdf', 'Ben Thomas', false],
        ['126', 'Assignment D', 'assignments/demo.pdf', 'John Doe', true],
        ['127', 'Assignment E', 'assignments/demo.pdf', 'Emily Davis', false]
    ]; */

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
    var subTableBody = $("table.table-sub-assignments tbody");
    var myTableBody = $("table.table-rev-assignments tbody");
    subTableBody.empty(); // Clear any existing rows
    myTableBody.empty(); // Clear any existing rows

    // Start a single row for each table
    var subTableRow = `<tr>`;
    var myTableRow = `<tr>`;

    // Populate "Give Feedback" table (one row, multiple columns)
    submission_data.forEach(function(data) {
        if (data.submitted_flag === '1') {
            var pdfFile = data.submitted_pdf ? data.submitted_pdf : 'assignments/demo.pdf'; //Check if the file is uploaded. If not, set temp pdf file for testing. 
            if (data.username === window.username) {
                myTableRow += `
                <td>
                    <div class="card std-hover bwcolor" style="background-color: var(--bwcolor);">
                        <div class="card-image">
                            <i class="large material-icons">assignment</i>
                        </div>
                        <div class="assignmentName">${data.assignment_name}</div> <!-- Assignment Name -->
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn view-feedback" data-file="${pdfFile}" data-id="${data.assignment_id}">
                                View Feedback
                                <i class="material-icons right center">visibility</i>
                            </button>
                        </div>
                    </div>
                </td>`;
            }
            else{
                subTableRow += `
                <td>
                    <div class="card std-hover bwcolor" style="background-color: var(--bwcolor);">
                        <div class="card-image">
                            <i class="large material-icons">assignment</i>
                        </div>
                        <div class="assignmentName">${data.assignment_name}</div> <!-- Assignment Name -->
                        <div class="studentName">${data.username}</div> <!-- Student Name -->
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn give-feedback" data-file="${pdfFile}" data-id="${data.assignment_id}" data-student="${data.username}" data-assignment-name="${data.assignment_name}">
                                Give Feedback
                                <i class="material-icons right center">chat</i>
                            </button>
                        </div>
                    </div>
                </td>`;
            }
        }
    });

    // Close the row for the submitted assignments table
    subTableRow += `</tr>`;
    myTableRow += `</tr>`;
    subTableBody.append(subTableRow);
    myTableBody.append(myTableRow);
    
    $("button.give-feedback").on("click", function() {
        // Open the "Give Feedback" page and load the assignment PDF
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

    $("button.give-feedback-save").click(function() {
        // Save the feedback and close the "Give Feedback" page
        var feedback = $("textarea#feedback-input").val();
        var assignmentId = $(".give-feedback-page").data("assignment-id");
        var studentName = $(".give-feedback-page").data("student-name");
        var assignmentName = $(".give-feedback-page").data("assignment-name");

        /*console.log("Feedback: " + feedback);
        console.log("Assignment ID: " + assignmentId);
        console.log("Student Name: " + studentName);
        console.log("Assignment Name: " + assignmentName);*/

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
                    } else {
                        window.alert("500 - Server Error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to submit review:", status, error);
                }
            });
        } else {
            window.alert("Please enter feedback before saving.");
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

    $("button.view-feedback").click(function() {
        var file = $(this).data("file");
        var id = $(this).data("id");
        $("div.review-pdf").html(`<embed src="${file}" type="application/pdf" width="100%" height="100%" />`);
        $("div.peer-reviews-main").hide();
        $("div.view-feedback-page").css("display", "flex");

        //TO IMPLEMENT WHEN BACKEND COMPLETE
        /*$.ajax({
            url: 'services/get-reviews.php', 
            type: 'POST', 
            data: {
                authorize: "gradeplus", 
                username: window.username, 
                assignment_id: id
            }, 
            dataType: 'json',  
            success: function(response) {
                if (response['success']==1) {
                    var reviews = response['data'];

                    // Clear the existing feedback list
                    var feedbackList = $("div.view-feedback-page .review-input ul");
                    feedbackList.empty();

                    reviews.forEach(function(feedback) {
                        feedbackList.append(`<li>${feedback}</li>`);
                    });

                    $("div.peer-reviews-main").hide();
                    $("div.view-feedback-page").css("display", "flex");
                } else {
                    window.alert("500 - Server Error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to retreieve reviews:", status, error);
            }
        });*/
    });

}
