<link rel="stylesheet" type="text/css" href="css/styles-peer_reviews.css">

<div class = "peer-reviews-main">
    <div class = "peer-instructor">
        <h6 class="instructor-assignments-header">
            Student Reviews
        </h6>
        <div class="instructor-assignments">
            <!--Data will populate here-->
        </div>
    </div>
    <div class="peer-student">
        <!--Available Reviews -->
        <h6 class="sub-assignments-header">
            Give Reviews
        </h6>
        <div class="sub-assignments">
            <!--Data will populate here-->
        </div>
        
        <!--My Reviewed Assignments -->
        <h6 class="rev-assignments-header">
            Received Reviews
        </h6>
        <div class="rev-assignments">
            <!--Data will populate here-->
        </div>
    </div>
</div>

<!-- Give Feedback Form-->
<div class="give-feedback-page">
        <div class="review-pdf"></div>
        <div class="review-input">
            <div class="input-field">
                <h6>Feedback</h6>
                <textarea id="feedback-input" style="background-color: var(--hover-color); color: var(--font-color)"></textarea>
            </div>
            <button class="btn-flat waves-effect waves-light give-feedback-close" style="color: var(--font-color)">Cancel</button>
            <button class="btn waves-effect waves-light give-feedback-save">Save</button>
        </div>
</div>

<!--Review Feedback Form-->
<div class="view-feedback-page">
        <div class="review-pdf"></div>
        <div class="review-input">
            <h6>Feedback</h6>
                <ul>
                    <li style="background-color: var(--bwcolor); color: var(--font-color);"><p></p></li>
                </ul>
            <button class="btn waves-effect waves-light view-feedback-close bwcolortext">Return</button>
        </div>
</div>
<script src="js/peer-reviews.js"></script>