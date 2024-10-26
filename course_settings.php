<link rel="stylesheet" type="text/css" href="css/styles-course-settings.css">
<div class="course-settings">
    <!--Course Detail Inputs (instructor only)-->
    <div class="course-details">
        <div class="input-field update-code">
            <i class="material-symbols-outlined prefix">key</i>
            <input type="text" name="updatedcode" id="updatedcode">
            <label class="active" for="updatedcode">Course Code</label>
        </div>

        <div class="input-field update-name">
            <i class="material-symbols-outlined prefix">import_contacts</i>
            <input type="text" name="updatedname" id="updatedname">
            <label class="active" for="updatedname">Course Name</label>
        </div>

        <div class="input-field file-field bwcolor update-banner">
            <i class="material-symbols-outlined left">add_photo_alternate</i>
            <button id="update-banner-btn" class="update-banner-btn waves-effect green white-text btn-flat">
                SELECT COURSE BANNER
                <input type="file" name="updatedbanner" id="updatedbanner" accept="image/*" required>
            </button>
            <div class="file-path-wrapper">
                <input class="file-path validate short-input" type="text">
            </div>
        </div>
    </div>

    <!--Update buttons-->
    <div class="settings-footer">
        <div class="left-footer">
            <div class="save">
                <a class="save-course-info green waves-effect white-text btn-flat">
                    SAVE COURSE INFO
                    <i class="material-symbols-outlined left">save</i>
                </a>
            </div>
            <div class="withdraw">
                <p>Withdraw from Course</p>
                <a class="course-withdraw-btn red waves-effect white-text btn-flat">
                    <i class="material-symbols-outlined left">block</i>
                    WITHDRAW
                </a>
            </div>
        </div>
        <div class="right-footer">
            <a class="delete-course-btn waves-effect red white-text btn-flat">
                DELETE COURSE
                <i class="material-symbols-outlined left">delete</i>
            </a>
        </div>
    </div>
    
    <!--Confirmation Modal-->
    <div id="confirm-modal" class="modal bwcolor">
        <div class="modal-content">
            <h4>Confirmation</h4>
            <p>Are you sure you want to withdraw from this course?</p>
        </div>
        <div class="modal-footer bwcolor">
            <a class="agree-btn modal-close red white-text waves-effect waves-green btn-flat">Withdraw</a>
            <a class="cancel-btn modal-close white-text waves-effect waves-green btn-flat">Cancel</a>
        </div>
    </div>
</div>
<script src="js/course-update.js"></script>