<?php 
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/Calendar.class.php");
	$calendar = new Calendar();
	$initialRotation = $calendar->getMinimumRotationNumber();
	$maximumRotation = $calendar->getMaximumRotationNumber();

	if(isset($_SESSION['insertResult'])) {
		$insertResult = $_SESSION['insertResult'];

		unset($_SESSION['insertResult']);
	}

	/*if(isset($_POST['submit']) && isset($_POST['blackoutWeek'])) {
		$_SESSION['insertResult'] = $calendar->loadCalendarYear($_POST['blackoutWeek']);
		header("Location: inputblackouts.php");
	}*/
?>

<?php 
	if(isset($insertResult)) {
		var_dump($insertResult);
	}
?>

<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=raihncongregation%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

<br>

<!-- Trigger the modal with a button -->
<button type="button" id="blackout-date-sbmit" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Enter Blackout Dates</button>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
			<a href="#" id="prev-btn" class="btn btn-info btn-sm"> 
	          <span class="glyphicon glyphicon-chevron-left"></span> Prev
	        </a>
        	<?php echo "Rotation: <span id='rot-number'>".$initialRotation."</span>"; ?> (Weeks are from Sunday to Saturday)
        	<a href="#" id="nxt-btn" class="btn btn-info btn-sm">
	          Next <span class="glyphicon glyphicon-chevron-right"></span>
	        </a>
    	</h4>
      </div>
      <!-- <form action="inputblackouts.php" method="post"> -->
	      <div class="modal-body"> 
	        <div class="modal-checkboxes">
			</div> 
	      </div>
	      <div class="modal-footer">
			<input id="blackoutSubmit" type="submit" class="btn btn-default" name="submit" value="Submit"/>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
      <!-- </form> -->
    </div>

  </div>
</div>   

<?php require_once("./inc/bottom_layout.php"); ?>