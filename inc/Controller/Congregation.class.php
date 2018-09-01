<?php
	class Congregation {
		private $db;
		
		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__."/../functions.php");
			$this->db = new Database();
		}

		/* function to grab the congregation coordinator data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getCongregationCoordinators() {
			$sqlQuery = "SELECT * FROM CONGREGATION_COORDINATOR";
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
			$bigString = "<table class='table'>";
				$bigString .= "<thead>";
					$bigString .= "<tr>";
						$bigString .= "<th scope='col'>#</th>";
						$bigString .= "<th scope='col'>Coordinator Name</th>";
						$bigString .= "<th scope='col'>Coordinator Phone</th>";
						$bigString .= "<th scope='col'>Coordinator Email</th>";
					$bigString .= "</tr>";
				$bigString .= "</thead>";
				$bigString .= "<tbody>";
					for($i = 0; $i < sizeof($data); $i++) {
						$bigString .= "<tr>";
							$bigString .= "<th scope='row'>".($i+1)."</th>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['coordinatorName'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['coordinatorPhone'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['coordinatorEmail'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getCongregationCoordinators

		/* function to grab the host congregation data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getHostCongregationRoster() {
			$sqlQuery = "SELECT * FROM CONGREGATION";
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
			$bigString = "<table class='table'>";
				$bigString .= "<thead>";
					$bigString .= "<tr>";
						$bigString .= "<th scope='col'>#</th>";
						$bigString .= "<th scope='col'>Congregation Name</th>";
						$bigString .= "<th scope='col'>Congregation Address</th>";
						$bigString .= "<th scope='col'>Comments</th>";
					$bigString .= "</tr>";
				$bigString .= "</thead>";
				$bigString .= "<tbody>";
					for($i = 0; $i < sizeof($data); $i++) {
						$bigString .= "<tr>";
							$bigString .= "<th scope='row'>".($i+1)."</th>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['congName'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['congAddress'])."</td>";
							$bigString .= "<td>".testSQLNullValue($data[$i]['comments'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getHostCongregationRoster

	}//end Congregation
?>