$(document).ready(function() {

    var date = "";


    $('#inputBusCalendar').fullCalendar({

        dayClick: function(date, jsEvent, view) {
          date = date.format();

          $('#content').attr('date',date);

          // change the day's background color just for fun
          //$(this).css('background-color', 'red');
        },

        eventAfterAllRender: function(view){
            //if(view.name == 'month'){
                $('.fc-day').each(function(){
                    $(this).css('position','relative');
                    var add_button_AM = '<a class="add_event_label"><div id="container"><div id="content"><div id="contact-form buttonAM"><input type="button" name="contact" id ="blackout-am" value="Blackout-AM" class="AM contact demo"/></div></div></div></a>';
                    var add_button_PM = '<a class="add_event_label"><div id="container"><div id="content"><div id="contact-form buttonPM"><input type="button" name="contact" id ="hi" value="Blackout-PM" class="PM contact demo"/></div></div></div></a>';

                    var button = '<input type="button" name="contact" id ="hi" value="Blackout-PM" class="PM contact demo"/>';

                    $(this).append(button);
                    //$(this).append(add_button_PM);

                    $('#hi').onclick = function() {
                      alert("button was clicked  times");
                      console.log('hey');
                      // document.getElementById("black").style.background = "red";
                    };


                });
            //}
        }
    });

});



// function getBlackouts(){
//     //console.log('hi');
//     //console.log( $('#inputBusCalendar').fullCalendar('clientEvents'));
//
//     var evs = $('#calendar').fullCalendar('getView').getShownEvents();
//     console.log(evs);
//
//   // it would go through all events and check for evetns that are red.
//   // take their date,time,driverID(by session variable)
//   // next push these to database
//
//
//
// }
