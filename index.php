<?php include_once 'inc/top_layout.php'; ?>

<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=raihncalendarapp%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>


<?php
// Refer to the PHP quickstart on how to setup the environment:
// https://developers.google.com/calendar/quickstart/php
// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
// credentials.
// link for code was found at https://developers.google.com/calendar/create-events 

$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Google I/O 2015',
  'location' => '800 Howard St., San Francisco, CA 94103',
  'description' => 'A chance to hear more about Google\'s developer products.',
  'start' => array(
    'dateTime' => '2015-05-28T09:00:00-07:00',
    'timeZone' => 'America/Los_Angeles',
  ),
  'end' => array(
    'dateTime' => '2015-05-28T17:00:00-07:00',
    'timeZone' => 'America/Los_Angeles',
  ),
  'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=2'
  ),
  'attendees' => array(
    array('email' => 'lpage@example.com'),
    array('email' => 'sbrin@example.com'),
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);

 ?>

<?php include_once 'inc/bottom_layout.php'; ?>
