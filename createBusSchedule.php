<?php
    require("./CreateBusSchedule.class.php");

 if (isset($_POST['month'])){

    $month = $_POST['month'];
    $year = $_POST['year'];

    $CreateBusSchedule = new CreateBusSchedule($month, $year);
}




 ?>
