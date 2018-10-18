$(document).ready(function() {
	//Global Variables
	var blackoutWeekDates;

	$("#blackoutSubmit").on("click", function() {
        var congBlackouts = [];
        $(".blackoutWeek:checked").each(function(i) {
            congBlackouts.push($(this).val());
        });
        var currUserEmail = $("#curr-user").text();
        var insertResult = postData({congBlackoutData: congBlackouts, email: currUserEmail}, "inc/Controller/insertcongblackoutdata.php");
        $.when(insertResult).then(function(congInsertResult) {
            console.log(congInsertResult);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
        // console.log(congBlackouts);
    });

	$("body").on("click", "#admin-submit", function() {
		var editedDivs = $("tr").filter(function() {
            var editedColor = $(this).css("background-color");
            return editedColor === "rgb(255, 202, 58)" || editedColor === "#FFCA3A";
		});
        $("#modalLabel").text("Nothing Changed");
        if(editedDivs) {
        	editedDivs.each(function(i) {
        		var optionVals = editedDivs.eq(i).children("td").eq(1).children("select").val().split(",");
        		var editedCong = $("<div>").attr("class","edited-congs");

        		var startDateHeading = $("<p>").append($("<strong>").text("Start Date: "));
                startDateHeading.append($("<span>").attr("class","updated-start-date").text(optionVals[2]));

                var newCongHeading = $("<p>").append($("<strong>").text("New Congregation: "));
                newCongHeading.append($("<span>").attr("class","updated-cong-name").text(optionVals[0]));

                var rotationHeading = $("<p>").append($("<strong>").text("Rotation: "));
                rotationHeading.append($("<span>").attr("class","updated-rotation").text(optionVals[1]));

        		editedCong.append(startDateHeading);
        		editedCong.append(newCongHeading);
        		editedCong.append(rotationHeading);

                $("#modalLabel").text("Please Confirm Changes");
        		$(".modal-body").append(editedCong);
			});
        }
	});

    //On selection of one of the check marks for the host congregation blackouts
    $("body").on("change", ".blackoutWeek", function() {
        $('#calendar').fullCalendar('gotoDate', this.value);
    });

    //The "Ok" button when the admin clicks to update changes made to the schedule
    $("body").on("click", "#conf-ok-btn", function() {
        window.location.replace("adminCongSchedule.php");
    });

    //When an admin changes a congregation, change the background
    $("body").on("change", "select", function() {
    	var currentCong = $(this).find(".curr-sch-cong").val().split(",");
    	var newCong = $(this).val().split(",");
    	if(newCong[0] !== currentCong[0]) {
            $(this).parent().parent().css("background-color","#FFCA3A");
		}else {
            $(this).parent().parent().css("background-color","");
		}
    });

    //Full calendar congregation blackout inputs
    $('#calendar').fullCalendar({

    });

    $("#conf-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
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
        var updateData = postData({updatedData: updatedCongData},"inc/Controller/updateCongSch.php")
        $.when(updateData).then(function(updateDataResult) {
            $("#modalLabel").text("Success: Changes Made!").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"conf-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
            // console.log(updateDataResult);
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
		$(".blackout-checkboxes").empty();
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
		$(".blackout-checkboxes").empty();
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

    /*$("#show-calendar").on("click",(function() {
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
    }));*/

	//AJAX CALLS

	//Modular way to get data
	var getData = function(params, url) {
		if(params.length == 0) {
            return $.ajax({
                type: "get",
                dataType: 'json',
                url: url
            });
		}else {
            return $.ajax({
                type: "get",
                data: params,
                dataType: 'json',
                url: url
            });
		}
	}

	var postData = function(params, url) {
        if(params.length == 0) {
            return $.ajax({
                type: "post",
                dataType: 'json',
                url: url
            });
        }else {
            return $.ajax({
                type: "post",
                data: params,
                dataType: 'json',
                url: url
            });
        }
    }

	//Fetch the dates for congregations to input their blackouts on
	var blackoutWeekDates = getData({},"inc/Controller/fetchblackoutweeks.php");
	$.when(blackoutWeekDates).then(function(blackoutWeeks) {
		blackoutWeekDates = blackoutWeeks;
        displayBlackoutRanges(blackoutWeeks);
	}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
    });

	//Setup the admin congregation schedule
    var getRotationNums = getData({},"inc/Controller/fetchScheduledRotationNums.php"),
        getFullSchedule = getData({},"inc/Controller/fetchfullschedule.php"),
        eligibleCongregations = getData({},"inc/Controller/fetchEligibleCongregations.php");

    $.when(getRotationNums,getFullSchedule,eligibleCongregations).then(function(rotationNums, fullSchedule, eligibleCongs) {
    	$(".loader").hide();
    	var startDates = Object.keys(eligibleCongs[0]);
    	startDates = startDates.sort(function (a, b) {
            return new Date(a).getTime() - new Date(b).getTime()
        });
    	var table = $("<table>").addClass("table");
        table.attr("id","final-cong-schedule");
        var rotationCount = startDates.length / 13;

        var weekCount = 0;
        var congCount = 0;
        for(var i = 0; i < rotationCount; i++) {
            var tableHead = $("<thead>");
            tableHead.addClass("rotation-head");
            var tableRow = $("<tr>");
            var tableHeading1 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading1.text("Start Date");
            var tableHeading2 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading2.text("Rotation #"+rotationNums[0][i]["rotationNumber"]);
            var tableHeading3 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading3.text("Approved Schedule as of:");

            tableRow.append(tableHeading1);
            tableRow.append(tableHeading2);
            tableRow.append(tableHeading3);
            tableHead.append(tableRow);

            table.append(tableHead);

            var tableBody = $("<tbody>");
            for(var h = weekCount; h < weekCount + 13; h++) {
                var tableBodyRow = $("<tr>");
                tableBodyRow.addClass("scheduled-date");

                var tableData = $("<td>");
                if(fullSchedule[0][h]["holiday"] === "Yes"){
                    var strongTag = $("<strong>");
                    strongTag.text(fullSchedule[0][h]["start"]+" HOLIDAY!");
                    tableData.append(strongTag);
				}else {
                    tableData.text(fullSchedule[0][h]["start"]);
				}

				var tableData2 = $("<td>").addClass("congName").attr("id","cong"+congCount);

                var selectOption = $("<select>").addClass("form-control congNames");
                selectOption.append(createHeader("Currently Scheduled"));

                var firstOption = $("<option>").addClass("curr-sch-cong").attr({"selected": "selected","value": fullSchedule[0][h]["title"]
																			+","+rotationNums[0][i]["rotationNumber"]}).text(fullSchedule[0][h]["title"]);
                selectOption.append(firstOption);

                selectOption.append(createSpaceOption());

                selectOption.append(createHeader("Eligible Congregations"));

                for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                    if (eligibleCongs[0][startDates[h]][k]["eligible"] !== "No") {
                        var eligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+rotationNums[0][i]["rotationNumber"]+
																","+fullSchedule[0][h]["start"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                        selectOption.append(eligibleOption);
                    }
                }

                selectOption.append(createSpaceOption());

                var divider = $("<option>").attr("disabled", "disabled");
                divider.text("──────────");
                selectOption.append(divider);

                selectOption.append(createSpaceOption());

                selectOption.append(createHeader("Ineligible Congregations"));

                for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                    if (eligibleCongs[0][startDates[h]][k]["eligible"] === "No") {
                        var ineligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+rotationNums[0][i]["rotationNumber"]+
																","+fullSchedule[0][h]["start"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                        selectOption.append(ineligibleOption);
                    }
                }
                tableData2.append(selectOption);

                var tableData3 = $("<td>");
                tableData3.text("");

                tableBodyRow.append(tableData);
                tableBodyRow.append(tableData2);
                tableBodyRow.append(tableData3);

                tableBody.append(tableBodyRow);
                congCount++;
            }
            weekCount+=13;

            table.append(tableBody);
        }
        $("#admin-schedule").append(table);
        var adminButtons = $("<div>").attr("id","admin-cong-buttons");
        adminButtons.append($("<button>").attr({"id": "admin-submit", "type": "submit", "data-toggle": "modal", "data-target":"#conf-data-submit"}).addClass("btn btn-primary").text("Submit Changes"));
        adminButtons.append($("<button>").attr({"id": "admin-finalize", "type": "submit"}).addClass("btn btn-success").text("Finalize Schedule"));
        $("#admin-schedule").append(adminButtons);
	}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus);
	});

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

	function createHeader(text) {
		return $("<option>").attr("disabled", "disabled").text(text);
	}//end createHeader

	function createSpaceOption() {
        return $("<option>").attr("disabled", "disabled").text("");
	}//end createSpaceOption

	function displayBlackoutRanges(data) {
		for(var i = 0; i <= 12; i++) {
			if(data[i]['holiday'] == 1) {
                if(data[i]['startDate'] == "1970-01-01") {

                }else {
                    var label = $("<label>").addClass("checkbox-inline");

                    var input = $("<input>").attr("type", "checkbox");
                    input.attr("name", "blackoutWeek[]");
                    input.attr("class", "blackoutWeek");
                    input.attr("value", data[i]['startDate']);
                    label.append(input);
                    label.append("<strong>Week " + data[i]['weekNumber'] +
                        "	(" + data[i]['startDate'] + " to "
                        + data[i]['endDate'] + ") HOLIDAY!</strong>");

                    $(".blackout-checkboxes").append(label);
                    $(".blackout-checkboxes").append("<br />");
                }
			}else {
                if(data[i]['startDate'] == "1970-01-01") {

                }else {
                    var label = $("<label>").addClass("checkbox-inline");

                    var input = $("<input>").attr("type", "checkbox");
                    input.attr("name", "blackoutWeek[]");
                    input.attr("class", "blackoutWeek");
                    input.attr("value", data[i]['startDate']);
                    label.append(input);
                    label.append("Week " + data[i]['weekNumber'] +
                        "	(" + data[i]['startDate'] + " to "
                        + data[i]['endDate'] + ")");

                    $(".blackout-checkboxes").append(label);
                    $(".blackout-checkboxes").append("<br />");
                }
			}
			if(i == 12) {
                var label = $("<label>").addClass("checkbox-inline");

                var input = $("<input>").attr("type","checkbox");
                input.attr("name","blackoutWeek[]");
                input.attr("class","blackoutWeek");
                input.attr("value","1970-01-01-"+$("#rot-number").text());
                label.append(input);
                label.append("No Blackouts (Available for the whole rotation)");

                $(".blackout-checkboxes").append(label);
                $(".blackout-checkboxes").append("<br />");
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
