<?php 
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/BusDriver.class.php");
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
		
		$object = (object) [$date => $timeOfDay];
		
		//if the driverid and the temp are the same
		if ($driverId == $tempDriveriD){
			$blackouts[] = $object;
			$blackout_final[$driverId] = $blackouts;
			echo "first" . $driverId;
		}
		else{
			//create new key for assoc array
			unset($blackouts);
			$blackouts[] = $object;
			$blackout_final[$driverId] = $blackouts;
			echo "second" . $driverId;
			
		} 

		echo "THOIS SDF" . sizeof($blackouts);

		$tempDriveriD = $data[$i]['driverID'];

	}

  echo "<pre>";
  print_r($blackout_final);
  echo "</pre>";

?>

<?php require_once("./inc/bottom_layout.php"); ?>