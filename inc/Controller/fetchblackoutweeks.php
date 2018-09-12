<?php
	require_once("./Calendar.class.php");
	$calendar = new Calendar();

	$data = $calendar->showBlackoutWeeks();

	//Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($data);
?>