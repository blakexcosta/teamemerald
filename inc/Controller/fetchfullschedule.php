<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    require_once(__DIR__."/Functions.class.php");
    $CongregationSchedule = new CongregationSchedule();
    $Functions = new Functions();

    $fullSchedule = $CongregationSchedule->createCompleteSchArray();
    $sortedDates = $Functions->sort2DArray($fullSchedule, "start");

    //Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($sortedDates);