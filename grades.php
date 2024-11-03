<link rel="stylesheet" type="text/css" href="css/styles-grades.css">
<!--Grades (Student only)-->
<div class="grades">
    <div class="reveal-holder">
        <label class="reveal-text" for="swipe-to-reveal">Swipe to Reveal</label>
        <label class="reveal-switch">
            <input type="checkbox" id="swipe-to-reveal">
            <span class="slider round"></span>
        </label>
    </div>
    <table class="table-grades bwcolor">
        <thead>
            <th style="padding-left: 2rem;">Assignment</th>
            <th>Grade</th>
            <th>Feedback</th>
        </thead>
        <tbody id="table-grades-body" class="bwcolor">
            <!-- Data Will populate here -->
        </tbody>
    </table>
</div>
<!--Submitted Assignments (Instructor only)-->
<div class="sub-assignments">
    <table class="table-sub-assignments bwcolor">
        <thead>
            <th style="padding-left: 2rem;">Assignment</th>
            <th>Student Name</th>
            <th>Grade</th>
            <th>Feedback</th>
            <th>Modify</th>
        </thead>
        <tbody id="table-sub-assignments-body" class="bwcolor">
            <!-- Data Will populate here -->
        </tbody>
    </table>
</div>
<script src="js/grades.js"></script>