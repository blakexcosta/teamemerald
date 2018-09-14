<?php 
	session_start();
    require_once("./inc/top_layout.php"); 
    require_once("./inc/Controller/Congregation.class.php");
?>

<?php 
	$congregation = new Congregation();

    //Gets the data for all host congregations in MySQL
    $congregation->getHostCongregationRoster(); 
?>

<?php require_once("./inc/bottom_layout.php"); ?>