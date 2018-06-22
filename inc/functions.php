<?php 
	require_once('db.class.php');

	///* FUNCTIONS *///

	/* function to call all types of SQL queries (DELETE, UPDATE, SELECT, etc...)
	 * @param $sqlQuery - the desired MySQL query to be called
	 * @param $params - any params used with the query (WHERE somevar = "") 
	 * @return $data - the data fetched from MySQL
	 */
	function executeQuery($sqlQuery, ...$params) {
		if(sizeof($params) == 0) {
			$db = new Database();
			$conn = $db->getConnection();
			$query = $conn->prepare($sqlQuery);
			if($query->execute()){
                $data = $query->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            }
		}/*else {
			$db = new Database();
			$conn = $db->getConnection();
			$query = $conn->prepare($sqlQuery);

		}*/
	}

	function getBusDriverData() {
		$sqlQuery = "SELECT * FROM BUS_DRIVER";
		$data = executeQuery($sqlQuery);
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
	}

	function getCongregationCoordinators() {
		$sqlQuery = "SELECT * FROM CONGREGATION_COORDINATOR";
		$data = executeQuery($sqlQuery);
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
	}

	function getHostCongregationRoster() {
		$sqlQuery = "SELECT * FROM CONGREGATION";
		$data = executeQuery($sqlQuery);
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
	}
	
	function testSQLNullValue($sqlData) {
		if($sqlData === "NULL") {
			return "None";
		}else {
			return $sqlData;
		}
	}

?>