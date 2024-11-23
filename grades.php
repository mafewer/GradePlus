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
    <div class="grading">
        <div class="grade-pdf"></div>
        <div class="grading-input">
            <h6 style="margin-bottom: 2rem;">Modify Grade</h6>
            <div class="input-field">
                <input type="text" id="score-input">
                <label for="grade">Grade</label>
            </div>
            <div class="input-field">
                <input type="text" id="score-max-input">
                <label for="grade">Maximum Grade</label>
            </div>
            <div class="input-field">
                <h6>Feedback</h6>
                <textarea id="feedback-input" style="background-color: var(--hover-color); color: var(--font-color)"></textarea>
            </div>
            <button class="btn-flat waves-effect waves-light grade-review-close" style="color: var(--font-color)">Cancel</button>
            <button class="btn waves-effect waves-light grade-review-save">Save</button>
        </div>
    </div>
</div>
<script src="js/grades.js"></script>