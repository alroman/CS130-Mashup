<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
        <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
      }
      .container > footer p {
        text-align: center; /* center align it with the container */
      }
      .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
      }

      /* The white background content wrapper */
      .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
           -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      /* Page header tweaks */
      .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
      }

      /* Styles you shouldn't keep as they are for displaying this base example only */
      .content .span10,
      .content .span4 {
        min-height: 500px;
      }
      /* Give a quick and non-cross-browser friendly divider */
      .content .span4 {
        margin-left: 0;
        padding-left: 19px;
        border-left: 1px solid #eee;
      }

      .topbar .btn {
        border: 0;
      }

    </style>

    <!--CSS For Event Calendar-->
   <link rel="stylesheet" href="http://localhost/CS130-Mashup/web/css/jquery.calendarPicker.css" />
   <link rel="stylesheet" href="http://localhost/CS130-Mashup/web/css/calendar.css" />
   <link rel="stylesheet" href="http://localhost/CS130-Mashup/web/css/fullcalendar.css" />
   <link rel="stylesheet" href="http://localhost/CS130-Mashup/web/css/fullcalendar.print.css" media="print"/>
   <!-- End -->

	<meta charset="utf-8">
        <?php $display_city = isset($city) ? $city : "Los Angeles"?>
	<title>Events from <?php echo $display_city ?> presentation</title>
	<link rel="stylesheet" href="../development-bundle/themes/base/jquery.ui.all.css">
	<script  type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
	<script  type="text/javascript" src="../development-bundle/ui/jquery.ui.core.js"></script>
	<script  type="text/javascript" src="../development-bundle/ui/jquery.ui.widget.js"></script>
	<script  type="text/javascript" src="../development-bundle/ui/jquery.ui.accordion.js"></script>

  <script  type="text/javascript" src="http://localhost/CS130-Mashup/web/js/jquery-1.6.2.min.js"></script>
  <script  type="text/javascript" src="http://localhost/CS130-Mashup/web/development-bundle/ui/jquery.ui.core.js"></script>
  <script  type="text/javascript" src="http://localhost/CS130-Mashup/web/development-bundle/ui/jquery.ui.widget.js"></script>
  <script  type="text/javascript" src="http://localhost/CS130-Mashup/web/development-bundle/ui/jquery.ui.accordion.js"></script>
  <script>
	$(function() {
		$( "#accordion" ).accordion({
			fillSpace: true,
      autoHeight: false,
			navigation: true,
      collapsible: true
		});
	});
	</script>
</head>
<body>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="#">Entertainment+</a>

          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">Unit Test</a></li>
            <li><a href="#contact">??</a></li>
          </ul>
          <?php if (@$display_img): ?>
          <span style="float:right; vertical-align: middle"><?php echo $display_img ?></span>
          <?php else: ?>
          <span style="float:right; margin-top: 8px; vertical-align: bottom"><a href="<?php echo $login_url ?>"> <img src="../img/connect2.gif" /></a></span>
            
            <?php endif; ?>

        </div>
      </div>
    </div>

    <div class="container">

      <div class="content">
        <div class="page-header">
            
            
          <h1>E+ <small>Here is what's happening in <?php echo $display_city ?></small></h1>
        </div>
        <div class="row">
          <div class="span10">
    <?php if (@$logout_url): ?>
        <a href="<?php echo $logout_url ?>">logout</a>
    <?php endif; ?>
            <h2>Feeds:</h2>
    <?php if (@$user_profile): ?>
        <pre>
            <?php echo print_r($user_profile, TRUE) ?>
        </pre>
   <?php else: ?>
        You are not logged in.

    <?php endif; ?>
 
    <?php if (@$likes): ?>
        <pre>
            <?php echo print_r($likes, TRUE) ?>
        </pre>
    <?php endif; ?>
          </div>
          <div class="span4">
            <h3>Secondary content</h3>
            nothing here yet...
          </div>
          <div class="span6" id="eventCalendar" style="margin-top: 10px;">
            <div id="dest" style="width:340px"></div>
            <div id="calendar"></div>
          </div>
        </div>


      </div>



      <footer>
        <p>&copy; E+ 2011</p>

      </footer>

    </div> <!-- /container -->

<!-- Javascript for event calendar -->
<script type="text/javascript" src="http://localhost/CS130-Mashup/web/js/fullcalendar.min.js"></script>
<script type="text/javascript" src="http://localhost/CS130-Mashup/web/js/jquery.calendarPicker.js"></script>
<script type="text/javascript" src="http://localhost/CS130-Mashup/web/js/calendar.js"></script>

<script>
$(document).ready(function() {
  ;var e = <?php echo $events_cal; ?>;
  // If the e is buggy, try the complete string instead of the object
  // var e = '[{"title":"Forbidden City NightClub","start_time":"2011-11-01 18:30:00","stop_time":""},{"title":"RELATIONS - All Things House","start_time":"2011-11-03 00:00:00","stop_time":""},{"title":"CLUB GABAH","start_time":"2011-11-05 22:30:00","stop_time":"2011-11-06 01:00:00"},{"title":"Indigo Lounge","start_time":"2011-10-31 20:00:00","stop_time":"2011-11-01 00:00:00"},{"title":"Hollywood Blonde","start_time":"2011-11-05 21:00:00","stop_time":""},{"title":"Writer Prima","start_time":"2011-11-01 19:00:00","stop_time":""},{"title":"Julia Price","start_time":"2011-11-01 22:00:00","stop_time":""},{"title":"dj tory tee","start_time":"2011-11-02 21:00:00","stop_time":""},{"title":"Marv Robinson","start_time":"2011-11-04 20:15:00","stop_time":""},{"title":"Local Culture","start_time":"2011-11-05 20:00:00","stop_time":""},{"title":"LA Sucks","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"ACTING CLASSES from a star of the film ALIVE - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"ACTING STUDIO: Acting Classes from a star of the film Alive - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"A MONTH OF FREE ACTING CLASSES A star of the film ALIVE - Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"LEARN TO WORK IN FILM AND TELEVISION AS A PROFESSIONAL ACTOR Los Angeles, CA","start_time":"2011-10-31 00:00:00","stop_time":""},{"title":"CASABLANCA (1942) & THE MALTESE FALCON (1941)","start_time":"2011-11-04 19:30:00","stop_time":"2011-11-04 23:25:00"},{"title":"The 3rd Annual Los Angeles Transgender Film Festival: Truth to Power","start_time":"2011-11-06 15:00:00","stop_time":"2011-11-06 16:00:00"},{"title":"New Media Film Festival","start_time":"2011-11-04 00:00:00","stop_time":"2011-11-05 00:00:00"},{"title":"The 3rd Annual Los Angeles Transgender Film Festival - Orchids: My Intersex Adventure","start_time":"2011-11-06 18:00:00","stop_time":"2011-11-06 20:00:00"},{"title":"MET Siegfried LIVE","start_time":"2011-11-05 00:00:00","stop_time":""}]';

    var event_cal = {'events':null, 'eventList':new Array(), 'eventDateList':new Array()};
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // Initialize the event objects the  event_cal object, which is used lateron
    event_cal.initEvents = function(e) {
      event_cal.events = e;
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

     //Added color for odd lines of the calendar
     $("table.fc-agenda-slots tr:even").addClass('tr_odd');
});
</script>

</body>
</html>