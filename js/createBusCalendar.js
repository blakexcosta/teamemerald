$(document).ready(function() {
	//GLOBAL VARIABLES

    //Global variable storing the final schedule from ajax call in show-calendar
    // var fullSchedule;
    //


    $('#calendar').fullCalendar({
        eventSources : [
            {
                url: '../getFullBusSchedule.php'
            }
        ]
    });


    // $('#calendar').fullCalendar({
    //
    //   eventSources: [
    //
    //     // your event source
    //     {
    //       url: '/myfeed.php', // use the `url` property
    //       color: 'yellow',    // an option!
    //       textColor: 'black'  // an option!
    //     }
    //
    //     // any other sources...
    //
    //   ]
    //
    // });





});
