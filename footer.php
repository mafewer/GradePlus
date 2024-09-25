<?php
$year = date("Y");
?>
<html>
<div class="page-footer">
    <div class="icon-holder">
        <i class="material-icons">copyright</i> Copyright
        <?php echo $year ?> GradePlus
    </div>
</div>
<style>
    div.page-footer {
        background-color: #2e7d32;
        color: white;
        padding: 0.5rem 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        transition: background-color 0.5s, color 0.5s;
    }

    div.icon-holder {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin: 0;
        padding: 0;

        i {
            font-size: 1.3rem;
        }
    }
</style>

</html>