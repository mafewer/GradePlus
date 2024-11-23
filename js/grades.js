// Swipe to Reveal
if (!localStorage.getItem('swipe-to-reveal')) {
    localStorage.setItem('swipe-to-reveal', '1');
}

if (localStorage.getItem('swipe-to-reveal') == '1') {
    $("input#swipe-to-reveal").prop("checked", true);
} else {
    $("input#swipe-to-reveal").prop("checked", false);
}

if ($("a.addenrolcourse").attr("id")==="enroltrue"){ // Student
    $("div.sub-assignments").hide();
    studentgrades();
} else {
    $("div.grades").hide();
    subassignments();
}

function studentgrades() {
    // Get the student's grades
    $.ajax({
        url: "services/grade-retrieval.php",
        type: "POST",
        data: {
            "username": $("span.user-name").text(),
            "invite_code": $("p.side-nav-course-invite").text(),
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                window.alert("500 - Server Error");
                return;
            } else {
                data = response.grades.map(grade => [
                    grade.assignment_name,
                    `${grade.grade}/${grade.max_grade}`,
                    grade.feedback
                ]);
                var tableBody = $("table.table-grades tbody");
                tableBody.empty(); // Clear any existing rows

                data.forEach(function(row) {
                    var tr = $("<tr></tr>");
                    row.forEach(function(cell) {
                        var td = $("<td></td>").text(cell);
                        if (tr.children().length === 0) {
                            td.css("padding-left", "2rem");
                        } else if (tr.children().length === 1) {
                            td.html('<div class="swipe-reveal"><p class="swipe-reveal-text">Swipe to Reveal →</p><p class="grade-score">' + cell + '</p></div>');
                        }
                        tr.append(td);
                    });
                    tableBody.append(tr);
                });
                update_reveal();
            }
        }});
}

function subassignments() {
    //Cancel
    $("button.grade-review-close").click(function() {
        $("div.grading").hide();
        $("table.table-sub-assignments").show();
    });
    // Get the submitted assignments
    $.ajax({
        url: "services/get-all-submissions.php",
        type: "POST",
        data: {
            "invite_code": $("p.side-nav-course-invite").text(),
            "authorize": "gradeplus"
        },
        dataType : "json",
        success: (response) => {
            if (response["success"] != 1) {
                window.alert("500 - Server Error");
                return;
            } else {
                data = response.data.map(grade => [
                    grade.assignment_name,
                    grade.username,
                    `${grade.grade}/${grade.max_grade}`,
                    grade.feedback,
                    grade.assignment_id
                ]);
                var tableBody = $("table.table-sub-assignments tbody");
                tableBody.empty(); // Clear any existing rows

                data.forEach(function(row) {
                    var tr = $("<tr></tr>");
                    row.forEach(function(cell) {
                        var td = $("<td></td>").text(cell);
                        if (tr.children().length === 0) {
                            td.css("padding-left", "2rem");
                        }
                        if (tr.children().length != 4) {
                            tr.append(td);
                        }
                    });
                    td = $(`<td><button class='btn-flat waves-effect waves-light sub-assignments-grade' data-assignment-id='${row[4]}' data-student-username='${row[1]}'>
                                                    <i class="material-icons" style="color:green; font-size: 1.6rem;">edit_note</i>
                                                </button></td>`);
                    td.css("vertical-align", "middle").css("width", "10%");
                    tr.append(td);
                    tableBody.append(tr);
                });
                $("button.sub-assignments-grade").click(function() {
                    $.ajax({
                        url: "services/get-individual-submission.php",
                        type: "POST",
                        data: {
                            "invite_code": $("p.side-nav-course-invite").text(),
                            "student_name": $(this).data("student-username"),
                            "assignment_id": $(this).data("assignment-id"),
                            "authorize": "gradeplus"
                        },
                        dataType : "json",
                        success: (response) => {
                            if (response["success"] != 1) {
                                window.alert("500 - Server Error");
                                return;
                            } else {
                                $("button.grade-review-save").data("assignment-id", $(this).data("assignment-id"));
                                $("button.grade-review-save").data("student-username", $(this).data("student-username"));
                                if (response.data[0].grade != null) {
                                    $("input#score-input").val(response.data[0].grade);
                                } else {
                                    $("input#score-input").val(0);
                                }
                                if (response.data[0].max_grade != null) {
                                    $("input#score-max-input").val(response.data[0].max_grade);
                                } else {
                                    $("input#score-max-input").val(0);
                                }
                                if (response.data[0].feedback != null) {
                                    $("textarea#feedback-input").val(response.data[0].feedback);
                                } else {
                                    $("textarea#feedback-input").val("");
                                }
                                M.updateTextFields();
                                $("table.table-sub-assignments").hide();
                                $("div.grading").css("display", "flex");
                                $("div.grade-pdf").html(`<embed src='${response.data[0].submitted_pdf}' type="application/pdf" width="100%" height="100%" />`);

                                //Grading
                                $("button.grade-review-save").click(function() {
                                    $.ajax({
                                        url: "services/grade-assignment.php",
                                        type: "POST",
                                        data: {
                                            "invite_code": $("p.side-nav-course-invite").text(),
                                            "username": $(this).data("student-username"),
                                            "assignment_id": $(this).data("assignment-id"),
                                            "grade": $("input#score-input").val(),
                                            "max_grade": $("input#score-max-input").val(),
                                            "feedback": $("textarea#feedback-input").val(),
                                            "authorize": "gradeplus"
                                        },
                                        dataType : "json",
                                        success: (response) => {
                                            if (response["success"] != 1) {
                                                window.alert("500 - Server Error");
                                                return;
                                            } else {
                                                $("button.grade-review-close").click();
                                                subassignments();
                                            }
                                        }
                                    });});
                            }}});
                });
            }}});
}

function update_reveal() {
    if (localStorage.getItem('swipe-to-reveal') == '1') {
        $("p.swipe-reveal-text").show();
        $(".swipe-reveal").animate({scrollLeft: 0}, 500); // Reset scroll position of the parent container
        $('.swipe-reveal').on('click', '.swipe-reveal-text', function() {
            const $nextParagraph = $(this).next('p'); // Select the next sibling p tag
            if ($nextParagraph.length) {
                $(this).parent().animate({
                    scrollLeft: $nextParagraph.position().left
                }, 500);
            }
        });    
    } else {
        $("p.swipe-reveal-text").hide();
    }
}

$("input#swipe-to-reveal").change(function() {
    localStorage.setItem('swipe-to-reveal', $(this).is(":checked") ? '1' : '0');
    update_reveal();
});
