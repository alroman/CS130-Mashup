<script type="text/javascript" src="<?php echo $public_url; ?>js/oms.min.js"></script>
<script>  
$(document).ready(function(){

   // Set the default location
   var lon = <?php echo $geoloc['longitude']; ?>;
   var lat = <?php echo $geoloc['latitude']; ?>;

   var mapDefaultLocation = new google.maps.LatLng(lat, lon);
   var mapOptions = {
          zoom      : 12,
         center     : mapDefaultLocation,
         mapTypeId  : google.maps.MapTypeId.ROADMAP,
         panControl : false
   }

   //Create an global object that it can be used in app.js
   window.places = <?php echo $json_events; ?>;

   window.gm = google.maps;
   window.map = new google.maps.Map($("#map_canvas")[0], mapOptions);
   var spideroptions = {
      markerWontMove : true,
      markerWontHide : true,
      keepSpiderfied : true
   };
   window.oms = new OverlappingMarkerSpiderfier(map, spideroptions);
   window.public_url = '<?php echo $public_url;?>';

   window.icons = {
      'music':  public_url+'img/music.png',  
      'movies': public_url+'img/movies.png'  
   }

   window.currentPlace = null;
   window.info = $('#placeDetails');
   window.markerArray = [];
   window.app = {};

   app.updateMap = function() {
      // This returns a reference to elements in array
      var place = this;
      // Set the marker to the lon lat of a place location
      var loc = new gm.LatLng(place.latitude, place.longitude);
      var marker = new gm.Marker({
         position : loc,
            map   : map,
            title : place.title,
            icon  : icons[place.category]
      });

      markerArray.push(marker);
      // Do the drop animation for each element
      marker.setAnimation(gm.Animation.DROP);
      // For each element, we add the event listener...
      gm.event.addListener(marker, 'click', function() {
         var hidingMarker = currentPlace;
         var slideIn = function(marker) {  
            $('#event_title', info).text(place.title);
            $('#event_desc',  info).text(place.description);  
            info.animate({right: '0'});  
         }

         //place.category match the icons name
         if (currentPlace) {
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
      oms.addMarker(marker);
   }

   // This iterates through each element in the 'places' array
   $(places).each(app.updateMap);

   
});
</script>  
<script type="text/javascript" src="<?php echo $public_url; ?>js/app.js"></script>
</body>
</html>
