<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $updatedData = $_POST['updatedData'];

    $status = array();
    for($i = 0; $i < sizeof($updatedData); $i++) {
        $updateResult = $CongregationSchedule->updateSchedule($updatedData[$i]["startDate"],$updatedData[$i]["congName"],$updatedData[$i]["rotation"]);
        array_push($status,$updateResult);
    }

    foreach($status as &$result) {
        if($result == false) {
            echo "Error!";
        }
    }

    echo json_encode($status);