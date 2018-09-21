<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 6:55 PM
 */

class CongregationBlackout {
    function __construct() {
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
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
     * @return $result - the blackout week data fetched from MySQL
     * @return null - return nothing if no data was fetched
     * */
    function getBlackoutsForOneCongregation($congID) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE congID = :congID";
        $params = array(':congID' => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getBlackoutsForOneCongregation

    //First, grab congregations and their blackout dates
    /* function that fetches all data from congregation_blackouts
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackouts($orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout ORDER BY ".$orderByVar;
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
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
        $result = $this->getCongBlackouts("congID");
        $countedData = $this->countValues($result,$result[0]["congID"],"congID","count");
        $sortedBlackouts = $this->Functions->sortArray($countedData,"congID","count");
        return $sortedBlackouts;
    }//end getBlackouts

    //Forth, check to see if more than 5 host congregations have a week blacked out
    //Schedule that week first
    /* function that checks to see if a blackout week has more than 5 congregations blacking it out
     * @return $datesMoreThanFive - array holding start dates for blackout weeks with more than 5 congregations blacking it out
     * */
    function dateBlackoutCount() {
        $result = $this->getCongBlackouts("startDate");
        $countedBlackedOutDates = $this->countValues($result,$result[0]["startDate"],"startDate", "count");
        $sortedDates = $this->Functions->sortArray($countedBlackedOutDates,"startDate","count");
        return $sortedDates;
    }//end moreThan5Congregations

    /* Hard coded function
     * */
    /*function insertBlackout($blackoutWeekArr) {
        $weekNumber = $this->calendar->getWeekNumber($blackoutWeekArr[0]);
        $congID = 1;
        $sqlQuery = "INSERT INTO congregation_blackout VALUES (:congID, :weekNumber, :startDate)";
        $params = array(":congID" => $congID, ":weekNumber" => $weekNumber, ":startDate" => $blackoutWeekArr[0]);
        $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }*/

    function insertBlackout($blackoutWeek, $email) {
        //Get the congregation ID of the current user logged in
        $sqlQuery = "SELECT congID FROM congregation_coordinator WHERE coordinatorEmail = :email";
        $params = array(":email" => $email);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");

        if($result){
            for($i = 0; $i < sizeof($blackoutWeek); $i++) {

                //Get the rotation number of the date that was submitted
                $sqlQuery2 = "SELECT weekNumber FROM date_range WHERE startDate = :startDate";
                $params2 = array(":startDate" => $blackoutWeek[$i]);
                $result2 = $this->DB->executeQuery($sqlQuery2, $params2, "select");

                if($result2) {
                    //Insert the blackout date to MySQL
                    $sqlQuery3 = "INSERT INTO congregation_blackout VALUES (:congID, :weekNumber, :srtDate)";
                    $params3 = array(":congID" => $result[0]["congID"], ":weekNumber" => $result2[0]["weekNumber"],
                                    ":srtDate" => $blackoutWeek[$i]);
                    $result3 = $this->DB->executeQuery($sqlQuery3, $params3, "insert");
                    if($result3 < 0) {
                        return false;
                    }
                }else {
                    return false;
                }
            }
        }else {
            return false;
        }
        /*$blackoutWeekNumArray = array();
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
        }*/
        return true;
    }//end insertBlackout

    /* function to set the startDate column in MySQL
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the new start date value
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setStartDate($congID, $startDate) {
        $sqlQuery = "UPDATE congregation_blackout SET startDate = :startDate WHERE congID = :congID";
        $params = array(":weekNumber" => $startDate, ":congID" => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setStartDate

    /* function to set the weekNumber column in MySQL
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $weekNumber - the new week number value
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setWeekNumber($congID, $weekNumber) {
        $sqlQuery = "UPDATE congregation_blackout SET weekNumber = :weekNumber WHERE congID = :congID";
        $params = array(":weekNumber" => $weekNumber, ":congID" => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setWeekNumber

}//end CongregationBlackout