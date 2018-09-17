<!DOCTYPE html>
<html lang="en">





<?php
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/BusDriver.class.php");
	require_once("Schedule.class.php");
	require_once("CalendarBus.class.php");
	// require_once("./inc/Controller/Blackouts.class.php");

	// $blackouts = new Blackouts();
?>



	<iframe src="https://calendar.google.com/calendar/embed?src=raihnbusdriver%40gmail.com&ctz=America%2FNew_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>


</html>

<?php
	// $data = $blackouts->getCongBlackouts();
	// for ($i=0; $i < sizeof($data); $i++) {
	// 	var_dump($data[$i]);
	// }
	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}





	// echo "<pre>";
	// print_r(getAllBlackout());
	// echo "<pre>";

			//testing for Sept, 2018
			$schedule = new Schedule();


// array
// 	2018-09-01AM -> {driverID, date,timeOfDay}

		$bus = new BusDriver();

		//this gets an array of the drivers with the most to least blackout dates
		$data = $bus->getMostBlackouts();

		echo "<pre>";
		print_r ($data);
		echo "</pre>";


		$list=array();
		$month = 9;
		$year = 2018;
		for($d=1; $d<=31; $d++)
		{
		    $time=mktime(12, 0, 0, $month, $d, $year);
		    if (date('m', $time)==$month)
		        $list[]=date('Y-m-d', $time);
		}
		// echo "<pre>";
		// //var_dump($list);
		// echo "</pre>";

	//this is a large function that creates the draft schedule

	$draftSchedulePass = createDraftSchedule(9,2018, $list);



	$calendarBus = new CalendarBus();

	$calendarBus->scheduleDrivers($draftSchedulePass);


	function createDraftSchedule($monthNumber, $year, $list){


		$allBlackouts = getAllBlackout();
		$draftSchedule = array();
		// echo "<pre>";
		// print_r ($allBlackouts);
		// echo "<pre>";
		 for ($i=1;$i<sizeof($list)+1;$i++){


			 // this is our key $list[$i-1];
			 //Get the key of $list[$i-1] = orig."AM"
			 //loop through each day while checking if they are blacked out for that day.
			 //this will go through all the first driver's blackout date

			 if (sizeof($allBlackouts[9]) == 0){
				 $draftSchedule[$list[$i-1]] = [9, $list[$i-1]];
			 }

			 for($j=0;$j<sizeof($allBlackouts[9]);$j++){
				 $currentBlackoutDay = (int)(substr($allBlackouts[9][$j]->{'date'}, strrpos($allBlackouts[9][$j]->{'date'}, '-') + 1));

				//check if primary== backup$
				 // checks if the key exists in the draft schedule (if the day is already scheduled)
				 //(array_key_exists($list[$i-1], $draftSchedule)))

				  //if they have a blackout date that matches the current day of the month, don't schedule them! and move on to the next $i
				 if($currentBlackoutDay == $i) {
					 // echo " IF:: currentBlackoutDay ".$currentBlackoutDay . " AND " . $i . " second condition: ". (array_key_exists($list[$i-1], $draftSchedule));
					 // echo "<br>";
					 // echo " IF:: currentBlackoutDay ".$currentBlackoutDay . " AND " . $i;
					 // echo "<br>";

					 //remove that blackout date
					 unset($allBlackouts[9][$j]);
					 $allBlackouts[9] = array_values($allBlackouts[9]);

					 break;
				 }
				 //they are free on that date, schedule them
				 else{
					 // echo " ELSE:: currentBlackoutDay ".$currentBlackoutDay . " AND " . $i;
					 // echo "<br>";
					 //date_create($i+$monthNumber+$year);
					 // echo "<pre>";
					 // print_r($list[$i-1]);
					 // echo "<pre>";
					 /*
					 * 2 is hard coded which stands for BusDriverID
					 */
					 $draftSchedule[$list[$i-1]] = [9, $list[$i-1]];

					 break;
				 }

			 }//end of inner for loop-1

		 } //end of for outer loop


		 // echo "<pre>";
		 // print_r ($draftSchedule);
		 // echo "<pre>";

		 return $draftSchedule;


	} //end of function create draft schedule






	//$blackouts3 = getAllBlackout();
	//print_r( $blackouts3[1]);



	function getAllBlackout(){
		$busdriver = new BusDriver();

		$data = $busdriver->getBusDriverBlackout();

		//associative array with driverID mapped to array
		$blackout_final = array();

		$tempDriveriD = -5;

		$blackouts = array();

		for($i = 0; $i < sizeof($data); $i++) {
			$driverId = testSQLNullValue($data[$i]['driverID']);
			$date = testSQLNullValue($data[$i]['date']);
			$timeOfDay = testSQLNullValue($data[$i]['timeOfDay']);

			//$object = (object) [$date => $timeOfDay];
			$object = (object) ['date' => $date, 'timeof' => $timeOfDay];


			//if the driverid and the temp are the same
			if ($driverId == $tempDriveriD){
				$blackouts[] = $object;
				$blackout_final[$driverId] = $blackouts;
			}
			else{
				//create new key for assoc array
				unset($blackouts);
				$blackouts[] = $object;
				$blackout_final[$driverId] = $blackouts;
			}



			$tempDriveriD = $data[$i]['driverID'];

		}


		return $blackout_final;
	}




//   echo "<pre>";
//   print_r($blackout_final);
//   echo "</pre>";

?>

<?php require_once("./inc/bottom_layout.php"); ?>
