<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/18/2018
 * Time: 10:29 AM
 */

class CongregationSchedule {
    function __construct() {
        require_once(__DIR__."/Congregation.class.php");
        require_once(__DIR__."/CongregationBlackout.class.php");
        require_once(__DIR__."/DateRange.class.php");
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        require_once(__DIR__."/GoogleCalendar.php");

        $this->Congregation = new Congregation();
        $this->CongregationBlackout = new CongregationBlackout();
        $this->DateRange = new DateRange();
        $this->DB = new Database();
        $this->Functions = new Functions();

        $this->GoogleCalendar = new GoogleCalendar();
        $this->Client = $this->GoogleCalendar->getClient();
        $this->Service = new Google_Service_Calendar($this->Client);
    }

    /* function to get the rotation number of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the start date for a scheduled congregation
     * @param $weekNumber - the week number for a scheduled congregation
     * @return $result[0]["rotationNumber"] - the rotation number for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getRotationNumber($congID, $startDate, $weekNumber) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND startDate = :startDate AND 
                    weekNumber = :weekNumber";
        $params = array(":congID" => $congID, ":startDate" => $startDate, ":weekNumber" => $weekNumber);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["rotationNumber"];
        }else {
            return null;
        }
    }//end getRotationNumber

    /* function to get the start date of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $weekNumber - the week number of a rotation for a scheduled congregation
     * @param $rotationNum - the rotation number for a scheduled congregation
     * @return $result[0]["startDate"] - the start date for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getStartDate($congID, $weekNumber, $rotationNum) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND weekNumber = :weekNumber AND 
                    rotationNumber = :rotationNumber";
        $params = array(":congID" => $congID, ":weekNumber" => $weekNumber, ":rotationNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["startDate"];
        }else {
            return null;
        }
    }//end getStartDate

    /* function to get the week number of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the start date for a scheduled congregation
     * @param $rotationNum - the rotation number for a scheduled congregation
     * @return $result[0]["weekNumber"] - the week number for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getWeekNumber($congID, $startDate, $rotationNum) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND startDate = :startDate AND 
                    rotationNumber = :rotationNumber";
        $params = array(":congID" => $congID, ":startDate" => $startDate, ":rotationNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["weekNumber"];
        }else {
            return null;
        }
    }//end getWeekNumber

    /* function that will insert a newly scheduled congregation to congregation_schedule in MySQL
     * @param $congID - the ID of the congregation to be inserted
     * @param $startDate - the start date of the week the congregation is scheduled for
     * @param $weekNumber - the rotation week number that congregation is scheduled for
     * @param $rotationNumber - the rotation number of the week the congregation is scheduled for
     * @return boolean - return true or false depending on if the data was inserted
     * */
    function insertNewScheduledCong($congID, $startDate, $weekNumber, $rotationNumber) {
        $sqlQuery = "INSERT INTO congregation_schedule VALUES (:congID, :startDate, :weekNumber, :rotNum)";
        $params = array(":congID" => $congID, ":startDate" => $startDate,
            ":weekNumber" => $weekNumber, ":rotNum" => $rotationNumber);
        $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end insertNewScheduledCong

    function insertToGoogleCalendar($congID, $blackoutWeekStart) {
        $blackoutWeekEnd = date("Y-m-d",strtotime("+6 days",strtotime($blackoutWeekStart)));

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $this->Congregation->getCongregationName($congID),
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
        $event = $this->Service->events->insert($calendarId, $event);
        if($event) {
            return true;
        }
    }//end insertToGoogleCalendar

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
        //Congregation blackout count data sorted
        $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();

        //Date blackout count data sorted
        $dateBlackoutCount = $this->CongregationBlackout->dateBlackoutCount();

        $finalHostCongScheduleArr = array();
        for ($i = 0; $i < sizeof($dateBlackoutCount); $i++) {
            for ($h = 0; $h < sizeof($congregationBlackoutCount); $h++) {
                //Get an array of a single congregation's list of blackout weeks

                //The array is used to help figure out if the date we're trying to schedule for is already blacked
                // out by the congregation

                $singleCongBlackouts = $this->CongregationBlackout->getBlackoutsForOneCongregation($congregationBlackoutCount[$h]["congID"]);
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

                //If the congregation we're trying to schedule has no matching blackout date with the date we're
                // looking at, move onto the next step
                if($noConflictingDate == true) {
                    //Test to see if the congregation was scheduled within a 10 week span of the date we're trying to schedule
                    $scheduledAtLeast10Apart = true;
                    $priorDate = $this->Congregation->getLastDateServed($congregationBlackoutCount[$h]["congID"]);
                    if (is_null($priorDate) == false) {
                        $datetime1 = new DateTime($dateBlackoutCount[$i]["startDate"]);
                        $datetime2 = new DateTime($priorDate);
                        $interval = $datetime1->diff($datetime2);
                        $daysDiff = $interval->format('%a days');
                        $numOfDays = 0;
                        for($k = 0; $k < strlen($daysDiff); $k++) {
                            if(!is_numeric(substr($daysDiff, $k,1))) {
                                $numOfDays = intval(substr($daysDiff, 0, $k));
                                break;
                            }
                        }
                        $numOfWeeks = $numOfDays / 7;
                        if($numOfWeeks < 10) {
                            $scheduledAtLeast10Apart = false;
                        }
                    }
                    //If the date we're trying to schedule for is at least 10 weeks apart
                    // from the last time the congregation was scheduled, move on to the next step
                    //Else, break the for loop and move on to the next congregation
                    if ($scheduledAtLeast10Apart == true) {
                        $year = substr($dateBlackoutCount[$i]["startDate"], 0, 4);
                        $scheduledEndDate = date("Y-m-d", strtotime("+6 days", strtotime($dateBlackoutCount[$i]["startDate"])));
                        $dateIsAHoliday = $this->DateRange->containsHoliday($year, $dateBlackoutCount[$i]["startDate"], $scheduledEndDate);
                        //If the date is not a holiday, schedule the congregation for that holiday
                        //If it is a holiday, check if the congregation was scheduled the holiday the year before
                        if ($dateIsAHoliday == false) {
                            $congID = $congregationBlackoutCount[$h]["congID"];
                            $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                            $scheduledWeekNum = $this->DateRange->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                            $scheduledRotationNum = $this->DateRange->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                            //Create array holding the information of the congregation about to be scheduled
                            $congregationScheduledArr = array(
                                "congName" => $this->Congregation->getCongregationName($congID),
                                "startDate" => $scheduledStartDate,
                                "weekNumber" => $scheduledWeekNum,
                                "rotationNumber" => $scheduledRotationNum
                            );
                            //Push array of congregation info into larger array that will contain all scheduled congregations
                            array_push($finalHostCongScheduleArr, $congregationScheduledArr);
                            $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum);

                            if ($insertedIntoCongSch == true) {
                                unset($congregationBlackoutCount[$h]);
                                $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                break;
                            } else {
                                //No!!
                            }
                        } else {
                            //First, identify which holiday it is
                            $holidayName = $this->DateRange->identifyHoliday($year, $dateBlackoutCount[$i]["startDate"]);
                            $holidayArray = array("Easter", "Memorial", "Independence", "Labor", "Thanksgiving", "Christmas");
                            $priorYear = date("Y", strtotime("-1 year", strtotime($year)));
                            $holidayAYearAgo = date("Y-m-d");
                            foreach ($holidayArray as $holiday) {
                                if ($holiday == $holidayName) {
                                    if ($holidayName == "Easter") {
                                        $holidayAYearAgo = $this->DateRange->get_easter_datetime($priorYear);
                                    } elseif ($holidayName == "Memorial") {
                                        $holidayAYearAgo = $this->DateRange->getMemorialDay($priorYear);
                                    } elseif ($holidayName == "Independence") {
                                        $holidayAYearAgo = $this->DateRange->getIndependenceDay($priorYear);
                                    } elseif ($holidayName == "Labor") {
                                        $holidayAYearAgo = $this->DateRange->getLaborDay($priorYear);
                                    } elseif ($holidayName == "Thanksgiving") {
                                        $holidayAYearAgo = $this->DateRange->getThanksgiving($priorYear);
                                    } elseif ($holidayName == "Christmas") {
                                        $holidayAYearAgo = $this->DateRange->getChristmas($priorYear);
                                    } else {
                                        break;
                                    }
                                }
                            }


                            //Next, see if the congregation was scheduled for that holiday a year ago
                            $lastHolidayServed = $this->Congregation->getLastHolidayServed($congregationBlackoutCount[$h]["congID"]);

                            //If the congregation was scheduled for the holiday a year ago, move on to the next congregation
                            //Else, schedule the congregation for that holiday
                            if ($holidayAYearAgo == $lastHolidayServed) {
                                break;
                            } else {
                                $congID = $congregationBlackoutCount[$h]["congID"];
                                $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                                $scheduledWeekNum = $this->DateRange->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                                $scheduledRotationNum = $this->DateRange->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                                //Create array holding the information of the congregation about to be scheduled
                                $congregationScheduledArr = array(
                                    "congName" => $this->Congregation->getCongregationName($congID),
                                    "startDate" => $scheduledStartDate,
                                    "weekNumber" => $scheduledWeekNum,
                                    "rotationNumber" => $scheduledRotationNum
                                );
                                //Push array of congregation info into larger array that will contain all scheduled congregations
                                array_push($finalHostCongScheduleArr, $congregationScheduledArr);
                                $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum);

                                if ($insertedIntoCongSch == true) {
                                    unset($congregationBlackoutCount[$h]);
                                    $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                    break;
                                } else {
                                    //No!!
                                }
                            }
                        }
                    }
                }
            }
        }

        return $finalHostCongScheduleArr;
    }//end scheduleCongregations

}//end CongregationSchedule