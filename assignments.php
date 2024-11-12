<link rel="stylesheet" type="text/css" href="css/styles-assignments.css">
<body>
    <div class="assign-container">
        <div class="assignments-list">
            <!-- assignments will go here -->
        </div>
        <div class="submitted-list">
            <div class="sub-heading">
                <h4>Submitted</h4>
            </div>
            <div class="submitted-list">
                <!-- submitted assignments will go here -->
            </div>
        </div>
    </div>
    <!--assignment view-->
    <div class="grading">
        <div class="grade-pdf"></div>
        <div class="grading-input">
            <h6 style="margin-bottom: 2rem;">Submit Assigment</h6>
            <div style="display: flex; align-items: center;" class="input-field file-field add-assign-file">
                <i class="material-symbols-outlined prefix">add_photo_alternate</i>
                <button style="position: relative; margin-left: 3rem; margin-top: 0.3rem;"
                    id="file-picker-btn" class="waves-effect waves-light green white-text btn-flat">
                    ADD FILE
                    <input class="assignfile" type="file" name="assignfile" id="assignfile" accept="application/pdf" required>
                </button>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <button class="btn-flat waves-effect waves-light assign-close bwcolortext">Cancel</button>
            <button class="btn waves-effect waves-light green assign-submit">Submit</button>
        </div>
    </div>
    <!-- Add assign Modal -->
    <div class="modal addassign-modal bwcolor">
        <div class="modal-content">
            <h4 style="margin-bottom: 1.5rem;">Add Assignment</h4>
            <p class="status-text"></p>
            <div class="modal-addenrol-holder">
                <div class="input-field assign-name">
                    <i class="material-icons prefix">key</i>
                    <input id="assignname" name="assignname" type="text">
                    <label for="assignname">Assignment Name</label>
                </div>
                <div class="input-field due-date">
                    <i class="material-symbols-outlined prefix">import_contacts</i>
                    <input id="duedate" name="duedate" type="text" class="datepicker">
                    <label for="duedate">Due Date</label>
                </div>
                <div style="display: flex; align-items: center;" class="input-field file-field upload-file">
                    <i class="material-symbols-outlined prefix">add_photo_alternate</i>
                    <button style="position: relative; margin-left: 3rem; margin-top: 0.3rem;"
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
                    <textarea id="description-input"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer bwcolor">
            <a class="addassign-modal-cancel waves-effect bwcolortext btn-flat">CANCEL</a>
            <a class="addassign-modal-add waves-effect white-text green btn-flat">ADD</a>
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
<script src="js/theme.js"></script>