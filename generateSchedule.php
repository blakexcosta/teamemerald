<?php
    //session_start();
    require_once("./inc/top_layout.php");
    require_once("Schedule.class.php");

?>

    <form action="createBusSchedule.php" method="post">

       <select name="year" id="year"></select>

       <div>
         <select name='month' id='months'>
          <option  value=''>--Select Month--</option>
          <option value='1'>Janaury</option>
           <option value='2'>February</option>
           <option value='3'>March</option>
           <option value='4'>April</option>
           <option value='5'>May</option>
           <option value='6'>June</option>
           <option value='7'>July</option>
           <option value='8'>August</option>
           <option value='9'>September</option>
           <option value='10'>October</option>
           <option value='11'>November</option>
           <option value='12'>December</option>
           </select>
        </div>

<!-- onclick="document.location.href='finalBusSchedule.php';" -->


        <input id="generateButton"  type='submit' value'submitted' name='Generate Schedule'>Generate Schedule</input>


    </form>







<script>
var start = 2010;
var end = new Date().getFullYear()+2;
var options = "";
for(var year = start ; year <=end; year++){
  options += "<option>"+ year +"</option>";
}
document.getElementById("year").innerHTML = options;
</script>


<?php require_once("./inc/bottom_layout.php"); ?>
