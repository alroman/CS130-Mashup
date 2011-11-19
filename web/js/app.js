$(document).ready(function() {
   if (markerArray === undefined) {
      alert('markerArray is undefined, please fixed it in the footer.php');
   }
   if (all_events === undefined) {
      alert('all_events is undefined, please fixed it in the footer.php');
   };


   function clearOverlays() {
      if (markerArray) {
        for (marker in markerArray) {
            markerArray[marker].setMap(null);
        }
      };
   }

   $('.tag').live('click', function(){
      var $div     = $(this);
      var checkbox = $div.find('input');

      $div.toggleClass('active');
      if (checkbox.is(':checked')) {
         checkbox.prop('checked', false);
      } else {
         checkbox.prop('checked', true);
      }
      var $form = $('#tag_form');
      filterEvents($form);
      return false;
   });

   // Ajax calls for using updating the events
   // Passed all the events and then filter them out based on users action.
   function filterEvents ($form) {
      var $params  = $form.find('input:checked'),
          url      = $form.attr('action'),
          params   = {'category[]' : []};
      
      //loop the inputs and get the value to create params
      $.each($params, function(i, input){
         var cat = $(input).val();
         params['category[]'].push(cat);
      });
      
      params['events'] = all_events; //get all the events
      // AJAX call to the server
      $.post(url, params, function(data){
         clearOverlays();
         update_event_list(data);
         update_event_cal(data);

         if (data.error) {
            return false;
         }
         $(data).each(app.updateMap);
      }, 'json');
      return false;
   }

   function update_event_cal(places) {
      var $dest = $('#dest'),
          $calendar = $('#calendar'),
          $event_cal = $('#eventCalendar');
      $dest.empty();
      $calendar.empty();
      event_cal.emptyEvents();
      event_cal.initEvents(places);
      event_cal.constructEventsList();
      event_cal.generateCalendar();
   }
   
   function update_event_list(places) {
      //Update the event list
      var $event_list = $('.event_list'),
          $lists = $event_list.children();
      $lists.remove();
      if (places.error) {
         $event_list.append("<div class='event_title'>Sorry, no event.</div>");
         return false;
      };
      $(places).each(function(i, place){
         $event_list.append("<div class='event_title'>"+place.title+"</div>");
      });
   }

   //=========Start with adding tag filter=============
   //
   var tags = {
      'trigger' : $('#addTags'),
      'tagWrapper': $('#tag-wrapper')
   };

   tags.initEvents = function() {
      tags.trigger.click(function(){
         var html = "<input class='span2' type='text' id='tag-input' name='tag-input' placeholder='Tag' style='padding: 1px 5px; margin-left: 5px;'></input>";
         $(this).hide().after(html);
         var $input = $('#tag-input');
         $input.focus();
         $input.focusout(function(){
            $(this).remove();
            tags.trigger.show();
         });
         tags.ajaxifyInput($input);
      });
   }

   tags.ajaxifyInput = function($input) {
      $input.keypress(function(e){
         if (e.which == 13) {
            e.preventDefault();
            
            var input_val = $input.val();
            var $form = $('#tag_form');

            if (tags.validateInput(input_val, $form)) {
               //Append tag to the subbanner
               var html = '<li><div class="input-prepend"><label class="add-on tag active"><input value='+input_val+' type="checkbox" class="tag_checkbox" style="display:none;" checked="true"><span class="tags_text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+input_val+'</span></label></div></li>';
               $input.remove();
               tags.tagWrapper.before(html);
               tags.trigger.show();
               
               filterEvents($form);
            };
         }
      });
   }

   tags.validateInput = function(input_val, $form) {
      //Input canot be dulpicate
      var $params  = $form.find('input:checked'),
          is_valid = true;
      
      $.each($params, function(i, input){
         var cat = $(input).val();
         if (input_val.toLowerCase() == cat.toLowerCase()) {
            alert('Your input is not valid, please try again.');
            is_valid = false;
            return is_valid;
         };
      });

      return is_valid;
   }

   tags.initEvents();

   //==============Start with Event Calendar================
   //Init variables
   var e = places;
   window.event_cal = {'events':null, 'eventList':new Array(), 'eventDateList':new Array()};
   var date = new Date();
   var d = date.getDate();
   var m = date.getMonth();
   var y = date.getFullYear();

   // Initialize the event objects the  event_cal object, which is used lateron
   event_cal.initEvents = function(e) {
      event_cal.events = e;
   };

   event_cal.emptyEvents = function() {
      event_cal.events = null;
      event_cal.eventList = new Array();
      event_cal.eventDateList = new Array();
   };

   event_cal.parse_date = function(string) {
      var parts = String(string).split(/[- :]/);
      var date = new Date(parts[0], (parts[1] - 1), parts[2], parts[3], parts[4], parts[5]);

      return date;
   };

   // Get the eventList and eventDateList populated with the data
   event_cal.constructEventsList = function() {
      $.each(event_cal.events, function(i, v){
         var tmp = {title: v.title,
            start: v.start_time,
         end: v.stop_time,
         allDay: false};
         event_cal.eventList.push(tmp);
         var date = event_cal.parse_date(v.start_time);
         event_cal.eventDateList.push(date.toDateString());
      })
   };

   event_cal.generateCalendar = function() {
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
          enableYears: false,
          months:1,
          days:3,
          //showDayArrows:false,
          eventDates: event_cal.eventDateList,
          callback:function(cal) {
             fullCal.fullCalendar('gotoDate', cal.currentDate);
          }
      });

      //Added color for odd lines of the calendar
      $("table.fc-agenda-slots tr:even").addClass('tr_odd');
   }

   event_cal.initEvents(e);
   event_cal.constructEventsList();
   // console.log(event_cal.eventDateList);
   event_cal.generateCalendar();
});
