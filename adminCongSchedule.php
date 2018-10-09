<?php
    session_start();
    require_once("./inc/top_layout.php");
/*    require_once("./inc/Controller/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();
    var_dump($CongregationSchedule->scheduleCongregations());*/
?>
    <div id="admin-schedule">

    </div>
    <div id="calendar">

    </div>

<?php require_once("./inc/bottom_layout.php"); ?>