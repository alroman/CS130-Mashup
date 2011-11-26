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
        window.all_events = places;

        window.gm = google.maps;
        window.map = new google.maps.Map($("#map_canvas")[0], mapOptions);

        //Create spider object
        var spideroptions = {
            markerWontMove : true,
            markerWontHide : true,
            keepSpiderfied : true
        };
        window.oms = new OverlappingMarkerSpiderfier(map, spideroptions);

        //Make url assessible to all other files
        window.public_url = '<?php echo $public_url; ?>';

        window.icons = {
            'music':  public_url+'img/map.png',  
            'movies': public_url+'img/map.png',
            'hot': public_url+'img/map_hot.png',
            'ice': public_url+'img/map_ice.png',
            'warm': public_url+'img/map_warm.png',
            'neutral': public_url+'img/map_neutral.png',
            'cool': public_url+'img/map_cool.png'
        }

        window.currentPlace = null;
        window.info = $('#placeDetails');
        window.details = $('#fullDetails');
        window.markerArray = [];
        window.app = {};

        // OverlayView is used to retrieve the x,y pixel coordinates of a 
        // marker on the map.
        var overlay = new gm.OverlayView();
        overlay.draw = function() {};
        overlay.setMap(map);
    
        //This app object is global object, so it will be accessible from app.js
        app.updateMap = function() {
            // This returns a reference to elements in array
            var place = this;
            // Set the marker to the lon lat of a place location
            var loc = new gm.LatLng(place.latitude, place.longitude);
            var marker = new gm.Marker({
                position : loc,
                map   : map,
                icon  : icons[place.heat_rank]
            });

            markerArray.push(marker);
            // Do the drop animation for each element
            marker.setAnimation(gm.Animation.DROP);
      
            gm.event.addListener(marker, 'mouseover', function() {
                var projection = overlay.getProjection(); 
                var pixel = projection.fromLatLngToContainerPixel(marker.getPosition());

                $('#event_title', info).html("<img src='" + public_url + "img/heat_" + place.heat_rank + ".png'/>" + place.title);
                $('#event_desc',  info).html(place.description);
                $('#event_venue',  info).text("@"+place.venue_name);

                // Offset so that we can display the arrow right below the marker
                info.animate({left: parseInt(pixel.x - 146) + "px", top: parseInt(pixel.y) + "px", visibility: "visible"}, 200);
                info.fadeIn(50);
            });
        
            gm.event.addListener(marker, 'mouseout', function() {
                info.fadeOut(0);
            });

            // For each element, we add the event listener...
            gm.event.addListener(marker, 'click', function() {
                $('#desc_title', details).text(place.title);
                $('#desc_venue', details).html("<strong>" + place.venue_name + "</strong><br/>" + place.venue_address + "<br/>" + place.city_name);
                $('#desc_desc', details).html(place.description_long);
            }); 
            oms.addMarker(marker);
        }

        // This iterates through each element in the 'places' array
        $(places).each(app.updateMap);

   
    });

   $(function () {
       $("a[rel=popover]")
       .popover({
           offset: 45,
           placement: 'below'
       })
       .click(function(e) {
           e.preventDefault()
       })
   })

   $(function () {
       $("button[rel=popover]")
       .popover({
           offset: 10,
           placement: 'below'
       })
       .click(function(e) {
           e.preventDefault()
       })
   })

    function showMap() {
        $('#all_events_map').css('display', 'block');
        $('#all_events_list').css('display', 'none');
    }

    function showList() {
        $('#all_events_map').css('display', 'none');
        $('#all_events_list').css('display', 'block');
    }
    
    function mapIt(lon, lat) {
        showMap();
        var darwin = new google.maps.LatLng(lat, lon);
        $(window.map.setCenter(darwin));

    }

</script>  
<script type="text/javascript" src="<?php echo $public_url; ?>js/app.js"></script>
</body>
</html>
