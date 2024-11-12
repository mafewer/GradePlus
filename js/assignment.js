if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    //$("div.sub-assignments").hide();
} else { // Instructor
    $("div.submitted-list").hide();
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
            "course_code": $("p.side-nav-course-code").text(),
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                return;
            } else {
                assignmentsBody.empty(); // Clear any existing assignments
                assignments = response["data"];
                console.log(assignments);
                assignments.forEach((assign) => {
                        let assignCard = `
                            <div class="card assign-card std-hover bwcolor" data-file="${assign.assignment_file}" data-id="${assign.assignment_id}" data-assignment-name="${assign.assignment_name}">
                                <div class="card-content bwcolortext">
                                    <span class="card-title">${assign.assignment_name}</span>
                                    <p>${assign["description"]}</p>
                                    <p>Due Date: ${assign["due_date"]}</p>
                                </div>
                            </div>`;
                        assignmentsBody.append(assignCard); 
                });
                if ($("a.addenrolcourse").attr("id")!=="enroltrue") { // Instructor
                    //add assignment button
                    assignmentsBody.append(
                    `<div class="card add-assign-card std-hover bwcolor">
                        <div class="card-content">
                        <span class="add-assign material-symbols-outlined green-text">
                        add
                        </span>
                        </div>
                    </div>`
                );
                }
            }
        }});

        //Opening an assignment
        $("div.assignments-list").on("click", "div.assign-card", function() {
            console.log("clicked");
            //$("div.grade-pdf").html('<embed src="assignments/demo.pdf" type="application/pdf" width="100%" height="100%" />');
            var file = $(this).data("file");
            var id = $(this).data("id");
            var assignmentName = $(this).data("assignment-name");

            /* CURRENTLY NOT WORKING
            var byteCharacters = atob(file); // Decode the base64 string
            var byteArrays = [];

            for (var offset = 0; offset < byteCharacters.length; offset += 1024) {
                var slice = byteCharacters.slice(offset, offset + 1024);
                var byteNumbers = new Array(slice.length);
                
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }
            console.log(byteArrays);
            var blob = new Blob(byteArrays, {type: 'application/pdf'});
            var blobURL = URL.createObjectURL(blob);*/

            $(".grading .grade-pdf").html(`<embed src="assignments/demo.pdf" type="application/pdf" width="100%" height="100%" />`);

            $(".grading").data("assignment-name", assignmentName); 
            $(".grading").data("assignment-id", id); 

            $(".assign-container").hide();
            $(".grading").css("display", "flex");
        });
                
        //Submitting an Assignment

        $("button.assign-submit").click(function() {
            let submitted_pdf = $("input[name='assignfile']")[0].files[0];
            var assignmentId = $(".grading").data("assignment-id");
            var assignmentName = $(".grading").data("assignment-name");

            var formData = new FormData();

            formData.append('authorize', 'gradeplus');  // Authorization
            formData.append('username', $("span.user-name").html());  // Username
            formData.append('assignment_name', assignmentName);  // Assignment name
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
                            $("div.grading").css("display", "none");
                            $("div.assign-container").show();
                        } else {
                            window.alert("500 - Server Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Failed to submit review:", status, error);
                    }
                });
        });
                
            //Closing an assignment
            $("button.assign-close").click(function() {
                $("div.grading").css("display", "none");
                $("div.assign-container").show();
            });

            //Adding an assignment modal (instructors only)
            $("div.add-assign-card").click(function() {
                $("div.addassign-modal").fadeIn(100);
            });
            
            //Adding an assignment
            $("a.addassign-modal-add").click(function() {
                var assignment_name = $("input[name='assignname']").val();
                var description = $("textarea#description-input").val();
                var due_date = $("input[name='duedate']").val();
                var course_code = $("p.side-nav-course-code").text();
                var assignment_file = $("input[name='instructionfile']")[0].files[0];
                var instructor = $("span.display-name").html();

                var formData = new FormData();

                formData.append('authorize', 'gradeplus');  // Authorization
                formData.append('assignment_name', assignment_name);  // Assignment name
                formData.append('description', description);  // Description
                formData.append('due_date', due_date);  // Due date
                formData.append('course_code', course_code);  // Course code
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
                            retrieveAssignments();
                        } else {
                            window.alert("500 - Server Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Failed to submit review:", status, error);
                    }
                });
                });

                //Closing the add assignment modal
                $("a.addassign-modal-cancel").click(function() {
                    $("div.addassign-modal").fadeOut(100);
                });

            }
/*function submitAssignment() {
    // Submit an assignment
    $.ajax({
        url: "services/submit_assignment.php",
        type: "POST",
        data: {
            "username": username,
            "assignment_id": assignment_id,
            "assignment_name": assignment_name,
            "course_code": $("p.side-nav-course-code").text(),
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                return;
            } else {
            
            }
        }});
}*/


/*function addAssignment() {
    // Add an assignment to the course
    var course = $("select#course").val();
    var assignment = $("input#assignment").val();
    var assignmentsBody = $("div.assignments-list");
}

function removeAssignment() {
    // Remove an assignment from the course
    var assignment = $("select#assignment").val();
    var assignmentsBody = $("div.assignments-list");
}*/
