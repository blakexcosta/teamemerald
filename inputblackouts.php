<?php 
	session_start();
	include_once 'inc/top_layout.php';
	include_once 'inc/functions.php';

	if(isset($_SESSION['choiceArray'])) {
		$choiceArray = $_SESSION['choiceArray'];

		unset($_SESSION['choiceArray']);
	}

	if(isset($_POST['submit']) && isset($_POST['blackoutWeek'])) {
		$_SESSION['choiceArray'] = loadCalendarYear($_POST['blackoutWeek']);
		var_dump($_SESSION['choiceArray']);
		header("Location: inputblackouts.php");
	}
?>

<?php 
	if(isset($choiceArray)) {
		for($i = 0; $i < sizeof($choiceArray); $i++) {
			echo "<p>You're unavailable: ".$choiceArray[$i]."</p>";
		}
	}
?>

<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=raihncalendarapp%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

<br>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Enter Blackout Dates</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header (Rotations are from Sunday to Saturday)</h4>
      </div>
      <form action="inputblackouts.php" method="post">
	      <div class="modal-body"> 
	        <div class="modal-checkboxes">
				<label class="checkbox-inline"><input type="checkbox" name="blackoutWeek[]" id="blackoutWeek" value="2018-10-06">Week 1</label>
				<br>
				<label class="checkbox-inline"><input type="checkbox" name="blackoutWeek[]" id="blackoutWeek" value="2018-08-14">Week 2</label>
				<br>
				<label class="checkbox-inline"><input type="checkbox" name="blackoutWeek[]" id="blackoutWeek" value="2017-12-31">Week 3</label>
				<br>
				<label class="checkbox-inline"><input type="checkbox" name="blackoutWeek[]" id="blackoutWeek" value="2019-02-25">Week 4</label>
			</div> 
	      </div>
	      <div class="modal-footer">
			<input id="blackoutSubmit" type="submit" class="btn btn-default" name="submit" value="Submit"/>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
      </form>
    </div>

  </div>
</div>   

<?php include_once 'inc/bottom_layout.php'; ?>