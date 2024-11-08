<link rel="stylesheet" type="text/css" href="css/styles-peer_reviews.css">

<div class = "instructor">
    <p> Peer Reviews are only available to students at this moment. </p>
</div>

<div class = "peer-reviews-main">
    <!--Submitted Assignments -->
    <div class="sub-assignments">
        <h3 class="sub-assignments-header">
            Give Feedback
        </h3>
        <table class="table-sub-assignments">
            <tbody id="table-sub-assignments-body">
                <!--
                <tr>
                    Data Will populate here
                    <td>
                        <div class="card bwcolor">
                            <div class="card-image">
                            <i class="large material-icons">assignment</i>
                            </div>
                        <div class="assignmentName">
                            Assignment Name
                        </div>
                        <div class="studentName">
                            Student Name
                        </div>
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn give-feedback">
                                Give Feedback
                                <i class="material-icons right center">chat</i>
                            </button>
                        </div>
                    </td>
                </tr>
                -->
            </tbody>
        </table>
    </div>

    <!--My Reviewed Assignments -->
    <div class="rev-assignments">
        <h3 class="rev-assignments-header">
            Review Feedback
        </h3>
        <table class="table-rev-assignments">
            <tbody id="table-rev-assignments-body">
                <!--
                <tr>
                     Data Will populate here
                    <td>
                        <div class="card bwcolor">
                            <div class="card-image">
                            <i class="large material-icons">assignment</i>
                            </div>
                        <div class="assignmentName">
                            Assignment Name
                        </div>
                        <div class="card-action">
                            <button class="waves-effect green std-hover waves-light btn view-feedback">
                                View Feedback
                                <i class="material-icons right center">visibility</i>
                            </button>
                        </div>
                    </td>
                </tr>
                -->
            </tbody>
        </table>
    </div>

</div>

<!-- Give Feedback Form-->
<div class="give-feedback-page">
        <div class="review-pdf"></div>
        <div class="review-input">
            <div class="input-field">
                <h6>Feedback</h6>
                <textarea id="feedback-input"></textarea>
            </div>
            <button class="btn-flat waves-effect waves-light give-feedback-close bwcolortext">Cancel</button>
            <button class="btn waves-effect waves-light give-feedback-save">Save</button>
        </div>
</div>

<!--Review Feedback Form-->
<div class="view-feedback-page">
        <div class="review-pdf"></div>
        <div class="review-input">
            <h6>Feedback</h6>
                <ul>
                    <li><p> DEMO FEEDBACK </p></li>
                    <li><p> DEMO FEEDBACK </p></li>
                    <li><p> DEMO FEEDBACK </p></li>
                    <li><p> DEMO FEEDBACK </p></li>
                </ul>
            <button class="btn waves-effect waves-light view-feedback-close bwcolortext">Return</button>
        </div>
</div>

<script src="js/peer-reviews.js"></script>