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
         if (places.error) {
            // console.log(places.error);
            return false;
         }
         $(places).each(updateMap);
      }, 'json');
      return false;
   }


   function updateMap() {
      // This returns a reference to elements in array
      var place = this;
      // Set the marker to the lon lat of a place location
      var marker = new google.maps.Marker({
         position: new google.maps.LatLng(place.latitude, place.longitude),
            map   : map,
            title : place.title,
            icon  : public_url+'images/pin.png'
      });

      markerArray.push(marker);

      // Do the drop animation for each element
      marker.setAnimation(google.maps.Animation.DROP);

      // For each element, we add the event listener...
      google.maps.event.addListener(marker, 'click', function() {
         var hidingMarker = currentPlace;
         var slideIn = function(marker) {  
            $('#event_title', info).text(place.title);
            $('#event_desc',  info).text(place.description);  
            info.animate({right: '0'});  
         }

         marker.setIcon(icons['train-selected']);  
         if (currentPlace) {  
            currentPlace.setIcon(icons['train']);  
            info.animate(  
         { right: '-320px' },  
         { complete: function() {  
            if (hidingMarker != marker) {  
               slideIn(marker);  
            } else {  
               currentPlace = null;  
            }  
         }}  
         );  
         } else {  
            slideIn(marker);  
         }  
         currentPlace = marker;  

      });  
      
   }

});
