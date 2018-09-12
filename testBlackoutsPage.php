<?php 
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/BusDriver.class.php");
	require_once("Schedule.class.php");
	// require_once("./inc/Controller/Blackouts.class.php");

	// $blackouts = new Blackouts();
?>

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

			


	function createDraftSchedule(){


		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, 9, 2018);

		$allBlackouts = getAllBlackout();


		
		// for ($i=1;i<$daysInMonth;i++){

			


		// }

	}


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