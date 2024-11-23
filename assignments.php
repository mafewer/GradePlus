<link rel="stylesheet" type="text/css" href="css/styles-assignments.css">
<body>
    <div class="assign-container">
        <div class="assignments-list">
            <!-- assignments will go here -->
        </div>
        <div class="submitted-list">
            <!-- submitted assignments will go here -->
        </div>
        <a class="add-assign modal-close green white-text waves-effect waves-green btn-flat"><i class="material-symbols-outlined left">add_circle</i>Add</a>
    </div>
    <!--assignment view-->
    <div class="grading">
        <div class="grade-pdf"></div>
        <div class="grading-input">
            <h6 style="margin-bottom: 1rem;">Submit Assigment</h6>
            <div style="display: flex; align-items: center;" class="input-field file-field add-assign-file">
                <i class="material-symbols-outlined prefix">description</i>
                <button style="position: relative; margin-left: 3rem; margin-top: -0.5rem;"
                    id="file-picker-btn" class="waves-effect waves-light green white-text btn-flat">
                    ADD FILE
                    <input class="assignfile" type="file" name="assignfile" id="assignfile" accept="application/pdf" required>
                </button>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div style="display: flex; justify-content:end; margin-top: 1.5rem;">
            <button class="btn-flat waves-effect waves-light assign-close " style="color: var(--font-color)">Cancel</button>
            <button class="btn waves-effect waves-light green assign-submit">Submit</button>
            </div>
        </div>
    </div>
    <!-- Add assign Modal -->
    <div class="modal addassign-modal" style="background-color: var(--bwcolor); color: var(--font-color)">
        <div class="modal-content">
            <h4 style="margin-bottom: 1.5rem;">Add Assignment</h4>
            <p class="status-text"></p>
            <div class="modal-addenrol-holder">
                <div class="input-field assign-name">
                    <i class="material-icons prefix">edit</i>
                    <input id="assignname" name="assignname" type="text">
                    <label for="assignname">Assignment Name</label>
                </div>
                <div class="input-field due-date">
                    <i class="material-symbols-outlined prefix">calendar_today</i>
                    <input id="duedate" name="duedate" type="text" class="datepicker">
                    <label for="duedate">Due Date</label>
                </div>
                <div style="display: flex; align-items: center;" class="input-field file-field upload-file">
                    <i class="material-symbols-outlined prefix">description</i>
                    <button style="position: relative; margin-left: 3rem; margin-top: -0.5rem;"
                        id="file-picker-btn" class="waves-effect green white-text btn-flat">
                        ADD FILE
                        <input type="file" name="instructionfile" id="instructionfile" accept="application/pdf" required>
                    </button>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                <div class="input-field">
                    <h6>Description</h6>
                    <textarea style="height: 6rem;" id="description-input"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background-color: var(--bwcolor); color: var(--font-color)">
            <a class="addassign-modal-cancel waves-effect btn-flat" style="color: var(--font-color)">CANCEL</a>
            <a class="addassign-modal-add waves-effect white-text green btn-flat">ADD</a>
        </div>
    </div>
    <!-- Delete Assignment Safety Modal -->
    <div id="delete-modal" class="delete-modal modal bwcolor">
        <div class="delete-modal-content">
            <h4>Delete Assignment</h4>
            <p>Are you sure you want to <span class="confirm-modal-text">delete this assignment</span>?</p>
        </div>
        <div class="delete-modal-footer bwcolor" style="display: flex; justify-content:end;">
            <a class="cancel-btn modal-close waves-effect waves-green btn-flat" style="color: var(--font-color)">Cancel</a>
            <a class="delete-btn modal-close red white-text waves-effect waves-red btn-flat">Delete</a>
        </div>
    </div>
</body>
<script>
    // Initialize the datepicker
    $(document).ready(function(){
    $('#duedate').datepicker({
        format: 'yyyy-mm-dd',          // Set the date format
        autoClose: true,               // Close on date select
        showClearBtn: true        // Show clear button
    });
});
</script>
<script src="js/assignment.js"></script>