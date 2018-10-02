<?php
	class Congregation {
		private $db;

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__."/../functions.php");
			$this->db = new Database();
		}

		/* function to get all the congregations from MySQL
		 * @return $data - MySQL data holding all the congregations
		 * @return null - return nothing if no data was returned
		 * */
		function getCongregations() {
            $sqlQuery = "SELECT * FROM congregation";
            $data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
            if($data) {
                return $data;
            }else {
                return null;
            }
        }

        /* function to get a congregation name from MySQL
         * @param $congID - the congregation ID that will be used to help find the name
		 * @return $data - data from MySQL holding congregation name
		 * @return null - return nothing if no data was returned
		 * */
        function getCongregationName($congID) {
		    $sqlQuery = "SELECT congName FROM congregation WHERE congID = :congID";
		    $params = array(":congID" => $congID);
		    $data = $this->db->executeQuery($sqlQuery, $params, "select");
            if($data) {
                return $data[0]["congName"];
            }else {
                return null;
            }
        }//end getCongregationName

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

        /* function that will insert a newly scheduled congregation to congregation_schedule in MySQL
         * @param $congID - the ID of the congregation to be inserted
         * @param $startDate - the start date of the week the congregation is scheduled for
         * @param $weekNumber - the rotation week number that congregation is scheduled for
         * @param $rotationNumber - the rotation number of the week the congregation is scheduled for
         * @return bool - return true or false depending on if the data was inserted
         * */
        function insertNewScheduledCong($congID, $startDate, $weekNumber, $rotationNumber) {
            $sqlQuery = "INSERT INTO congregation_schedule VALUES (:congID, :startDate, :weekNumber, :rotNum)";
            $params = array(":congID" => $congID, ":startDate" => $startDate,
                            ":weekNumber" => $weekNumber, ":rotNum" => $rotationNumber);
            $result = $this->db->executeQuery($sqlQuery, $params, "insert");
            if($result > 0) {
                return true;
            }else {
                return false;
            }
        }//end insertNewScheduledCong

	}//end Congregation
?>
