<?php
    session_start();
    require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Controller/CongregationSchedule.class.php");
?>
    <div id='calendar'>

    </div>
    <div id="full-calendar">
        <p id="full-calendar-text"></p>
    </div>
    <form action="testFile.php" method="post">
        <div class="form-check">
        </div>
        <button id="show-calendar" name="show-calendar"  type="submit" class="btn btn-primary">Show Calendar</button>
    </form>

<?php require_once("./inc/bottom_layout.php"); ?>