<?php


class Schedule{

  private $month;
  private $year;
  private $numberOfDrivers;

  function __construct($_month, $_year, $_numberOfDrivers) {
    $this->month = $_month;
    $this->year = $_year;
    $this->numberOfDrivers = $_numberOfDrivers;
  }


  // Array ( [0] => 2018-09-01 [1] => 2018-09-02 [2] => 2018-09-03 [3] => 2018-09-04 [4] =>
  //this function will return the number of days in a given month, when you specify the monthnumber and the year
  function getDaysInMonth(){
    $monthOutline=array();
 		for($d=1; $d<=31; $d++){
 		    $time=mktime(12, 0, 0, $this->month, $d, $this->year);
            if (date('m', $time)==$this->month){
                $monthOutline[]=date('Y-m-d', $time) . 'AM';
                $monthOutline[]=date('Y-m-d', $time) . 'PM';
            }
        }

    return $monthOutline;
  }

  function getDriverName($driverID, $driverNames){
    for ($i=0; $i<count($driverNames); $i++){

      if ($driverID == $driverNames[$i]['driverID']){
        return $driverNames[$i]['name'];
      }
      else{
        continue;
      }
    }
  } //function getDriverName

  //this function will create a blank array of 10 drivers
  function resetDrivingLimits(){
    $array = array_fill(0, $this->numberOfDrivers, 0);
    return $array;
  }

  // j = Day of the month without leading zeros	 Ex: 1 to 31 for October
  function getWeekNumber($date){
      $date = substr($date,0,10);
      return ceil( date( 'j', strtotime( $date ) ) / 7 );
  }

  function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
  }

  function createDraftSchedule($mostBlackouts, $driverNames,$blackouts, $primarySchedule){


    $currentDriverIndex = 0;

    //just sets the first driver we will attempt to schedule
    $currentDriverToSchedule = $mostBlackouts[$currentDriverIndex]['driverID'];

    //Creates a array for that specific month. pass in : monthNumber & Year
    //Array ( [0] => 2018-09-01AM [1] => 2018-09-01PM [2] => 2018-09-02AM [3] => 2018-09-02PM [4] => 2018-09-03AM [5] => 2018-09-03PM [6]
    $monthOutline = $this->getDaysInMonth();

    //print_r($monthOutline);

    $storedWeekNumber = 1;
    $draftSchedule = array();

    $tempCounterMonthOutline = 1;
    //printf("MonthOutline: " . sizeof($monthOutline));
    for ($i=0;$i<sizeof($monthOutline);$i++){

        //if even i, update counter, unless first time through
        if ($i != 0){
            if ($i%2 == 0){
                $tempCounterMonthOutline++;
            }
        }

        //week number within the current month
        $week_num = $this->getWeekNumber($monthOutline[$i]); //111112222233344455

        //echo "Month outline: " . $monthOutline[$i];

        // print_r($monthOutline[$i-1]);

        //if the week number has changed, need to reset the # of days driven for each drivers
        if ($week_num != $storedWeekNumber){
            $this->resetDrivingLimits();
        }

        //checks if no one is available on a specific date i.e. if it checks the data against 10 drivers push 'no drivers available' to $draftSchedule
        $unavailableDrivers = 0;

        top:

        $currentDriverToSchedule = $mostBlackouts[$currentDriverIndex]['driverID'];

        //assume they can work this shift
        $scheduleBoolean = true;


        //start going through this drivers blackout dates
        for($j=0;$j<sizeof($blackouts[$currentDriverToSchedule]);$j++){
          // $currentBlackoutDay = (int)(substr($blackouts[$currentDriverToSchedule][$j]->{'date'}, strrpos($blackouts[$currentDriverToSchedule][$j]->{'date'}, '-') + 1));


            $currentBlackoutDay = ($blackouts[$currentDriverToSchedule][$j]->{'date'});
            $currentBlackoutDay = $currentBlackoutDay . $blackouts[$currentDriverToSchedule][$j]->{'timeof'};

            //printf("Temp: " . $tempCounterMonthOutline);

            $currentSlot = ($monthOutline[$i]);

            //
            //
            // echo (' current driver to shcedule: ') . $currentDriverToSchedule;
            // echo (' current driver index: ') . $currentDriverIndex;
            // echo ("    blackout day we are checking:   ". $currentBlackoutDay);
            // echo ("     slot to schedule in :  " . $currentSlot);
            // echo "<br>";

            if($tempCounterMonthOutline == (sizeof($monthOutline)/2)+1){
                $tempCounterMonthOutline = 1;
            }


            if($currentBlackoutDay == $currentSlot) {
                 //echo "don't schedule";
                // echo "<br>";
                $unavailableDrivers++;
                  //-1 BECAUSE To signifiy no driver avail
                if($unavailableDrivers == $this->numberOfDrivers){
                    $draftSchedule[$monthOutline[$i]] = ["-1", $monthOutline[$i], "NO DRIVER AVAILABLE"];
                    $unavailableDrivers = 0;
                    $scheduleBoolean = false;
                    break;
                }
                $scheduleBoolean = false;
                $currentDriverIndex++;
                if ($currentDriverIndex == $this->numberOfDrivers){
                    $currentDriverIndex = 0;
                }

                goto top; // stops here

            }

        }//end of inner for loop-1

        if ($scheduleBoolean){

            //if primarySchedule isn't null, we will start scheduling blackouts
            // echo " current driver to schedule" . $currentDriverToSchedule;
            // echo " schedule them";
            // echo "<br>";

            if($primarySchedule != ""){
                //when primary = backup
                if($primarySchedule[$monthOutline[$i]][0] == $currentDriverToSchedule){
                    $currentDriverIndex++;
                    if ($currentDriverIndex == $this->numberOfDrivers){
                        $currentDriverIndex = 0;
                    }
                    goto top; // stops here
                }
                // When the primary driver isn't the same as the backup driver, valid to schedule
                else{
                    // echo "currentDriverISTHISKID: " .$currentDriverToSchedule;
                    // echo ("blackout day we are checkin:   ". $currentBlackoutDay);
                    $draftSchedule[$monthOutline[$i]] = [($currentDriverToSchedule), $monthOutline[$i], $this->getDriverName($currentDriverToSchedule, $driverNames)];
                    $currentDriverIndex++;
                    if ($currentDriverIndex == $this->numberOfDrivers){
                        $currentDriverIndex = 0;
                    }

                    $unavailableDrivers = 0;

                    //need to increment $drivingLimitsCount for the driver that just got scheduled
                    //$drivingLimitsCount[$currentDriverToSchedule]+=1;

                    //print_r($drivingLimitsCount[$currentDriverToSchedule]);

                } //end of else
            } //end of if

            else{ //WHEN primarySchedule is not defined
                $draftSchedule[$monthOutline[$i]] = [($currentDriverToSchedule), $monthOutline[$i], $this->getDriverName($currentDriverToSchedule, $driverNames)];
                $currentDriverIndex++;
                if ($currentDriverIndex == $this->numberOfDrivers){
                    $currentDriverIndex = 0;
                }
                $unavailableDrivers = 0;
                //need to increment $drivingLimitsCount for the driver that just got scheduled

                //$drivingLimitsCount[$currentDriverToSchedule]+=1;
                //print_r($drivingLimitsCount[$currentDriverToSchedule]);
            } //end of else


            if ($currentDriverIndex == $this->numberOfDrivers){
                $currentDriverIndex = 0;
            }


        }//scheduleBoolean

    } //end of for outer loop

    return $draftSchedule;

  } //end of function create draft schedule


} //end of class schedule

?>
