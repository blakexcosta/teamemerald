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