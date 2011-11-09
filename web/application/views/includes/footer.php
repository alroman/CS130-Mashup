<script>  
$(document).ready(function(){

      // Set the default location
      var lon = <?php echo $geoloc['longitude']; ?>;
      var lat = <?php echo $geoloc['latitude']; ?>;
      var mapDefaultLocation = new google.maps.LatLng(lat, lon);
      var mapOptions = {
         zoom:      11,
            center:    mapDefaultLocation,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: false
      }
      var places = <?php echo $all_events; ?>;

      var map = new google.maps.Map($("#map_canvas")[0], mapOptions);

      var icons = {
         'train':          '<?php echo $public_url;?>images/pin.png',  
         'train-selected': '<?php echo $public_url;?>images/pin.png'  
      }

      var currentPlace = null;
      var info = $('#placeDetails');

      // This iterates through each element in the 'places' array
      $(places).each(function() {
         // This returns a reference to elements in array
         var place = this;
         // Set the marker to the lon lat of a place location
         var marker = new google.maps.Marker({
            position: new google.maps.LatLng(place.latitude, place.longitude),
               map:      map,
               title:    place.title,
               icon:     '<?php echo $public_url;?>images/pin.png'
         });
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
      });
});
</script>  
</body>
</html>
