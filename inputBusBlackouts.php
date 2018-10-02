<?php
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./Schedule.class.php");
   $schedule = new Schedule(9, 2018, 10);

   $dates = $schedule->getDaysInMonth();
   

?>


<script type="text/javascript" src="./js/createBusBlackouts.js"></script>

<button type="button" id="blackout-date-sbmit" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Enter Blackout Dates</button>

<script> 

   var dates = <?php echo json_encode($dates) ?>;
   createBlackouts(dates);

</script>


   <div id="blackoutFormContainer">

   

   </div>





<?php require_once("./inc/bottom_layout.php"); ?>