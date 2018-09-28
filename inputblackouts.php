<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Controller/CongregationBlackout.class.php");
    require_once(__DIR__."/inc/Controller/DateRange.class.php");

    $DateRange = new DateRange();
    $CongregationBlackout = new CongregationBlackout();

	$initialRotation = $DateRange->getMinimumRotationNumber();

	if(isset($_SESSION['insertResult'])) {
		$insertResult = $_SESSION['insertResult'];

		unset($_SESSION['insertResult']);
	}

	if(isset($_POST['blackoutSubmit']) && isset($_POST['blackoutWeek'])) {
		$insertBlackout = $CongregationBlackout->insertBlackout($_POST['blackoutWeek'], $_SESSION['email']);
		if($insertBlackout) {
		    $_SESSION['insertBlackout'] = $insertBlackout;
            $_SESSION['insertResult'] = "<div class='alert alert-success'>
											<strong>Success!</strong> Blackouts inserted!
										</div>";
            header("Location: inputblackouts.php");
        }else {
            $_SESSION['insertResult'] = "<div class='alert alert-danger'>
											<strong>Success!</strong> Blackouts inserted!
										</div>";
            header("Location: inputblackouts.php");
        }
	}
?>

<?php
	if(isset($insertResult)) {
		echo $insertResult;
	}
?>

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
       <form action="inputblackouts.php" method="post">
	      <div class="modal-body">
	        <div class="modal-checkboxes">
			</div>
	      </div>
	      <div class="modal-footer">
			<input id="blackoutSubmit" type="submit" class="btn btn-default" name="blackoutSubmit" value="Submit"/>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
       </form>
    </div>

  </div>
</div>

<?php require_once("./inc/bottom_layout.php"); ?>
