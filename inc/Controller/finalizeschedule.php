<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotNum = $_POST['rotation_number'];

    $finalizedResult = $CongregationSchedule->finalizeSchedule($rotNum);

    $finalizedResultArr = array(
        "FinalizeResult" => $finalizedResult
    );

    echo json_encode($finalizedResultArr);