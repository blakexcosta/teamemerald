$(document).ready(function() {



    $('#busCalendar').fullCalendar({
        eventSources : [
            {
                url: '../getFullBusSchedule.php'
            }
        ]
    });




});
