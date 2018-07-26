<?php require_once('inc/top_layout.php'); ?>

<iframe src="https://calendar.google.com/calendar/embed?src=teamemeraldtest2%40gmail.com&ctz=America%2FNew_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>


<?php 

// Refer to the PHP quickstart on how to setup the environment:
// https://developers.google.com/calendar/quickstart/php
// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
// credentials.

$calendarId = "raihncongregation@gmail.com";

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





<p>Sed ut perspiciatis unde omnis iste 
	natus error sit voluptatem accusantium doloremque laudantium, 
	totam rem aperiam, eaque ipsa quae ab illo inventore veritatis 
	et quasi architecto beatae vitae dicta sunt explicabo. 
	Nemo enim ipsam voluptatem quia voluptas sit aspernatur
	 aut odit aut fugit, sed quia consequuntur 
	 magni dolores eos qui ratione voluptatem 
	 sequi nesciunt. Neque porro quisquam est, 
	 qui dolorem ipsum quia dolor sit amet, 
	 consectetur, adipisci velit, 
	 sed quia non numquam eius modi tempora incidunt ut labore 
	 et dolore magnam aliquam quaerat voluptatem. 
	 Ut enim ad minima veniam, quis nostrum 
	 exercitationem ullam corporis suscipit 
	 laboriosam, nisi ut aliquid ex ea commodi consequatur? 
	 Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam 
	 nihil molestiae consequatur, vel 
	 illum qui dolorem eum fugiat quo voluptas nulla pariatur</p>

<p>
	Lorem ipsum dolor sit amet, nostrum reformidans vim at. Ne populo definitionem mea, ad vim aliquip facilis vulputate. Te eos voluptua tincidunt deterruisset, latine iracundia at usu, ad mel maluisset similique adversarium. Sit ut agam sanctus ancillae, simul lucilius neglegentur at has, graeco omnesque dissentiunt cum an. Ei est alii quaeque accusamus. Minim iisque delicata pro no, eum ex odio primis.

Simul omnesque tacimates at vim. Ex dicta dignissim contentiones pri. Nec id suas convenire, qui te eligendi offendit adversarium, copiosae perpetua eu est. Cetero appellantur ne ius, cum ea nulla iudicabit, ne vel essent diceret expetendis. Vix alii comprehensam an. Nusquam tractatos disputationi in vim. Putant offendit euripidis ei pro, vocent dolorum ea has, alia postulant ne vis.

Cu aeque scaevola usu. Reque feugiat torquatos sea ei. Mea zril maiestatis ne, altera epicurei invidunt mea eu. Mei id sint simul, epicuri iudicabit ullamcorper te quo. Brute regione neglegentur et eam.

Mei errem labitur expetenda te. Qui ex homero salutatus, qui eu malis labores, vero affert habemus his ei. Quas maluisset ea qui, ius velit quaestio cu. Et illum verterem accusamus quo, ne ferri simul mandamus nam. Eros altera omnium vis id, probo luptatum ut mel.

Ut pri ocurreret mnesarchum, integre gubergren nec et. Diceret nusquam quo ex. Eos in populo complectitur, sale vituperatoribus et nec. Vis clita vocibus ne, ad pro regione veritus accommodare.
</p>

<p>
	Lorem ipsum dolor sit amet, nostrum reformidans vim at. Ne populo definitionem mea, ad vim aliquip facilis vulputate. Te eos voluptua tincidunt deterruisset, latine iracundia at usu, ad mel maluisset similique adversarium. Sit ut agam sanctus ancillae, simul lucilius neglegentur at has, graeco omnesque dissentiunt cum an. Ei est alii quaeque accusamus. Minim iisque delicata pro no, eum ex odio primis.

Simul omnesque tacimates at vim. Ex dicta dignissim contentiones pri. Nec id suas convenire, qui te eligendi offendit adversarium, copiosae perpetua eu est. Cetero appellantur ne ius, cum ea nulla iudicabit, ne vel essent diceret expetendis. Vix alii comprehensam an. Nusquam tractatos disputationi in vim. Putant offendit euripidis ei pro, vocent dolorum ea has, alia postulant ne vis.

Cu aeque scaevola usu. Reque feugiat torquatos sea ei. Mea zril maiestatis ne, altera epicurei invidunt mea eu. Mei id sint simul, epicuri iudicabit ullamcorper te quo. Brute regione neglegentur et eam.

Mei errem labitur expetenda te. Qui ex homero salutatus, qui eu malis labores, vero affert habemus his ei. Quas maluisset ea qui, ius velit quaestio cu. Et illum verterem accusamus quo, ne ferri simul mandamus nam. Eros altera omnium vis id, probo luptatum ut mel.

Ut pri ocurreret mnesarchum, integre gubergren nec et. Diceret nusquam quo ex. Eos in populo complectitur, sale vituperatoribus et nec. Vis clita vocibus ne, ad pro regione veritus accommodare.
</p>

<p>
	Lorem ipsum dolor sit amet, nostrum reformidans vim at. Ne populo definitionem mea, ad vim aliquip facilis vulputate. Te eos voluptua tincidunt deterruisset, latine iracundia at usu, ad mel maluisset similique adversarium. Sit ut agam sanctus ancillae, simul lucilius neglegentur at has, graeco omnesque dissentiunt cum an. Ei est alii quaeque accusamus. Minim iisque delicata pro no, eum ex odio primis.

Simul omnesque tacimates at vim. Ex dicta dignissim contentiones pri. Nec id suas convenire, qui te eligendi offendit adversarium, copiosae perpetua eu est. Cetero appellantur ne ius, cum ea nulla iudicabit, ne vel essent diceret expetendis. Vix alii comprehensam an. Nusquam tractatos disputationi in vim. Putant offendit euripidis ei pro, vocent dolorum ea has, alia postulant ne vis.

Cu aeque scaevola usu. Reque feugiat torquatos sea ei. Mea zril maiestatis ne, altera epicurei invidunt mea eu. Mei id sint simul, epicuri iudicabit ullamcorper te quo. Brute regione neglegentur et eam.

Mei errem labitur expetenda te. Qui ex homero salutatus, qui eu malis labores, vero affert habemus his ei. Quas maluisset ea qui, ius velit quaestio cu. Et illum verterem accusamus quo, ne ferri simul mandamus nam. Eros altera omnium vis id, probo luptatum ut mel.

Ut pri ocurreret mnesarchum, integre gubergren nec et. Diceret nusquam quo ex. Eos in populo complectitur, sale vituperatoribus et nec. Vis clita vocibus ne, ad pro regione veritus accommodare.
</p>

<?php require_once('inc/bottom_layout.php'); ?>