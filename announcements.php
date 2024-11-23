<link rel="stylesheet" type="text/css" href="css/styles-announcements.css">
<body>
<!-- Add Announcement Modal -->
<div id="add-announcement-modal" class="add-announcement modal" style="background-color: var(--bwcolor); color: var(--font-color);">
    <div class="add-announcement-content">
    <h4>Add Announcement</h4>
        <div class="input-field">
            <i class="material-symbols-outlined prefix">notifications</i>
            <input id="announcement-header" name="announcement-header" type="text">
            <label for="announcement-header">Announcement Title</label>
        </div>
        <div class="input-field">
            <i class="material-symbols-outlined prefix">edit_note</i>
            <input id="announcement-text" name="announcement-text" type="text">
            <label for="announcement-text">Description</label>
        </div>
    </div>
    <div class="add-announcement-footer" style="display: flex; justify-content:end; background-color: var(--bwcolor); color: var(--font-color);">
        <a class="announcement-cancel-btn modal-close waves-effect waves-green btn-flat" style="color: var(--font-color)">Cancel</a>
        <a class="announcement-add-btn modal-close green white-text waves-effect waves-green btn-flat">Add</a>
    </div>
</div>
<div class="announcements-main">
    <!--Available Reviews -->
    <h6 class="no-announcements-header">
        No Announcements Posted
    </h6>
    <a class="add-announcement modal-close green white-text waves-effect waves-green btn-flat"><i class="material-symbols-outlined left">add_circle</i>Add</a>
    <div class="announcements-container">
        <!--Data will populate here-->
    </div>
</div>
</body>
<script src="js/announcements.js"></script>