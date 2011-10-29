<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <!-- CSS -->
   <link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" />
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.calendarPicker.css" />
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/calendar.css" />
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/fullcalendar.css" />
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/fullcalendar.print.css" media="print"/>

</head>
<body>

<div class="span6" id="eventCalendar" style="margin-top: 50px;">
	<div id="dest" style="width:340px"></div>
	<div id="calendar">
</div>

<!-- Javascript -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.calendarPicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/calendar.js"></script>

<script>
$(document).ready(function() {
	// var e = '<?php echo $events; ?>';
	// If the e is buggy, try the complete string instead of the object
	var e = '[{"title":"Rickie Byars Beckwith","start_time":"2011-10-30 08:00:00","stop_time":"2011-10-30 14:00:00"},{"title":"Forbidden City NightClub","start_time":"2011-10-30 18:30:00","stop_time":""},{"title":"Jeremiah Roiko Band","start_time":"2011-11-04 20:00:00","stop_time":""},{"title":"RELATIONS - All Things House","start_time":"2011-11-03 00:00:00","stop_time":""},{"title":"CLUB GABAH","start_time":"2011-10-29 22:30:00","stop_time":"2011-10-30 02:00:00"},{"title":"Indigo Lounge","start_time":"2011-10-31 20:00:00","stop_time":"2011-11-01 00:00:00"},{"title":"ROCKTANE","start_time":"2011-10-29 22:00:00","stop_time":""},{"title":"GUNPOWDER SECRETS","start_time":"2011-10-30 00:00:00","stop_time":""},{"title":"One Voice\/Voices from the Middle East Pt. 2 - Skype and Discuss","start_time":"2011-11-01 18:30:00","stop_time":""},{"title":"Alesana with Sleeping with Sirens","start_time":"2011-11-03 17:30:00","stop_time":""},{"title":"LA Sucks","start_time":"2011-10-29 00:00:00","stop_time":""},{"title":"Calling for all Creative Talents","start_time":"2011-06-09 00:00:00","stop_time":"2011-10-31 00:00:00"},{"title":"Calling for Creative Talents","start_time":"2011-05-10 00:00:00","stop_time":"2011-10-31 00:00:00"},{"title":"Calling for Creative Talents","start_time":"2011-05-24 00:00:00","stop_time":"2011-10-31 00:00:00"},{"title":"ACTING CLASSES from a star of the film \u00e2\u0080\u009cALIVE\u00e2\u0080\u009d - Los Angeles, CA","start_time":"2011-10-29 00:00:00","stop_time":""},{"title":"ACTING STUDIO: Acting Classes \u00e2\u0080\u0093 from a star of the film \u00e2\u0080\u009cAlive\u00e2\u0080\u009d - Los Angeles, CA","start_time":"2011-10-29 00:00:00","stop_time":""},{"title":"A MONTH OF FREE ACTING CLASSES \u00e2\u0080\u0093 A star of the film \u00e2\u0080\u009cALIVE\u00e2\u0080\u009d - Los Angeles, CA","start_time":"2011-10-29 00:00:00","stop_time":""},{"title":"LEARN TO WORK IN FILM AND TELEVISION AS A PROFESSIONAL ACTOR Los Angeles, CA","start_time":"2011-10-29 00:00:00","stop_time":""},{"title":"Movie Magic Budgeting & Scheduling Seminar with Otter Huntley","start_time":"2011-11-02 10:00:00","stop_time":"2011-11-02 12:00:00"},{"title":"CEC Free Movies: Friends With Benefits","start_time":"2011-11-04 19:00:00","stop_time":"2011-11-04 23:59:00"}]';

	var event_cal = {'events':null, 'eventList':new Array(), 'eventDateList':new Array()};
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // Initialize the event objects the  event_cal object, which is used lateron
   	event_cal.initEvents = function(e) {
    	// event_cal.events = eval('(' + e + ')');//not using it because of security issue, see http://www.json.org/js.html
    	event_cal.events = JSON.parse(e, function (key, value) {
		    var type;
		    if (value && typeof value === 'object') {
		        type = value.type;
		        if (typeof type === 'string' && typeof window[type] === 'function') {
		            return new (window[type])(value);
		        }
		    }
		    return value;
		});
    }

    event_cal.parse_date = function(string) {
    	var date = new Date();
	    var parts = String(string).split(/[- :]/);

	    date.setFullYear(parts[0]);
	    date.setMonth(parts[1] - 1);
	    date.setDate(parts[2]);
	    date.setHours(parts[3]);
	    date.setMinutes(parts[4]);
	    date.setSeconds(parts[5]);
	    date.setMilliseconds(0);

	    return date;
    }

    // Get the eventList and eventDateList populated with the data
    event_cal.constructEventsList = function() {
    	$.each(event_cal.events, function(i, v){
    		var tmp = {title: v.title,
    					start: v.start_time,
    					end: v.stop_time,
    					allDay: false};
    		event_cal.eventList.push(tmp);
    		var date_str = event_cal.parse_date(v.start_time);
    		var date = new Date(date_str);
    		event_cal.eventDateList.push(date.toDateString());
    	})
    };

    event_cal.initEvents(e);
    event_cal.constructEventsList();

    var fullCal = $('#calendar').fullCalendar({
    	header: false,
        height: 300,
        contentHeight: 200,
        editable: false,
        defaultView: 'agendaDay',
        slotMinutes: 60,
        allDaySlot: false,
        firstHour: 0,
        events: event_cal.eventList
    });

   var calendarPickr = $("#dest").calendarPicker({
      monthNames:["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
       dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
       //useWheel:true,
       //callbackDelay:500,
       enableYears: false,
       //months:3,
       //days:4,
       //showDayArrows:false,
       eventDates: event_cal.eventDateList,
       callback:function(cal) {
  			fullCal.fullCalendar('gotoDate', cal.currentDate);
       }
     });


});
</script>

</body>
</html>
