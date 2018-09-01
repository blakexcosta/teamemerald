<?php
	class Calendar {
		private $db;

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__."/../functions.php");
			$this->db = new Database();
		}

		/* function to if a date range has a holiday in it for a given year
	     * @param $year - year that has the date range that is being tested
	     * @param $sundayOfRotation - the start of the date range being tested
	     * @param $saturdayOfRotation - the end of the date range being tested
	     * @return $isThereAHoliday - returns true or false if there is a holiday inside the tested date range
	     */
	    function checkCongregationHolidays($year, $sundayOfRotation, $saturdayOfRotation) {
	    	$easterDate = date("Y-m-d",$this->get_easter_datetime($year)->getTimestamp()); //date will be incremented in a loop
			$memorialDayDate = date("Y-m-d", strtotime("last monday of may ".$year));
			$independenceDayDate = date("Y-m-d",strtotime("4 july ".$year));
			$laborDayDate = date("Y-m-d",strtotime("first monday of september ".$year));
			$thanksgivingDate = date("Y-m-d",strtotime("fourth thursday of november ".$year));
			$christmasDate = date("Y-m-d",strtotime("25 december ".$year));

			$holidayArray = array($easterDate, $memorialDayDate, $independenceDayDate, $laborDayDate, 
									$thanksgivingDate, $christmasDate);

			$isThereAHoliday = false;
			for($i = 0; $i < sizeof($holidayArray); $i++) {
				if($sundayOfRotation <= $holidayArray[$i] && $holidayArray[$i] <= $saturdayOfRotation) {
					$isThereAHoliday = true;
					break;
				}
			}
			return $isThereAHoliday;
	    }//end checkCongregationHolidays

	    /* function that finds Easter for any given year
		 * @param $year - the year that you want to find Easter in
		 * @return $base - non timestamp value of Easter for a given year
		 */
		function get_easter_datetime($year) {
	        $base = new DateTime("$year-03-21");
	        $days = easter_days($year);

	        return $base->add(new DateInterval("P{$days}D"));
	    }//end get_easter_datetime

	    /* function to get the highest rotation number in MySQL
	     * @return $data[0]['MAX(rotation_number)'] - the maximum rotation number
	     */
	    function getMaximumRotationNumber() {
	    	$sqlQuery = "SELECT MAX(rotation_number) FROM DATE_RANGE"; 
	    	$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
	    	if($data[0]['MAX(rotation_number)']) {
	    		return $data[0]['MAX(rotation_number)'];
	    	}else {
	    		return null;
	    	}
	    }//end getMaximumRotationNumber
 
	    /* function to get the lowest rotation number in MySQL
	     * @return $data[0]['MIN(rotation_number)'] - the minimum rotation number
	     */
	    function getMinimumRotationNumber() {
	    	$sqlQuery = "SELECT MIN(rotation_number) FROM DATE_RANGE"; 
	    	$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
	    	if($data[0]['MIN(rotation_number)']) {
	    		return $data[0]['MIN(rotation_number)'];
	    	}else {
	    		return null;
	    	}
	    }//end getMinimumRotationNumber

	    /* function to calculate the total number of weeks inputted into the database
	     * @param $startYear - the start year for the number of weeks needed 
	     *						comes from figuring out the start year of the next rotation needed
	     * @param $finalYear - the calculated end year of the number of weeks needed 
	     * @param $startDateNxtRotation - the start date of the next rotation needed
	     */
	    function getTotalNumOfWks($startYear, $finalYear, $startDateNxtRotation) {
	    	$startingWkNum = date('W', strtotime($startDateNxtRotation));
	    	if($this->has53Weeks($startYear) == true){
	    		$startingWkNum = 1;
	    	}else {
	    		$startingWkNum++;
	    	}
	    	$totalWeeks = 0;
	    	while($startYear <= $finalYear) {
	    		$yearHas53 = $this->has53Weeks($startYear);
	    		if($yearHas53 == true) {
	    			$totalWeeks+=53;
	    		}else {
	    			$totalWeeks+=52;
	    		}
				$startYear++;
	    	}
	    	$finalTotal = $totalWeeks - $startingWkNum;
	    	return $finalTotal;
	    }//end getTotalNumOfWks

	    /* function that tests if a given year has 53 weeks
	     * @param $year - the desired year to be tested
	     * @param boolean - true or false if the tested year has 53 weeks
	     */
	    function has53Weeks($year){
	    	$daysInFebruary = cal_days_in_month(CAL_GREGORIAN, 2, $year); 
			$dayYearStartsOn = new DateTime();
			$dayYearStartsOn->setTimestamp(mktime(0,0,0,1,1,$year));
			$dayOfTheWeek = $dayYearStartsOn->format("w");
			if($dayOfTheWeek == 4 || ($dayOfTheWeek == 3 && $daysInFebruary == 29)) {
				return true;
			}else {
				return false;
			}
	    }//end has53Weeks

	    function insertCalendarEvent($blackoutWeekNumArray) {
			$selectQuery = "SELECT congID FROM CONGREGATION_COORDINATOR WHERE coordinatorEmail = :coorEmail";
			$params = array(':coorEmail' => $_SESSION['email']);
			$data = $this->db->executeQuery($selectQuery, $params, "select");

			/*for($i = 0; $i < sizeof($blackoutWeekNumArray); $i++ ){*/
				$insertQuery = "INSERT INTO CONGREGATION_BLACKOUT (congID, weekNumber, startDate) VALUES (:congID, :weekNum, :startDate)";
				$params2 = array(':congID' => $data[0]['congID'], ':weekNum' => $blackoutWeekNumArray[0], ':startDate' => '2018-01-08');
				$result = $this->db->executeQuery($insertQuery, $params2, "insert");
				if($result == 0) {
					return $params2;
				}
			/*}*/
			return true;			
		}//end insertCalendarEvent

	    /* function to insert date range data to the date_range table in MySQL
	     * @param $strtDateNxtRotation - the start date of the next rotation needed
	     * @param $numberOfYears - the number of years worth data that's wanted to be inserted
	     * @param $startYear - the start year for the number of weeks needed 
	     *						comes from figuring out the start year of the next rotation needed
	     * @param $nxtRotationNumber - th
	     */
	    function insertDateRange($strtDateNxtRotation, $numberOfYears, $startYear, $nxtRotationNumber) {
			$dateOfMonth = new DateTime();
			$dateOfMonth->setTimestamp(strtotime($strtDateNxtRotation)); //parameter
			$sundayOfRotation = $dateOfMonth->format("Y-m-d");

			$totalRotations = $this->getTotalNumOfWks($startYear, ($startYear + $numberOfYears), $sundayOfRotation) / 13;
			$totalRotationsRounded = round($totalRotations, 0, PHP_ROUND_HALF_DOWN);

			$insertDataMsg;
			$finalRotation = $nxtRotationNumber + ($totalRotationsRounded - 1);
			$x = 1;
			while($x <= $totalRotationsRounded) {
				for($i = 1; $i <= 13; $i++){
					$saturdayOfRotation = date("Y-m-d", strtotime("+6 day",strtotime($sundayOfRotation)));
					if($sundayOfRotation <= ($startYear."-12-31") && ($startYear."-12-31") <= $saturdayOfRotation) {
						$startYear++;
					}
					$isThereAHoliday = $this->checkCongregationHolidays($startYear, $sundayOfRotation, $saturdayOfRotation);
					if($isThereAHoliday) {
						$insertQuery = "INSERT INTO date_range VALUES (:weekNum, :startDate, :endDate, :holiday, :rotNum)";
						$params = array(':weekNum' => $i, ':startDate' => $sundayOfRotation, ':endDate' => $saturdayOfRotation,
											':holiday' => 1, ':rotNum' => $nxtRotationNumber);
						$result = $this->db->executeQuery($insertQuery, $params, "insert");
						if($result == 0) {
							$insertDataMsg = "Error";
						}
					}else {
						$insertQuery = "INSERT INTO date_range VALUES (:weekNum, :startDate, :endDate, :holiday, :rotNum)";
						$params = array(':weekNum' => $i, ':startDate' => $sundayOfRotation, ':endDate' => $saturdayOfRotation,
											':holiday' => 0, ':rotNum' => $nxtRotationNumber);
						$result = $this->db->executeQuery($insertQuery, $params, "insert");
						if($result == 0) {
							$insertDataMsg = "Error";
						}	
					}
					$sundayOfRotation = date("Y-m-d", strtotime("+7 day",strtotime($sundayOfRotation)));
				}
				$nxtRotationNumber++;
				$x++;
			}
			if($insertDataMsg == "Error") {
				return false;
			}else {
				return true;
			}
		}//end insertDateRange

		/* function to grab selected blackout weeks from the user end
		 */
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
		}//end loadCalendarYear

		/* function that prints out the black out weeks for each rotation number
		 * @return $bigString - formatted string of all the blackout week choices
		 */
		function showBlackoutWeeks() {
			$sqlQuery = "SELECT * FROM DATE_RANGE ORDER BY rotation_number";
			//$params = array(':rotationNum' => $this->getMinimumRotationNumber());
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
			return $data;
		}

		function validateDate($date, $format) {
		    $d = DateTime::createFromFormat($format, $date);
		    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits, changing the comparison from == to === fixes the issue.
		    return $d && $d->format($format) === $date;
		}//end validateDate
		
	}
?>