<?php
	class User {
		private $db;

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__."/../functions.php");
			$this->db = new Database();
		}

		/* function to change the password of the respective user
		 * @return bool - boolean showing if password was successfully changed
		 */
		function changePassword($newPass, $email) {
			$bytes = openssl_random_pseudo_bytes ( strlen($newPass) );
			$salt = bin2hex($bytes);
			$hashedPass = hash('sha256', ($newPass.$salt));
			$userID = $this->getUserID($email);

			$sqlQuery = "UPDATE USERS SET password = :pass, salt = :salt WHERE userID = :userID";
			$params = array(':pass' => $hashedPass, ':salt' => $salt, ':userID' => $userID);
			$result = $this->db->executeQuery($sqlQuery, $params, "update");
			if($result > 0) {
				return true;
			}else {
				return false;
			}
			
		}//end changePassword

		/* function to get the user ID from MySQL
		 * @param $email - email of the user
		 * @return $data[0]['userID'] - the user ID from MySQL
		 */
		function getUserID($email) {
			$sqlQuery = "SELECT userID FROM USERS WHERE email = :email";
			$params = array(':email' => $email);
			$data = $this->db->executeQuery($sqlQuery, $params, "select");
			if($data[0]['userID']){
				return $data[0]['userID'];
			}else {
				return null;
			}
		}

		/* function to get the user role from MySQL
		 * @param $email - email of the user
		 * @return $data[0]['userType'] - the user role from MySQL
		 */
		function getUserRole($email) {
			$sqlQuery = "SELECT userType FROM USERS WHERE email = :email";
			$params = array(':email' => $email);
			$data = $this->db->executeQuery($sqlQuery, $params, "select");
			if($data[0]['userType']){
				return $data[0]['userType'];
			}else {
				return null;
			}
		}

		/* function to check if the user needs to change their login password
		 * @param $email - the user's email to help find the salt for the password
		 */
		function needsNewPass($email) {
			$sqlQuery = "SELECT salt FROM USERS WHERE email = :email";
			$params = array(':email' => $email);
			$data = $this->db->executeQuery($sqlQuery, $params, "select");
			if(is_null($data[0]["salt"])) {
				return true;
			}else {
				return false;
			}
		}//end checkIfNewUser

		/* function that verifies the user's email and password
		 * @param $email - the email that the user enters
		 * @param $pass - the password that the user enters
		 * @return bool - returns either true or false if the credentials are correct
		 */
		function verifyCredentials($email, $pass) {
			$sqlQuery = "SELECT email, password, salt FROM USERS WHERE email = :email";
			$params = array(':email' => $email);
			$data = $this->db->executeQuery($sqlQuery, $params, "select");
			if(is_null($data[0]["email"])) {
				return false;
			}

			//Checks to see if the password that was entered was correct even if there is no salt
			if(is_null($data[0]["salt"])) {
				if(sizeof($data) == 0) {
					return false;
				}else {
					if($data[0]["password"] != $pass) {
						return false;
					}else {
						return true;
					}
				}
			}else{
				//Checks to see if the password that was entered was correct when there is salt
				if(sizeof($data) == 0) {
					return false;
				}else {
					$hashedPass = hash('sha256', ($pass.$data[0]["salt"]));
					if($hashedPass != $data[0]["password"]) {
						return false;
					}else {
						return true;
					}
				}
			}
		}//end verifyCredentials

	}//end User
?>