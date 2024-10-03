<?php
$year = date("Y");
?>
<html>
<div class="page-footer">
    <div class="page-footer-holder">
        <div class="icon-holder">
            <i class="material-icons">copyright</i> Copyright
            <?php echo $year ?> GradePlus
        </div>
    </div>
</div>
<style>
    div.page-footer {
        color: white;
        background-color: transparent;
        padding: 0.5rem 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    div.page-footer-holder {
        padding: 0.75rem 1.25rem;
        border-radius: 1rem;
        transition: background-color 0.5s;
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