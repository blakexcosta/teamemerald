<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 7:46 PM
 */

class RequiredObjects {
    public function __construct() {
        require_once(__DIR__."/Congregation.class.php");
        require_once(__DIR__."/CongregationBlackout.class.php");
        require_once(__DIR__."/CongregationCoordinator.class.php");
        require_once(__DIR__."/CongregationSchedule.class.php");
        require_once(__DIR__."/DateRange.class.php");
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        require_once(__DIR__."/GoogleCalendar.php");
        require_once(__DIR__."/LegacyHostBlackout.class.php");
        require_once(__DIR__."/RotationDate.class.php");
        require_once(__DIR__."/Users.class.php");

        $this->Congregation  = new Congregation();
        $this->CongregationBlackout = new CongregationBlackout();
        $this->CongregationCoordinator = new CongregationCoordinator();
        $this->CongregationSchedule = new CongregationSchedule();
        $this->DB = new Database();
        $this->DateRange = new DateRange();
        $this->Functions = new Functions();
        $this->GoogleCalendar = new GoogleCalendar();
        $this->LegacyHostBlackout = new LegacyHostBlackout();
        $this->RotationDate = new RotationDate();
        $this->Users = new Users();

        $this->Client = $this->GoogleCalendar->getClient();
        $this->Service = new Google_Service_Calendar($this->Client);
    }

    /* function that will return any required object needed
     * @param $objectName - the name of the object that is desired
     * @return $listOfObjects[$objectName] - the actual object retrieved from an array
     * */
    public function getObject($objectName) {
        $listOfObjects = array("Calendar" => $this->Calendar, "Congregation" => $this->Congregation,
                                "CongregationBlackout" => $this->CongregationBlackout, "CongregationCoordinator" => $this->CongregationCoordinator,
                                "CongregationSchedule" => $this->CongregationSchedule, "DB" => $this->DB, "DateRange" => $this->DateRange,
                                "Functions" => $this->Functions, "GoogleCalendar" => $this->GoogleCalendar, "GoogleClient" => $this->Client,
                                "GoogleService" => $this->Service, "LegacyHostBlackout" => $this->LegacyHostBlackout,
                                "RotationDate" => $this->RotationDate, "Users" => $this->Users);
        return $listOfObjects[$objectName];
    }//end getObject

}//end RequiredObject