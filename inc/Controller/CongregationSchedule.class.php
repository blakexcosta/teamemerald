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

        $this->Congregation = new Congregation();
        $this->CongregationBlackout = new CongregationBlackout();
        $this->DateRange = new DateRange();
        $this->DB = new Database();
        $this->Functions = new Functions();

        $this->FinalHostCongSchedule = null;
        $this->FlaggedHostCongregations = null;
    }

    /* function to delete a data from the congregation_schedule in the SQL table
     * @return boolean - return true or false if the data was successfully deleted
     * */
    function deleteCongregationSchedule() {
        $sqlQuery = "DELETE FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "delete");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end deleteCongregationSchedule

    /* function to get the congregations that weren't scheduled
     * @return $flaggedCongregations - all the congregations not scheduled
     * */
    function getFlaggedCongregations() {
        $finalHostCongScheduleArr = $this->getFullScheduleInArrayForm();

        //All the congregations from MySQL
        $allCongList = $this->Congregation->getCongregations();

        //Each rotation number in the final congregation schedule
        $rotationNums = $this->getRotationNumsInFinalSchedule();

        //All the start dates for each rotation number in the final congregation schedule
        $allStartDates = array();
        for($i = 0; $i < sizeof($rotationNums); $i++) {
            $startDates = $this->DateRange->getStartDateBasedRotation($rotationNums[$i]['rotationNumber']);
            for($h = 0; $h < sizeof($startDates); $h++) {
                array_push($allStartDates, $startDates[$h]['startDate']);
            }
        }

        $flaggedCongNames = array();
        $flaggedCongDates = array();
        $finalFlaggedCongList = array();

        //Get the names and start dates for each of the scheduled congregations
        $finalHostCongSchNames = array();
        $finalHostCongSchStartDates = array();

        //Add the congregation start dates and name into their own separate arrays
        //The names and start dates come from the $finalHostCongScheduleArr array
        //This is done to help see which start dates and congregation names are missing
        for($e = 0; $e < sizeof($finalHostCongScheduleArr); $e++) {
            array_push($finalHostCongSchNames, $finalHostCongScheduleArr[$e]['title']);
            array_push($finalHostCongSchStartDates, $finalHostCongScheduleArr[$e]['start']);
        }

        //Check to see which congregation names are missing from the $finalHostCongSchNames array
        //Add the missing congregation names to an array
        for($e = 0; $e < sizeof($allCongList); $e++) {
            if(!in_array($allCongList[$e]['congName'], $finalHostCongSchNames)) {
                array_push($flaggedCongNames, $allCongList[$e]['congName']);
            }
        }

        //Check to see which congregation start dates are missing from the $flaggedCongDates array
        //Add the missing congregation start dates to an array
        for($e = 0; $e < sizeof($allStartDates); $e++) {
            if(!in_array($allStartDates[$e], $finalHostCongSchStartDates)) {
                array_push($flaggedCongDates, $allStartDates[$e]);
            }
        }

        //Combine the missing start dates and congregation names into one final array
        for($e = 0; $e < sizeof($flaggedCongNames); $e++) {
            $scheduledEndDate = date("Y-m-d",strtotime("+7 days",strtotime($flaggedCongDates[$e])));
            $tempFlagArray = array(
                                "title" => $flaggedCongNames[$e],
                                "start" => $flaggedCongDates[$e],
                                "end" => $scheduledEndDate,
                                "color" => "#CC2936"
                            );
            array_push($finalFlaggedCongList, $tempFlagArray);
        }

        return $finalFlaggedCongList;
    }//end getFlaggedCongregations

    /* function to get all of the scheduled congregations
     * @return $result - the whole congregation schedule
     * @return null - return nothing if no data was returned
     * */
    function getFullSchedule() {
        $sqlQuery = "SELECT * FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getFullSchedule

    function getFullScheduleInArrayForm() {
        $fullSchedule = $this->getFullSchedule();

        $finalHostCongScheduleArr = array();

        for($i = 0; $i < sizeof($fullSchedule); $i++) {
            $scheduledEndDate = date("Y-m-d",strtotime("+7 days",strtotime($fullSchedule[$i]['startDate'])));

            //Create array holding the information of the congregation about to be scheduled
            $congregationScheduledArr = array(
                "title" => $this->Congregation->getCongregationName($fullSchedule[$i]['congID']),
                "start" => $fullSchedule[$i]['startDate'],
                "end" => $scheduledEndDate
            );
            array_push($finalHostCongScheduleArr, $congregationScheduledArr);
        }
        return $finalHostCongScheduleArr;
    }//end getFullScheduleInArrayForm

    /* function to get each distinct rotation number
     * @return $result - each distinct rotation number
     * @return null - return nothing if no data was returned
     * */
    function getRotationNumsInFinalSchedule() {
        $sqlQuery = "SELECT DISTINCT rotationNumber FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getRotationNumsInFinalSchedule

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
    function insertNewScheduledCong($congID, $startDate, $weekNumber, $rotationNumber, $holiday) {
        $sqlQuery = "INSERT INTO congregation_schedule VALUES (:congID, :startDate, :weekNumber, :rotNum, :holiday)";
        $params = array(":congID" => $congID, ":startDate" => $startDate,
            ":weekNumber" => $weekNumber, ":rotNum" => $rotationNumber, ":holiday" => $holiday);
        $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end insertNewScheduledCong

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

    /* function to actually schedule congregations to the database
     * @return bool - return true or false if the congregations were successfully inserted
     * */
    function scheduleCongregations() {
        //Congregation blackout count data sorted
        $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();


        $startDateList = $this->DateRange->getStartDateBasedRotation(53);

        //Date blackout count data sorted
        $dateBlackoutCount = $this->CongregationBlackout->dateBlackoutCount();

        //Grab start dates from dateBlackoutCount and store in separate array
        //Used for figuring if start dates weren't blacked out
        $justBlackoutStartDates = array();
        for($i = 0; $i < sizeof($dateBlackoutCount); $i++) {
            array_push($justBlackoutStartDates, $dateBlackoutCount[$i]['startDate']);
        }

        //Figure out which start dates weren't blacked out
        //If a start date wasn't blacked out, add it to the dateBlackoutCount array with a count of 0
        for($e = 0; $e < sizeof($startDateList); $e++) {
            if(!in_array($startDateList[$e]['startDate'], $justBlackoutStartDates)) {
                $tempArray = array(
                    'startDate' => $startDateList[$e]['startDate'],
                    'count' => 0
                );
                array_push($dateBlackoutCount, $tempArray);
            }
        }

        //Boolean variable used to check if the schedule was created
        $scheduleCreated = true;
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

                            $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum, 0);

                            if ($insertedIntoCongSch == true) {

                                unset($congregationBlackoutCount[$h]);
                                $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                if($h == 12) {
                                    $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();
                                }

                                break;
                            } else {
                                $scheduleCreated = false;
                                return $scheduleCreated;
                            }
                        } else {
                            //First, identify which holiday it is
                            $holidayName = $this->DateRange->identifyHoliday($year, $dateBlackoutCount[$i]["startDate"]);
                            $holidayArray = array("SundayBeforeEaster", "Easter", "Memorial", "Independence", "Labor", "Thanksgiving", "Christmas", "NewYears");
                            $priorYear = date("Y", strtotime("-1 year", strtotime($year)));
                            $holidayAYearAgo = date("Y-m-d");
                            foreach ($holidayArray as $holiday) {
                                if ($holiday == $holidayName) {
                                    if($holidayName == "SundayBeforeEaster") {
                                        $holidayAYearAgo = $this->DateRange->getSundayBeforeEaster($priorYear);
                                    } elseif ($holidayName == "Easter") {
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
                                    } elseif ($holidayName == "NewYears"){
                                        $holidayAYearAgo = $this->DateRange->getNewYears($priorYear);
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

                                $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum, 1);

                                if ($insertedIntoCongSch == true) {
                                    unset($congregationBlackoutCount[$h]);
                                    $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                    if($h == 12) {
                                        $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();
                                    }

                                    break;
                                } else {
                                    $scheduleCreated = false;
                                    return $scheduleCreated;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $scheduleCreated;
    }//end scheduleCongregations

}//end CongregationSchedule
