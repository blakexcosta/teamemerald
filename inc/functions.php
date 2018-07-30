<?php 
	require_once('db.class.php');

	///* FUNCTIONS *///

	/* function to call all types of SQL queries (DELETE, UPDATE, SELECT, etc...)
	 * @param $sqlQuery - the desired MySQL query to be called
	 * @param $params - an associative array if there is one
	 * @params $type - identify type of query (DELETE, UPDATE, SELECT, etc...), helps when identifying a successful update/delete query
	 * @return $result - either the data fetched from select query or status of update/delete query (i.e number of rows of affected)
	 */
	function executeQuery($sqlQuery, $params, $type) {
		try {
			if(sizeof($params) == 0) {
				$db = new Database();
				$conn = $db->getConnection();
				$query = $conn->prepare($sqlQuery);
				if($query->execute()){
	                if($type == "select") {
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
						$conn = null;
						$params = null;
	                	return $result;
					}else {
						$result = $query->rowCount();
						$conn = null;
						$params = null;
						return $result;
					}
	            }
			}else {
				$db = new Database();
				$conn = $db->getConnection();
				$query = $conn->prepare($sqlQuery);
				foreach ($params as $key => &$key_value) {
					$query->bindParam($key, $key_value, PDO::PARAM_STR);
				}
				if($query->execute()){
					if($type == "select") {
						$result = $query->fetchAll(PDO::FETCH_ASSOC);
						$query = null;
						$conn = null;
						$params = null;
	                	return $result;
					}else {
						$result = $query->rowCount();
						$query = null;
						$conn = null;
						$params = null;
						return $result;
					}
	            }
			}
		}catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	/* function to grab the bus driver data from MySQL
	 * echos back a formatted HTML Bootstrap table of the MySQL return results
	 */
	function getBusDriverData() {
		$sqlQuery = "SELECT * FROM BUS_DRIVER";
		$data = executeQuery($sqlQuery, paramsIsZero(), "select");
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

	/* function to grab the congregation coordinator data from MySQL
	 * echos back a formatted HTML Bootstrap table of the MySQL return results
	 */
	function getCongregationCoordinators() {
		$sqlQuery = "SELECT * FROM CONGREGATION_COORDINATOR";
		$data = executeQuery($sqlQuery, paramsIsZero(), "select");
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

	/* function to grab the host congregation data from MySQL
	 * echos back a formatted HTML Bootstrap table of the MySQL return results
	 */
	function getHostCongregationRoster() {
		$sqlQuery = "SELECT * FROM CONGREGATION";
		$data = executeQuery($sqlQuery, paramsIsZero(), "select");
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

	/* function that verifies the user credentials at login
	 * @param $email - the email that the user enters at login
	 * @param $pass - the password that the user enters at login
	 * @return bool - returns either true or false if the credentials correct
	 */
	/*function verifyUser($email, $pass) {
		$sqlQuery = "SELECT email, password FROM USERS WHERE email = :email";
		$params = array(':email' => $email);
		$data = executeQuery($sqlQuery, $params);
		if(sizeof($data) == 0) {
			return false;
		}else {
			if($pass != $data[0]["password"]) {
				return false;
			}else {
				return true;
			}
		}

	}*/

	/* function that verifies the user's email
	 * @param $email - the email that the user enters
	 * @return bool - returns either true or false if the email is correct
	 */
	function verifyEmail($email) {
		$sqlQuery = "SELECT email FROM USERS WHERE email = :email";
		$params = array(':email' => $email);
		$data = executeQuery($sqlQuery, $params, "select");
		if(sizeof($data) == 0) {
			return false;
		}else {
			return true;
		}
	}

	/* function that verifies the user's password
	 * @param $pass - the password that the user enters
	 * @param $email - the email that the user enters
	 * @return bool - returns either true or false if the password is correct
	 */
	function verifyPassword($pass, $email) {
		$sqlQuery = "SELECT password FROM USERS WHERE email = :email";
		$params = array(':email' => $email);
		$data = executeQuery($sqlQuery, $params, "select");
		if(sizeof($data) == 0) {
			return false;
		}else {
			if($pass != $data[0]["password"]) {
				return false;
			}else {
				return true;
			}
		}
	}

	/* function to check if the new password and the confirmed password the user enters matches
	 * @return bool - boolean showing if the two passwords match
	 */
	function confirmPassword($newPass, $confirmedPass) {
		if($newPass === $confirmedPass) {
			return true;
		}else {
			return false;
		}
	}

	/* function to change the password of the respective user
	 * @return bool - boolean showing if password was successfully changed
	 */
	function changePassword($newPass, $email) {
		$sqlQuery = "UPDATE USERS SET password = :pass WHERE email = :email";
		$params = array(':pass' => $newPass, ':email' => $email);
		$result = executeQuery($sqlQuery, $params, "update");
		if($result > 0) {
			return true;
		}else {
			return false;
		}
		
	}

	/* function to set the second param for the executeQuery function as an empty array 
     * @return $params - returns an empty array
	 */
	function paramsIsZero() {
		$params = array();
		return $params;
	}
	
	/* function to test if the MySQL values that was fetech is null
	 * @param $sqlData - data that was fetched from MySQL 
	 * @param String - a string that spells out "None" if the value is null
	 * @return $sqlData - the data that was fetched if it was found to be not null
	 */
	function testSQLNullValue($sqlData) {
		if($sqlData === "NULL") {
			return "None";
		}else {
			return $sqlData;
		}
	}

	function loadCalendarYear($blackoutWeek) {
		$blackoutWeekNumArray = array();
		for($i = 0; $i < sizeof($blackoutWeek); $i++) {
			if(date('w', strtotime($blackoutWeek[$i])) == 0) {
				$ddate = $blackoutWeek[$i];
				$date = new DateTime($ddate);
				$week = $date->format("W");
				$week++;
				if($week == 53) {
					$week = 01;
				}
				array_push($blackoutWeekNumArray, "Weeknumber: $week");
			}else {
				$ddate = $blackoutWeek[$i];
				$date = new DateTime($ddate);
				$week = $date->format("W");
				array_push($blackoutWeekNumArray, "Weeknumber: $week");
			}
		}
		return $blackoutWeekNumArray;
	}

?>