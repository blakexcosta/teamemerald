<?php
require("./CreateBusSchedule.class.php");

 if (isset($_POST['submit'])){

    $month = $_POST['month'];
    $year = $_POST['year'];

    $CreateBusSchedule = new CreateBusSchedule($month, $year);

    header('Location: finalBusSchedule.php');
}




 ?>
