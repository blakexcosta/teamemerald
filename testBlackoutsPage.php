<?php
	session_start();
	require_once("./inc/top_layout.php");
	require_once("./inc/Controller/Blackouts.class.php");
	require_once("./inc/Controller/Calendar.class.php");
	require_once(__DIR__."/inc/Controller/GoogleCalendar.php");

	$blackouts = new Blackouts();
	$calendar = new Calendar();
	$gCalendar = new GoogleCalendar();

	if(isset($_SESSION['eventMsg'])) {
		$eventMsg = $_SESSION['eventMsg'];
		unset($_SESSION['eventMsg']);
	}
?>

<?php

	// Get the API client and construct the service object.
	$client = $gCalendar->getClient();
	$service = new Google_Service_Calendar($client);

	// Refer to the PHP quickstart on how to setup the environment:
	// https://developers.google.com/calendar/quickstart/php
	// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
	// credentials.
	if(isset($_POST['blackout-submit'])) {
        $insertResult = $blackouts->insertBlackout($_POST['blackoutWeek']);
        if($insertResult) {
            $scheduleResult = $blackouts->scheduleCongregations();
            if($scheduleResult) {
                $_SESSION['eventMsg'] = "<h3>Blackout Inserted!</h3>";
                header("Location: testBlackoutsPage.php");
            }
        }
    }
    if(isset($eventMsg)) {
        echo $eventMsg;
	}
	/*printf('Event created: %s\n', $event->htmlLink);*/

?>
	<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=raihncongregation%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0" width="1000" height="600" frameborder="0" scrolling="no"></iframe>
	<br>
	<form action="testBlackoutsPage.php" method="post">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="2018-09-09" name="blackoutWeek[]" id="blackoutWeek">
			<label class="form-check-label" for="defaultCheck1">
				2018-09-09
			</label>
		</div>
		<button id="blackout-submit" name="blackout-submit"  type="submit" class="btn btn-primary">Submit</button>
	</form>

<?php require_once("./inc/bottom_layout.php"); ?>
