<?php
	class BusDriver {
		private $db;

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__."/../functions.php");
			$this->db = new Database();
		}

			/* function to grab the bus driver data from MySQL
	 * echos back a formatted HTML Bootstrap table of the MySQL return results
	 */
		function getBusDriverBlackout() {
			$sqlQuery = "SELECT * FROM bus_blackout";
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");

			// for($i = 0; $i < sizeof($data); $i++) {
			// 	$driverId = testSQLNullValue($data[$i]['driverID']);
			// 	$date = testSQLNullValue($data[$i]['date']);
			// 	$timeOfDay = testSQLNullValue($data[$i]['timeOfDay']);

			// 	$values = array($date, $timeOfDay);

			// 	$result[$row['name']] = $row['id'];

			// 	$blackouts[$driverId] =
			// }

			return $data;

		}//end getBusDriverData

		function getAllBlackout(){

			$data = $this->getBusDriverBlackout();

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

		//function to get the bus drivers in order of most black out dates to least

		function getMostBlackouts(){
			$sqlQuery = "SELECT driverID FROM bus_blackout GROUP BY driverID ORDER BY COUNT(driverID) desc";

			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");

			return $data;
		}


		//this gets an array of [driverID] => 1, [drivingLimit] => 4
		function getDriverLimits(){
			$sql = "SELECT driverID, drivingLimit FROM bus_driver";

			$data = $this->db->executeQuery($sql, paramsIsZero(), "select");

			return $data;

		}

		function getNumberOfBusDrivers(){
			$sql = "SELECT count(driverID) FROM bus_driver";

			$driverCount = $this->db->executeQuery($sql, paramsIsZero(), "select");

			$numDrivers = $driverCount[0]['count(driverID)'];

			return $numDrivers;
		}

		function getAllDriverNames(){
			$sql = "SELECT driverID, name FROM bus_driver";

			$driverName = $this->db->executeQuery($sql, paramsIsZero(), "select");


			return $driverName;
		}

		/* function to grab the bus driver data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getBusDriverData() {
			$sqlQuery = "SELECT * FROM BUS_DRIVER";
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
			$bigString = "<table class='table'>";
				$bigString .= "<thead>";
					$bigString .= "<tr>";
						$bigString .= "<th scope='col'>#</th>";
						$bigString .= "<th scope='col'>Bus Driver Name</th>";
						$bigString .= "<th scope='col'>Home Phone</th>";
						$bigString .= "<th scope='col'>Cell Phone</th>";
						$bigString .= "<th scope='col'>Email</th>";
						$bigString .= "<th scope='col'>Address</th>";
					$bigString .= "</tr>";
				$bigString .= "</thead>";
				$bigString .= "<tbody>";
					for($i = 0; $i < sizeof($data); $i++) {
						$bigString .= "<tr>";
							$bigString .= "<th scope='row'>".($i+1)."</th>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['name'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['homePhone'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['cellPhone'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['email'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['address'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getBusDriverData

	}//end BusDriver
?>
