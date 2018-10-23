<?php

class CalendarBus {

  function __construct() {
    require_once("./inc/Controller/BusDriver.class.php");



  } //end of constructor




  //pass in the final schedule so that we can push it
  function scheduleDrivers($schedule){
      $finalBusDriverScheduleArr = array();

      //parse the date out of the $schedule associative array
      foreach ($schedule as $key => $value) {

          $primaryDriverName = $value[2];
          $backupDriverName = $value[5];
          $date = substr($value[1],0,10);

          $timeOf = substr($value[1],10,2);
          $realTime = "";
          if($timeOf =='AM'){
            $realTime = "T09:00:00";
          }
          else{
              $realTime = "T18:00:00";
          }

          $primaryDriver = array(
              "title" => "P: " . $primaryDriverName,
              "start" => $date . $realTime,
              "end" => $date . $realTime
              // "color"=> '#ffffff
          );

          $backupDriver = array(
              "title" => "B: " . $backupDriverName,
              "start" => $date . $realTime,
              "end" => $date . $realTime,
              "color" => '#f20000'
          );

          array_push($finalBusDriverScheduleArr, $primaryDriver, $backupDriver);


      } //for each

      return $finalBusDriverScheduleArr;

  }//function scheduleDrivers


} //end of class



 ?>
