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
    var dummy_data = [['Assignment 1', '10/10', 'Excellent'], ['Assignment 2', '8/10', 'Good'], ['Assignment 3', '2/10', 'Poor']];
    var tableBody = $("table.table-grades tbody");
    tableBody.empty(); // Clear any existing rows

    dummy_data.forEach(function(row) {
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

function subassignments() {
    // Get the submitted assignments
    var dummy_data = [['Assignment 1', 'Student', '', ''], ['Assignment 2', 'Student', '', ''], ['Assignment 3', 'Student', '', '']];
    var tableBody = $("table.table-sub-assignments tbody");
    tableBody.empty(); // Clear any existing rows

    dummy_data.forEach(function(row) {
        var tr = $("<tr></tr>");
        row.forEach(function(cell) {
            var td = $("<td></td>").text(cell);
            if (tr.children().length === 0) {
                td.css("padding-left", "2rem");
            }
            tr.append(td);
        });
        td = $(`<td><button class='btn-flat waves-effect waves-light sub-assignments-grade' data-assignment-id="1">
                                        <i class="material-icons" style="color:green; font-size: 1.6rem;">edit_note</i>
                                    </button></td>`);
        td.css("vertical-align", "middle").css("width", "10%");
        tr.append(td);
        tableBody.append(tr);
    });

    $("button.sub-assignments-grade").click(function() {
        $("table.table-sub-assignments").hide();
        $("div.grading").css("display", "flex");
        $("div.grade-pdf").html('<embed src="assignments/demo.pdf" type="application/pdf" width="100%" height="100%" />');
    });

    $("button.grade-review-close").click(function() {
        $("div.grading").hide();
        $("table.table-sub-assignments").show();
    });
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
                }, 500); // Adjust animation duration as needed
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