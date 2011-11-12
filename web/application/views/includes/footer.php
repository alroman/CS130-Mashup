<script>  
$(document).ready(function(){

      // Set the default location
      var lon = <?php echo $geoloc['longitude']; ?>;
      var lat = <?php echo $geoloc['latitude']; ?>;

      var mapDefaultLocation = new google.maps.LatLng(lat, lon);
      var mapOptions = {
         zoom:      12,
         center:    mapDefaultLocation,
         mapTypeId: google.maps.MapTypeId.ROADMAP,
         panControl: false
      }
      var places = <?php echo $json_events; ?>;

      window.map = new google.maps.Map($("#map_canvas")[0], mapOptions);
      window.public_url = '<?php echo $public_url;?>';

      window.icons = {
         'train':          public_url+'images/pin.png',  
         'train-selected': public_url+'images/pin.png'  
      }

      window.currentPlace = null;
      window.info = $('#placeDetails');
      window.markerArray = [];

      // This iterates through each element in the 'places' array
      $(places).each(updateMap);

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
</script>  
   <script type="text/javascript" src="<?php echo $public_url; ?>js/app.js"></script>
</body>
</html>
