<?php 
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/Blackouts.class.php");
	$blackouts = new Blackouts();
?>

<?php 	
	$data = $blackouts->getCongBlackouts();
	for ($i=0; $i < sizeof($data); $i++) { 
		var_dump($data[$i]);
	}
?>

<?php require_once("./inc/bottom_layout.php"); ?>