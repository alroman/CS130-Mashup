$(document).ready(function() {
      
   function clearOverlays() {
      if (markerArray) {
        for (i in markerArray) {
            markerArray[i].setMap(null);
        }
      };
   }

   $('.tag').click(function(){
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
   function filterEvents ($form) {
      var $params  = $form.find('input:checked'),
          url      = $form.attr('action'),
          params   = {'category[]' : []},
          location = $form.find('#location').val();
      $.each($params, function(i, input){
         var cat = $(input).val();
         if (cat == 'movies') {
            cat = 'movies_film';
         };
         params['category[]'].push(cat);
      });
      params['location'] = location;
      // console.log(params);
      $.post(url, params, function(places){
         clearOverlays();
         update_event_list(places);
         if (places.error) {
            // console.log(places.error);
            return false;
         }
         $(places).each(app.updateMap);
      }, 'json');
      return false;
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
});
