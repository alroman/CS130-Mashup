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

<div class="span6" id="eventCalendar">
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
	;var e = '<?php echo $events; ?>';
	// If the e is buggy, try the complete string instead of the object
	// var e = '[{"title":"Forbidden City NightClub","start_time":"2011-11-01 18:30:00","stop_time":""},{"title":"RELATIONS - All Things House","start_time":"2011-11-03 00:00:00","stop_time":""},{"title":"CLUB GABAH","start_time":"2011-11-05 22:30:00","stop_time":"2011-11-06 01:00:00"},{"title":"Indigo Lounge","start_time":"2011-10-31 20:00:00","stop_time":"2011-11-01 00:00:00"},{"title":"Hollywood Blonde","start_time":"2011-11-05 21:00:00","stop_time":""},{"title":"Writer Prima","start_time":"2011-11-01 19:00:00","stop_time":""},{"title":"Julia Price","start_time":"2011-11-01 22:00:00","stop_time":""},{"title":"dj tory tee","start_time":"2011-11-02 21:00:00","stop_time":""},{"title":"Marv Robinson","start_time":"2011-11-04 20:15:00","stop_time":""},{"title":"Local Culture","start_time":"2011-11-05 20:00:00","stop_time":""},{"title":"LA Sucks","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"ACTING CLASSES from a star of the film ALIVE - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"ACTING STUDIO: Acting Classes from a star of the film Alive - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"A MONTH OF FREE ACTING CLASSES A star of the film ALIVE - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"LEARN TO WORK IN FILM AND TELEVISION AS A PROFESSIONAL ACTOR Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"CASABLANCA (1942) & THE MALTESE FALCON (1941)","start_time":"2011-11-04 19:30:00","stop_time":"2011-11-04 23:25:00"},{"title":"The 3rd Annual Los Angeles Transgender Film Festival: Truth to Power","start_time":"2011-11-06 15:00:00","stop_time":"2011-11-06 16:00:00"},{"title":"New Media Film Festival","start_time":"2011-11-04 00:00:00","stop_time":"2011-11-05 00:00:00"},{"title":"The 3rd Annual Los Angeles Transgender Film Festival - Orchids: My Intersex Adventure","start_time":"2011-11-06 18:00:00","stop_time":"2011-11-06 20:00:00"},{"title":"MET Siegfried LIVE","start_time":"2011-11-05 00:00:00","stop_time":""}]';

  	var event_cal = {'events':null, 'eventList':new Array(), 'eventDateList':new Array()};
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // Initialize the event objects the  event_cal object, which is used lateron
   	event_cal.initEvents = function(e) {
    	// event_cal.events = eval('(' + e + ')');//not using it because of security issue, see http://www.json.org/js.html
    	event_cal.events = JSON.parse(e, event_cal.reviver);
    }

    event_cal.reviver = function (key, value) {
	    var type;
	    if (value && typeof value === 'object') {
	        type = value.type;
	        if (typeof type === 'string' && typeof window[type] === 'function') {
	            return new (window[type])(value);
	        }
	    }
	    return value;
	}

    event_cal.parse_date = function(string) {
	    var parts = String(string).split(/[- :]/);
      var date = new Date(parts[0], (parts[1] - 1), parts[2], parts[3], parts[4], parts[5]);

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
        // console.log(v.start_time);
    		var date = event_cal.parse_date(v.start_time);
        // console.log(date.getMonth());
    		event_cal.eventDateList.push(date.toDateString());
    	})
    };

    event_cal.initEvents(e);
    event_cal.constructEventsList();
    // console.log(event_cal.eventDateList);

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
