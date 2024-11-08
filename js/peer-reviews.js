if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    $("div.instructor").hide();
    peerReviews();
} else {
    $("div.peer-reviews-main").hide();
}


function peerReviews() {
    var dummy_data = [
        ['A123', 'Assignment A', 'assignments/demo.pdf', 'Ben Thomas', true],
        ['A124', 'Assignment B', 'assignments/demo.pdf', 'Sarah Johnson', true],
        ['A125', 'Assignment C', 'assignments/demo.pdf', 'Ben Thomas', false],
        ['A126', 'Assignment D', 'assignments/demo.pdf', 'John Doe', true],
        ['A127', 'Assignment E', 'assignments/demo.pdf', 'Emily Davis', false]
    ];
    
    var subTableBody = $("table.table-sub-assignments tbody");
    var myTableBody = $("table.table-rev-assignments tbody");
    subTableBody.empty(); // Clear any existing rows
    myTableBody.empty(); // Clear any existing rows

    // Start a single row for each table
    var subTableRow = `<tr>`;
    var myTableRow = `<tr>`;

    // Populate "Give Feedback" table (one row, multiple columns)
    dummy_data.forEach(function(data) {
        if (data[4] === true) {
            var pdfFile = data[2] ? data[2] : 'assignments/demo.pdf'; //Check if the file is uploaded. If not, set temp pdf file for testing. 

            if (data[3] === "Ben Thomas" /* $_SESSION['username']; or $_SESSION['displayname']; */) {
                myTableRow += `
                <td>
                    <div class="card bwcolor">
                        <div class="card-image">
                            <i class="large material-icons">assignment</i>
                        </div>
                        <div class="assignmentName">${data[1]}</div> <!-- Assignment Name -->
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn view-feedback" data-file="${pdfFile}" data-id="${data[0]}">
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
                    <div class="card bwcolor">
                        <div class="card-image">
                            <i class="large material-icons">assignment</i>
                        </div>
                        <div class="assignmentName">${data[1]}</div> <!-- Assignment Name -->
                        <div class="studentName">${data[3]}</div> <!-- Student Name -->
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn give-feedback" data-file="${pdfFile}" data-id="${data[0]}" data-student="${data[3]}" data-assignment-name="${data[1]}">
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
        $(".give-feedback-page .student-name").data(student);
        $(".give-feedback-page .assignment-name").data(assignmentName);
        $(".give-feedback-page .assignment-id").data("id", id);
        $(".peer-reviews-main").hide();
        $(".give-feedback-page").css("display", "flex");
    });

    $("button.give-feedback-save").click(function() {
        // Save the feedback and close the "Give Feedback" page
        var feedback = $("textarea#feedback-input").val();
        var assignmentId = $(".give-feedback-page").data("assignment-id");
        var studentName = $(".give-feedback-page").data("student-name");
        var assignmentName = $(".give-feedback-page").data("assignment-name");
        console.log("Feedback: " + feedback);
        console.log("Assignment ID: " + assignmentId);
        console.log("Student Name: " + studentName);
        console.log("Assignment Name: " + assignmentName);
       /* $("div.give-feedback-page").hide();
        $("div.peer-reviews-main").show();*/
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
    });

}
