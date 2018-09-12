<?php


   class Schedule{

      public $daysInMonth;

      private $primaryDriver;
      private $backupDriver;

      function __construct() {
			  require_once('BusDriverDesc.class.php');    
         $primaryDriver = new BusDriverDesc();
         $backupDriver = new BusDriverDesc();
      }
      


      //this function will return the number of days in a given month, when you specify the monthnumber and the year
      function getDaysInMonth($monthNumber, $year){
         return cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);
      }


      function createBlankSchedule($numberOfDays){

        $blankSchedule = array();

      }






   }



?>