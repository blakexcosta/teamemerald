<?php
	class Blackouts {
		private $db;
		
		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
            require_once(__DIR__."/Calendar.class.php");
            require_once(__DIR__."/Congregation.class.php");
			require_once(__DIR__."/../functions.php");
            require_once(__DIR__."/GoogleCalendar.php");
			$this->db = new Database();
			$this->calendar = new Calendar();
			$this->congregation  = new Congregation();
			$this->gCalendar = new GoogleCalendar();

            $this->client = $this->gCalendar->getClient();
            $this->service = new Google_Service_Calendar($this->client);
		}

        /* function that counts the number entries for each entry of a specified column in MySQL
         * @param $array - the array of data from MySQL
         * @param $initialVal - an initial, starting value to use for comparison in the function
         * @param $key - the name of the key that will be used in the created associative array
         * @param $value - the name of the value that will be used in the created associative array
         * @return $countedArray - an array with each of the MySQL data entries counted in an associative array
         * */
        function countValues($array, $initialVal, $key, $value) {
            $countedArray = array();
            $comparableCongID = $initialVal;
            $congBlackoutCount = 0;
            for($i = 0; $i < sizeof($array); $i++) {
                if ($comparableCongID == $array[$i][$key]) {
                    $congBlackoutCount++;
                    if($i == (sizeof($array)-1)) {
                        $singleCount = [$key => $comparableCongID,
                            $value => $congBlackoutCount];
                        array_push($countedArray, $singleCount);
                    }
                } else {
                    $singleCount = [$key => $comparableCongID,
                        $value => $congBlackoutCount];
                    array_push($countedArray, $singleCount);
                    $comparableCongID = $array[$i][$key];
                    $congBlackoutCount = 1;
                    if($i == (sizeof($array)-1)) {
                        $singleCount = [$key => $comparableCongID,
                            $value => $congBlackoutCount];
                        array_push($countedArray, $singleCount);
                    }
                }
            }

            return $countedArray;
        }//end countValues

        /* function that gets blackout weeks for one congregation
         * @param $id - the id of congregation
         * @return $data - the blackout week data fetched from MySQL
         * @return null - return nothing if no data was fetched
         * */
        function getBlackoutsForOneCongregation($id) {
            $sqlQuery = "SELECT * FROM congregation_blackout WHERE congID = :congID";
            $params = array(':congID' => $id);
            $data = $this->db->executeQuery($sqlQuery, $params, "select");
            if($data) {
                return $data;
            }else {
                return null;
            }
        }//end getBlackoutsForOneCongregation

        /* function that sorts an associative array from greatest to least value
		 * @param $array - the chosen array to be sorted
		 * @param $key - the name of the key that will be used to help compare two values in the associative array
		 * @param $value - the name of the value that be used to help compare two values in the associative array
		 * @return $array - the chosen array but sorted from greatest to least
		 * */
        function sortArray($array, $key, $value) {
            for($i = 0; $i < sizeof($array); $i++) {
                for($h = 0; $h < sizeof($array) - $i - 1; $h++) {
                    if ($array[$h][$value] < $array[$h + 1][$value]) {
                        $tempID = $array[$h][$key];
                        $tempCount = $array[$h][$value];
                        $array[$h][$key] = $array[$h + 1][$key];
                        $array[$h][$value] = $array[$h + 1][$value];
                        $array[$h + 1][$key] = $tempID;
                        $array[$h + 1][$value] = $tempCount;
                    }
                }
            }
            return $array;
        }//end sortArray

		//First, grab congregations and their blackout dates
        /* function that fetches all data from congregation_blackouts
         * @param $orderByVar - variable used to help order the incoming select query
         * @return $data - if data was successfully fetched return the data
         * @return null - return no data if no data successfully fetched
         * */
		function getCongBlackouts($orderByVar) {
			$sqlQuery = "SELECT * FROM congregation_blackout ORDER BY ".$orderByVar;
			$data = $this->db->executeQuery($sqlQuery, paramsIsZero(), "select");
			if($data) {
				return $data;
			}else {
				return null;
			}
		}//end getCongBlackouts

		//Second, loop through all congregations with their blackout dates and
				//count out each date that's blacked out

        //Account for Congregations that don't have blackout dates
        /* function that gets the blackouts entered, counts the number of dates each congregation blacked out, then sorts them
         * function helps determine which congregation has the most blackout/unavailability week
         * @return $sortedBlackouts - sorted number of blackout dates entered for each congregation
         * */
		function getCongBlackoutCount() {
			$data = $this->getCongBlackouts("congID");
			$countedData = $this->countValues($data,$data[0]["congID"],"congID","count");
			$sortedBlackouts = $this->sortArray($countedData,"congID","count");
            return $sortedBlackouts;
		}//end getBlackouts

		//Forth, check to see if more than 5 host congregations have a week blacked out
				//Schedule that week first
        /* function that checks to see if a blackout week has more than 5 congregations blacking it out
         * @return $datesMoreThanFive - array holding start dates for blackout weeks with more than 5 congregations blacking it out
         * */
		function dateBlackoutCount() {
            $data = $this->getCongBlackouts("startDate");
            $countedBlackedOutDates = $this->countValues($data,$data[0]["startDate"],"startDate", "count");
            $sortedDates = $this->sortArray($countedBlackedOutDates,"startDate","count");
            return $sortedDates;
		}//end moreThan5Congregations

        /* Hard coded function
         * */
        function insertBlackout($blackoutWeekArr) {
		    $weekNumber = $this->calendar->getWeekNumber($blackoutWeekArr[0]);
		    $congID= 1;
		    $sqlQuery = "INSERT INTO congregation_blackout VALUES (:congID, :weekNumber, :startDate)";
		    $params = array(":congID" => $congID, ":weekNumber" => $weekNumber, ":startDate" => $blackoutWeekArr[0]);
		    $result = $this->db->executeQuery($sqlQuery, $params, "insert");
		    if($result > 0) {
		        return true;
            }else {
		        return false;
            }
        }

		//Fifth, start at the first week, looking first at the congregation who has the most blacked out weeks
				//Compare the week they're about to be scheduled for to the last week they were scheduled 
					//Make sure that the week they're about to be scheduled for is at least 10 weeks apart from the last time they were scheduled
						//If the week is scheduled at least 10 weeks apart:
							//If there's a holiday for that week:
								//Check if the congregation did the holiday last
									//If the congregation hasn't already done the holiday:
										//insert the holiday into the Congregation table for the respective congregation
									//Else: 
										//don't schedule the congregation
							//Else:
								//Schedule the congregation
						//Else: return false

				//Then go to the next most blacked out congregation
		function scheduleCongregations() {
            $dateRanges = $this->calendar->getDateRanges("rotation_number");
            $blackoutDateRanges = $this->getCongBlackouts("congID");

            //Congregation blackout count data sorted
            $congregationBlackoutCount = $this->getCongBlackoutCount();
            $congregationData = $this->congregation->getCongregations();

            //Date blackout count data sorted
            $dateBlackoutCount = $this->dateBlackoutCount();

            $mostBlackedOutCong = $congregationBlackoutCount[0]["congID"];
            $totalRotations = $this->calendar->getTotalNumberOfRotations();

            $finalHostCongScheduleArr = false;
            for ($i = 0; $i < sizeof($dateBlackoutCount); $i++) {
                for ($h = 0; $h < sizeof($congregationBlackoutCount); $h++) {
                    //Get an array of a single congregation's list of blackout weeks

                    //The array is used to help figure out if the date we're trying to schedule for is already blacked
                    // out by the congregation

                    $singleCongBlackouts = $this->getBlackoutsForOneCongregation($congregationBlackoutCount[$h]["congID"]);
                    $noConflictingDate = true;
                    for ($j = 0; $j < sizeof($singleCongBlackouts); $j++) {
                        //If the congregation we're looking at doesn't have a
                        // blackout date that is the date we're trying to schedule for
                        if($dateBlackoutCount[$i]["startDate"] == $singleCongBlackouts[$j]["startDate"]) {
                            //If the date we're trying to schedule for IS one of the blackout dates for the
                            // congregation we're trying to schedule, break the loop and move onto the next congregation
                            $noConflictingDate = false;
                            break;
                        }
                    }

                    if($noConflictingDate == true) {
                        $blackoutWeekStart = "2018-09-16";
                        $blackoutWeekEnd = date("Y-m-d",strtotime("+6 days",strtotime($blackoutWeekStart)));

                        $event = new Google_Service_Calendar_Event(array(
                            'summary' => 'Test Event',
                            'location' => '123 Fake Street',
                            'description' => 'This is a test event',
                            'start' => array(
                                'timeZone' => 'America/New_York',
                                'dateTime' => $blackoutWeekStart.'T00:00:00',
                            ),
                            'end' => array(
                                'timeZone' => 'America/New_York',
                                'dateTime' => $blackoutWeekEnd.'T23:59:59',
                            ),
                        ));
                        $calendarId = 'raihncongregation@gmail.com';
                        $event = $this->service->events->insert($calendarId, $event);
                        $finalHostCongScheduleArr = true;
                        break 2;
                    }
                    /*//If the congregation we're trying to schedule has no matching blackout date with the date we're
                    // looking at, move onto the next step
                    if($noConflictingDate == true) {
                        //Test to see if the congregation was scheduled within a 10 week span of the date we're trying to schedule
                        $scheduledAtLeast10Apart = true;
                        for ($k = 1; $k < 10; $k++) {
                            $weeksToSubtract = "-" . $k . " weeks";
                            $priorDate = date("Y-m-d", strtotime($weeksToSubtract, strtotime($dateBlackoutCount[$i]["startDate"])));
                            $priorDateData = $this->calendar->getSingleLegacyData($priorDate);
                            if (is_null($priorDateData) == false) {
                                if ($priorDateData[0]["congID"] == $singleCongBlackouts[0]["congID"]) {
                                    $scheduledAtLeast10Apart = false;
                                    break;
                                }
                            }
                        }

                        //If the date we're trying to schedule for is at least 10 weeks apart
                        // from the last time the congregation was scheduled, move on to the next step
                        //Else, break the for loop and move on to the next congregation
                        if ($scheduledAtLeast10Apart == true) {
                            $year = substr($dateBlackoutCount[$i]["startDate"], 0, 4);
                            $endDate = date("Y-m-d", strtotime("+6 days", strtotime($dateBlackoutCount[$i]["startDate"])));
                            $dateIsAHoliday = $this->calendar->containsHoliday($year, $dateBlackoutCount[$i]["startDate"], $endDate);
                            //If the date is not a holiday, schedule the congregation for that holiday
                            //If it is a holiday, check if the congregation was scheduled the holiday the year before
                            if ($dateIsAHoliday == false) {
                                $congID = $congregationBlackoutCount[$h]["congID"];
                                $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                                $scheduledEndDate = $endDate;
                                $scheduledWeekNum = $this->calendar->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                                $scheduledRotationNum = $this->calendar->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                                //Create array holding the information of the congregation about to be scheduled
                                $congregationScheduledArr = array(
                                    "congName" => $this->congregation->getCongregationName($congID),
                                    "startDate" => $scheduledStartDate,
                                    "weekNumber" => $scheduledWeekNum,
                                    "rotationNumber" => $scheduledRotationNum
                                );
                                //Push array of congregation info into larger array that will contain all scheduled congregations
                                array_push($finalHostCongScheduleArr, $congregationScheduledArr);
                                $insertedIntoCongSch = $this->congregation->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum);
                                $insertedIntoLegacy = $this->calendar->insertLegacyData($congID, $scheduledStartDate, $scheduledEndDate,
                                    0, $scheduledRotationNum);
                                if (true) {
                                    unset($congregationBlackoutCount[$h]);
                                    $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                    unset($dateBlackoutCount[$i]);
                                    $dateBlackoutCount = array_values($dateBlackoutCount);

                                    break;
                                } else {
                                    //No!!
                                }
                            } else {
                                //First, identify which holiday it is
                                $holidayName = $this->calendar->identifyHoliday($year, $dateBlackoutCount[$i]["startDate"]);
                                $holidayArray = array("Easter", "Memorial", "Independence", "Labor", "Thanksgiving", "Christmas");
                                $priorYear = date("Y", strtotime("-1 year", strtotime($year)));
                                $holidayAYearAgo = date("Y-m-d");
                                foreach ($holidayArray as $holiday) {
                                    if ($holiday == $holidayName) {
                                        if ($holidayName == "Easter") {
                                            $holidayAYearAgo = $this->calendar->get_easter_datetime($priorYear);
                                        } elseif ($holidayName == "Memorial") {
                                            $holidayAYearAgo = $this->calendar->getMemorialDay($priorYear);
                                        } elseif ($holidayName == "Independence") {
                                            $holidayAYearAgo = $this->calendar->getIndependenceDay($priorYear);
                                        } elseif ($holidayName == "Labor") {
                                            $holidayAYearAgo = $this->calendar->getLaborDay($priorYear);
                                        } elseif ($holidayName == "Thanksgiving") {
                                            $holidayAYearAgo = $this->calendar->getThanksgiving($priorYear);
                                        } elseif ($holidayName == "Christmas") {
                                            $holidayAYearAgo = $this->calendar->getChristmas($priorYear);
                                        } else {
                                            break;
                                        }
                                    }
                                }


                                //Next, see if the congregation was scheduled for that holiday a year ago
                                $data = $this->calendar->getSingleLegacyData($holidayAYearAgo);

                                //If the congregation was scheduled for the holiday a year ago, move on to the next congregation
                                //Else, schedule the congregation for that holiday
                                if ($data[0]["congID"] == $congregationBlackoutCount[$h]["congID"]) {
                                    break;
                                } else {
                                    $congID = $congregationBlackoutCount[$h]["congID"];
                                    $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                                    $scheduledEndDate = $endDate;
                                    $scheduledWeekNum = $this->calendar->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                                    $scheduledRotationNum = $this->calendar->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                                    //Create array holding the information of the congregation about to be scheduled
                                    $congregationScheduledArr = array(
                                        "congName" => $this->congregation->getCongregationName($congID),
                                        "startDate" => $scheduledStartDate,
                                        "weekNumber" => $scheduledWeekNum,
                                        "rotationNumber" => $scheduledRotationNum
                                    );
                                    //Push array of congregation info into larger array that will contain all scheduled congregations
                                    array_push($finalHostCongScheduleArr, $congregationScheduledArr);
                                    $insertedIntoCongSch = $this->congregation->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum);
                                    $insertedIntoLegacy = $this->calendar->insertLegacyData($congID, $scheduledStartDate, $scheduledEndDate,
                                        1, $scheduledRotationNum);
                                    if (true) {
                                        unset($congregationBlackoutCount[$h]);
                                        $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                        unset($dateBlackoutCount[$i]);
                                        $dateBlackoutCount = array_values($dateBlackoutCount);

                                        break;
                                    } else {
                                        //No!!
                                    }
                                }
                            }
                        } else {
                            break;
                        }
                    }*/
                }
            }

            return $finalHostCongScheduleArr;
		}

	}

?>