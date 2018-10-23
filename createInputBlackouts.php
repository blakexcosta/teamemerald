<?php
require_once("Schedule.class.php");
  require_once("./inc/Controller/BusDriver.class.php");
  require_once("./inc/Controller/CalendarBus.class.php");
  require_once("./inc/Controller/Blackouts.class.php");

$scheduleoutline = new Schedule(10, 2018,10);
$monthOutline = $scheduleoutline->getDaysInMonth();

//Array ( [0] => 2018-10-01AM [1] => 2018-10-01PM [2] => 2018-10-02AM [3] => 2018-10-02PM [4]
//print_r($monthOutline);


//$blankInputSchedule = array();

// foreach ($monthOutline as $key => $value) {
//
// 		$date = substr($value,0,10);
// 		$timeOf = substr($value,10,2);
// 		$realTime = "";
// 		$color;
//
// 		if($timeOf =='AM'){
// 				$realTime = "T09:00:00";
// 		}
// 		else{
// 				$realTime = "T18:00:00";
// 		}
//
// 		$driver = array(
// 				"title" => $timeOf,
// 				"start" => $date . $realTime,
// 				"end" => $date . $realTime
//
// 		);
//
// 		array_push($blankInputSchedule, $driver);
//
//
// } //for each

//echo json_encode($blankInputSchedule);

?>
