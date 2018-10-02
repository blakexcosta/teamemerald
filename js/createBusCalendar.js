$(document).ready(function() {



    $('#calendar').fullCalendar({
        eventSources : [
            {
                url: '../getFullBusSchedule.php'
            }
        ]
    });




});
