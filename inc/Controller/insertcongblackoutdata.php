<?php
    require_once(__DIR__."./CongregationBlackout.class.php");
    $CongregationBlackout = new CongregationBlackout();

    $congBlackouts = $_POST['congBlackoutData'];
    $email = $_POST['email'];

    $testArray = array("email" => $email);

    echo json_encode($testArray);
