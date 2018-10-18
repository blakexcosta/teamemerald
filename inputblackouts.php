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

	/*if(isset($_POST['blackoutSubmit']) && isset($_POST['blackoutWeek'])) {
		$insertBlackout = $CongregationBlackout->insertBlackout($_POST['blackoutWeek'], $_SESSION['email']);
		if($insertBlackout) {
            $_SESSION['insertResult'] = "<div class='alert alert-success'>
											<strong>Success!</strong> Blackouts inserted!
										</div>";
            header("Location: inputblackouts.php");
        }else {
            $_SESSION['insertResult'] = "<div class='alert alert-danger'>
											<strong>Error!</strong> Blackouts not inserted!
										</div>";
            header("Location: inputblackouts.php");
        }
	}*/
?>

<?php
	if(isset($insertResult)) {
		echo $insertResult;
	}
?>
<!-- Blackout content-->
<div id="blackout-content">
    <div class="blackout-header">
        <h4 class="blackout-title">
            <a href="#" id="prev-btn" class="btn btn-info btn-sm">
                <span class="glyphicon glyphicon-chevron-left"></span> Prev
            </a>
            <?php echo "Rotation: <span id='rot-number'>".$initialRotation."</span> (Weeks are from Sunday to Saturday)"; ?>
            <a href="#" id="nxt-btn" class="btn btn-info btn-sm">
                Next <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </h4>
        <?php echo "<p>Entering blackouts for user: <span id='curr-user'>".$_SESSION['email']."</span></p>"; ?>
    </div>
    <hr align="left"/>
    <!--<form action="inputblackouts.php" method="post">-->
        <div class="blackout-body">
            <div class="blackout-checkboxes">
            </div>
        </div>
        <div class="blackout-footer">
            <input id="blackoutSubmit" type="submit" class="btn btn-primary" name="blackoutSubmit" value="Submit Blackouts"/>
        </div>
    <!--</form>-->
</div>
<div id="calendar">

</div>

<?php require_once("./inc/bottom_layout.php"); ?>
