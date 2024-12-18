if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    $("div.grading-input").show();
    $("div.viewing-input").hide();
    $("div.submitted-list").show();
    $("div.sub-heading").show();
} else { // Instructor
    $("div.grading-input").hide();
    $("div.viewing-input").show();
    $("div.submitted-list").hide();
    $("div.sub-heading").hide();
}
$("div.grading").css("display", "none");
retrieveAssignments();


function retrieveAssignments() {
    // Get the Student's assignments
    var assignmentsBody = $("div.assignments-list");
    $.ajax({
        url: "services/get-assignments.php",
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
                assignmentsBody.empty(); // Clear any existing assignments
                let assignments = response["data"];
                assignments.forEach((assign) => {
                        let assignCard = `
                            <div class="card assign-card std-hover" data-file="${assign.assignment_file}" data-id="${assign.assignment_id}" data-assignment-name="${assign.assignment_name}" data-description="${assign.description}" data-duedate="${assign.due_date}"
                            data-instructor="${assign.instructor}">
                                <div class="card-content">
                                    <i class="material-icons card-icon">assignment</i>
                                    <span style="font-weight: bold;" class="card-title">${assign.assignment_name}</span>
                                    <p>${assign.description}</p>
                                    <p>Due Date: ${assign.due_date}</p>
                                </div>
                                <a id="remove-btn" style="position: absolute; top: 1rem; right: 1rem;" class='delete-assign btn-floating halfway-fab waves-effect waves-light red'><i
                                class='material-symbols-outlined'>delete</i></a></span>
                            </div>`;
                        assignmentsBody.append(assignCard);
                         
                });
                if (assignmentsBody.children().length === 0) {
                    $("h6.no-assignments-header").show();
                } else {
                    $("h6.no-assignments-header").hide();
                }
                if ($("a.addenrolcourse").attr("id")!=="enroltrue") {
                    $("a.add-assign").show();
                } else {
                    $("a.delete-assign").hide();
                }
            }
        }});
}

//Opening an assignment
$("div.assignments-list").on("click", "div.assign-card", function() {
    var file = $(this).data("file") ? $(this).data("file") : "assignments/demo.pdf";
    var id = $(this).data("id");
    var description = $(this).data("description");
    var dueDate = $(this).data("duedate");

    var assignmentName = $(this).data("assignment-name");

    $("div.grade-pdf").html(`<embed src="${file}" type="application/pdf" width="100%" height="100%" />`);

    $(".grading").data("assignment-name", assignmentName); 
    $(".grading").data("assignment-id", id); 
    $(".assign-container").hide();
    $("p.assign-info").text(description);
    $("p.assign-info-2").text(dueDate);
    $(".grading").css("display", "flex");
});
    
//Submitting an Assignment
$("button.assign-submit").click(function() {
let submitted_pdf = $("input[name='assignfile']")[0].files[0];
if (!submitted_pdf) {
    window.alert("Please upload a PDF file");
    return;
}
var assignmentId = $(".grading").data("assignment-id");
var assignmentName = $(".grading").data("assignment-name");

var formData = new FormData();

formData.append('authorize', 'gradeplus');  // Authorization
formData.append('username', $("span.user-name").html());  // Username
formData.append('assignment_name', assignmentName);  // Assignment name
formData.append('invite_code', $("p.side-nav-course-invite").text());  // Invite code
formData.append('course_code', $("p.side-nav-course-code").text());  // Course code
formData.append('assignment_id', assignmentId);  // Assignment ID
formData.append('submitted_pdf', submitted_pdf);  // Submitted PDF

    $.ajax({
        url: 'services/submit_assignment.php', 
        type: 'POST', 
        data: formData,
        processData: false,  // Prevent jQuery from processing data (since we are sending FormData)
        contentType: false,  // Don't set content type (FormData handles it)
        dataType : "json",
        success: function(response) {
            if (response['success']==1) {
                window.alert("Assignment Submitted Successfully!");
                $("div.grading").css("display", "none");
                $("div.assign-container").show();
                retrieveAssignments();
            } else {
                window.alert("500 - Server Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Failed to Submit Assignment:", status, error);
            console.log("Response Text:", xhr.responseText); // Log the response text for debugging
        }
    });
});
    
//Closing an assignment
$("button.assign-close").click(function() {
    $("div.grading").css("display", "none");
    $("div.assign-container").show();
});

//Adding an assignment modal (instructors only)
$("a.add-assign").click(() => {
    $("div.addassign-modal").fadeIn(100);
    $("input[name='assignname']").val("");
    $("textarea#description-input").val("");
    $("input[name='duedate']").val("");
    $("input[name='instructionfile']").val("");
});

//Adding an assignment
$("a.addassign-modal-add").click(function() {
    var assignment_name = $("input[name='assignname']").val();
    var description = $("textarea#description-input").val();
    var due_date = $("input[name='duedate']").val();
    var invite_code = $("p.side-nav-course-invite").text();
    var course_code = $("p.side-nav-course-code").text();
    var assignment_file = $("input[name='instructionfile']")[0].files[0];
    var instructor = $("span.user-name").html();

    var formData = new FormData();

    formData.append('authorize', 'gradeplus');  // Authorization
    formData.append('assignment_name', assignment_name);  // Assignment name
    formData.append('description', description);  // Description
    formData.append('due_date', due_date);  // Due date
    formData.append('course_code', course_code);  // Invite code
    formData.append('invite_code', invite_code);  // Course code
    formData.append('assignment_file', assignment_file);  // Assignment file
    formData.append('instructor', instructor);  // Instructor

    $.ajax({
        url: 'services/add_assignment.php', 
        type: 'POST', 
        data: formData,
        processData: false,  // Prevent jQuery from processing data (since we are sending FormData)
        contentType: false,  // Don't set content type (FormData handles it)
        dataType : "json",
        success: function(response) {
            if (response['success']==1) {
                $("div.addassign-modal").fadeOut(100);
                $("input[name='assignname']").val("");
                $("textarea#description-input").val("");
                $("input[name='duedate']").val("");
                $("input[name='instructionfile']").val("");
                retrieveAssignments();
            } else {
                window.alert("500 - Server Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Failed to Add Assignment:", status, error);
        }
    });
});

//Closing the add assignment modal
$("a.addassign-modal-cancel").click(function() {
    $("div.addassign-modal").fadeOut(100);
});

//Removing an assignment (only for instructors)
$("div.assignments-list").on("click", "a.delete-assign", function(event) {
    event.stopPropagation();
    var assignment_id = $(this).parent().data("id");
    var invite_code = $("p.side-nav-course-invite").text();
    var instructor = $(this).parent().data("instructor");
    $("div.delete-modal").fadeIn(100);


    $("a.delete-btn").click(function() {
        $.ajax({
            url: 'services/remove_assignment.php', 
            type: 'POST', 
            data: {
                "authorize": "gradeplus",
                "assignment_id": assignment_id,
                "invite_code": invite_code,
                "instructor": instructor
            },
            dataType : "json",
            success: function(response) {
                if (response['success']==1) {
                    retrieveAssignments();
                } else {
                    window.alert("500 - Server Error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to Remove Assignment:", status, error);
            }
        });
        $("div.delete-modal").fadeOut(100);
    });

    $("a.cancel-btn").click(function() {
        $("div.delete-modal").fadeOut(100);
    });
});
