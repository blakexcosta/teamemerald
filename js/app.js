$(document).ready(function() {
	//GLOBAL VARIABLES

    //Global variable storing data retrieved from ajax call in blackout-date-sbmit
    var blackoutWeekDates;

    //Global variable storing the final schedule from ajax call in show-calendar
    var fullSchedule;

    $('#calendar').fullCalendar({

    });

    $("#conf-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
	});

    $("#conf-data-cancel-finalize").on("click", function() {
        $(".modal-body").empty();
        $("#finalizeLabel").css("color","");
    });


    $("#conf-finalize").on("click", function() {
        var spanTag = $(".finalized-title").children("span");
        var rotNum = spanTag.eq(0).attr("id").split("-");
        var finalizeResult = postData({rotation_number: rotNum[1]},"inc/Controller/finalizeschedule.php");
        $.when(finalizeResult).then(function(result) {
            $("#finalizeLabel").text("Success: Schedule Finalized").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"finalize-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#finalizeLabel").text("Fail: Changes Not Made! Contact Admin!").css("color","#D63230");
        });
    });

    //Send data to PHP file to be updated in the database
    $("#conf-data-save").on("click", function() {
        var updatedStartDates = $(".updated-start-date");
        var updatedCongNames = $(".updated-cong-name");
        var updatedRotations = $(".updated-rotation");

        var updatedCongData = [];
        for(var i = 0; i < updatedCongNames.length; i++) {
            var updatedCong = {};
            updatedCong.startDate = updatedStartDates.eq(i).text();
            updatedCong.congName = updatedCongNames.eq(i).text();
            updatedCong.rotation = updatedRotations.eq(i).text();
            updatedCongData.push(updatedCong);
        }
        console.log(updatedCongNames);
        var updateData = postData({updatedData: updatedCongData},"inc/Controller/updateCongSch.php")
        $.when(updateData).then(function(updateDataResult) {
            $("#modalLabel").text("Success: Changes Made!").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"conf-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Changes Not Made! Contact Admin!").css("color","#D63230");
        });
	});

	//If the user clicks inside the "confirm password" field, show message
	$("#conf-password").focus(function() {
		$(this).next("p").css("display", "inline");
	});

	//If the user clicks outside the "confirm password" field, hide message
	$("#conf-password").focusout(function() {
		$(this).next("p").css("display", "none");
	});

	//While the user is typing inside the "confirm password" field, check to see if passwords match
	//Enable the submit button if the passwords match and the password has at least 8 characters
	$("#conf-password").keyup(function() {
		var newPass = $("#new-password").val();
		var confirmedPass = $("#conf-password").val();
		if(newPass === confirmedPass) {
			$("#pass-confirm-msg").css("color","#549F93");
			$("#done-word-conf").css("display","inline");

			//Checks to see if the word "done" is present for the "new password" field
			if($("#done-word-new").css("display") == "inline") {
				$("#pass-submit").prop("disabled",false);
			}
		}else {
			$("#pass-confirm-msg").css("color","#CC2936");
			$("#done-word-conf").css("display","none");
			$("#pass-submit").prop("disabled",true);
		}
	});

	//If the user clicks inside the "new password" field, show message
	$("#new-password").focus(function() {
        $("#eight-chars-msg").css("display", "inline");
        $("#spec-chars-msg").css("display", "inline");
	});

	//If the user clicks outside the "new password" field, hide message
	$("#new-password").focusout(function() {
		$("#eight-chars-msg").css("display", "none");
        $("#spec-chars-msg").css("display", "none");
	});

	//While the user is typing inside the "new password" field, check if password is 8 characters
	$("#new-password").keyup(function() {
		var newPass = $("#new-password").val();
		if($("#new-password").val().length >= 8) {
			$("#eight-chars-msg").css("color","#549F93");
			$("#done-word-new").css("display","inline");
		}else {
			$("#eight-chars-msg").css("color","#CC2936");
			$("#done-word-new").css("display","none");
		}
	});

	//Changes the rotation number on modal
	//Dynamically changes 13 week date ranges on modal
	$("#nxt-btn").click(function() {
		$("#prev-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber+1);

		var dateRanges = createCustomDateRangeArray();
		$(".modal-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

		if(parseInt($("#rot-number").text()) == getMaxRotationNumber()){
			$("#nxt-btn").css("display","none");
		}
	});

	//Changes the rotation number on modal
	//Dynamically changes 13 week date ranges on modal
	$("#prev-btn").click(function() {
		$("#nxt-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber-1);

		var dateRanges = createCustomDateRangeArray();
		$(".modal-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

		if(parseInt($("#rot-number").text()) == getMinRotationNumber()){
			$("#prev-btn").css("display","none");
		}
	});

	//password field show/hide listener
	$(".pw-toggle-group a").click(function() {
		var current = $(this).html();
		switch (current){
			case ('<i class="fa fa-eye"></i> Show'):
				$(this).html('<i class="fa fa-eye-slash"></i> Hide').addClass("pw-hide").parent().find("input").attr("type", "text");
				break;
			case ('<i class="fa fa-eye-slash"></i> Hide'):
				$(this).html('<i class="fa fa-eye"></i> Show').removeClass("pw-hide").parent().find("input").attr("type", "password");
				break;
			default:
				break;
		}
	});

	$("#blackout-date-sbmit").one("click",(function() {
		$.ajax({
			type: "post",
			url: "inc/Controller/fetchblackoutweeks.php",
			dataType: "json",
			success: function(data) {
				//Sets the returned data as a global variable
				blackoutWeekDates = data;
				displayBlackoutRanges(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
			     alert(textStatus);
		  	}
		});
	}));

    $("#show-calendar").one("click",(function() {
        $.ajax({
            type: "post",
            url: "inc/Controller/getFullCongSchedule.php",
            dataType: "json",
            success: function(data) {
                //Sets the returned data as a global variable
                fullSchedule = data;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
    }));


	//FUNCTIONS

	function createCustomDateRangeArray() {
		var firstIndex = getCurrRotationsFirstWeek();
		var lastIndex = firstIndex + 12;
		var dateRanges = new Array();
		for (var i = firstIndex; i <= lastIndex; i++) {
			dateRanges.push(blackoutWeekDates[i]);
		};
		return dateRanges;
	}//end createCustomDateRangeArray

	function displayBlackoutRanges(data) {
		for(var i = 0; i <= 12; i++) {
			if(data[i]['holiday'] == 1){
				var label = $("<label>").addClass("checkbox-inline");

				var input = $("<input>").attr("type","checkbox");
				input.attr("name","blackoutWeek[]");
				input.attr("id","blackoutWeek");
				input.attr("value",data[i]['startDate']);
				label.append(input);
				label.append("<strong>Week "+data[i]['weekNumber']+
					"	("+data[i]['startDate']+" to "
						+data[i]['endDate']+") HOLIDAY!</strong>");

				$(".modal-checkboxes").append(label);
				$(".modal-checkboxes").append("<br />");
			}else {
				var label = $("<label>").addClass("checkbox-inline");

				var input = $("<input>").attr("type","checkbox");
				input.attr("name","blackoutWeek[]");
				input.attr("id","blackoutWeek");
				input.attr("value",data[i]['startDate']);
				label.append(input);
				label.append("Week "+data[i]['weekNumber']+
					"	("+data[i]['startDate']+" to "
						+data[i]['endDate']+")");

				$(".modal-checkboxes").append(label);
				$(".modal-checkboxes").append("<br />");
			}
		}
	}//end displayBlackoutRanges

	function getCurrRotationsFirstWeek() {
		var rotNumber = parseInt($("#rot-number").text());
		var indexOfFirstWeek;
		for (var i = 0; i < blackoutWeekDates.length; i += 13) {
			if(blackoutWeekDates[i]['rotation_number'] == rotNumber) {
				indexOfFirstWeek = i;
				break;
			}
		};
		return indexOfFirstWeek;
	}

	/* Returns the maximum rotation number from date range array
	 * @return blackoutWeekDates[lastIndex]['rotation_number'] - last rotation number
	 */
	function getMaxRotationNumber() {
		var lastIndex = blackoutWeekDates.length - 1;
		return blackoutWeekDates[lastIndex]['rotation_number'];
	}//end getMinRotationNumber

	/* Returns the minimum rotation number from date range array
	 * @return blackoutWeekDates[0]['rotation_number'] - first rotation number
	 */
	function getMinRotationNumber() {
		return blackoutWeekDates[0]['rotation_number'];
	}//end getMinRotationNumber
});
