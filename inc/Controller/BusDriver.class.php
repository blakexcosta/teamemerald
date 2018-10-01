<?php
	class BusDriver {

		function __construct() {
            require_once(__DIR__."/RequiredObjects.class.php");
            $this->RequiredObjects = new RequiredObjects();
            $this->DB = $this->RequiredObjects->getObject("DB");
            $this->Functions = $this->RequiredObjects->getObject("Functions");
		}

		/* function to grab the bus driver data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getBusDriverBlackout() {
			$sqlQuery = "SELECT * FROM bus_blackout";
			$data = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");

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

		//function to get the bus drivers in order of most black out dates to least
		function getMostBlackouts(){
			$sqlQuery = "SELECT driverID FROM bus_blackout GROUP BY driverID ORDER BY COUNT(driverID) desc";

			$data = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");

			return $data;
		}

		/* function to grab the bus driver data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getBusDriverData() {
			$sqlQuery = "SELECT * FROM BUS_DRIVER";
			$data = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
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
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['name'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['homePhone'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['cellPhone'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['email'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['address'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getBusDriverData

	}//end BusDriver
?>
