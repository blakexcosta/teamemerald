<?php 
	session_start();
    require_once('inc/top_layout.php');
    require_once('inc/functions.php');
?>


<?php 
	//Gets the data for all the bus drivers
	getBusDriverData(); 
?>

<?php require_once('inc/bottom_layout.php'); ?>