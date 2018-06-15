<?php 
	require_once('inc/db.class.php');

	///* FUNCTIONS *///

	function execute($sqlQuery, ...$params) {
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
		$data = execute($sqlQuery);

		$bigString = "<table class='table'>";
			$bigString .= "<thead>";
				$bigString .= "<tr>";
					$bigString .= "<th scope='col'>#</th>";
					$bigString .= "<th scope='col'>Bus Driver Name</th>";
					$bigString .= "<th scope='col'>Home Phone</th>";
					$bigString .= "<th scope='col'>Cell Phone</th>";
					$bigString .= "<th scope='col'>Email</th>";
				$bigString .= "</tr>";
			$bigString .= "</thead>";
			$bigString .= "<tbody>";
				for($i = 1; $i <= sizeof($data); $i++) {
					$bigString .= "<tr>";
						$bigString .= "<th scope='row'>$i</th>";
						$bigString .= "<td>{$data['name']}</td>";
						$bigString .= "<td>{$data['homePhone']}</td>";
						$bigString .= "<td>{$data['cellPhone']}</td>";
						$bigString .= "<td>{$data['email']}</td>";
					$bigString .= "</tr>";
				}
			$bigString .= "</tbody>";
		$bigString .= "</table>";
		return $bigString;
	}

	/*
	 * Tests the DB connection by running query and getting results back
	 */
	function testConnection() {
		$sqlQuery = "SELECT * FROM BUS_DRIVER";
		$data = execute($sqlQuery);
		return $data;
	}

?>