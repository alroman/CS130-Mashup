<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <link rel="stylesheet" href="<?php echo base_url() ?>css/bootstrap.min.css"></link>
    <link rel="stylesheet" href="<?php echo base_url() ?>css/styles.css"></link>
    <script src="<?php echo base_url() ?>js/jquery-1.6.2.min.js" ></script>
    <script src="<?php echo base_url() ?>js/bootstrap-twipsy.js" ></script>
    <script src="<?php echo base_url() ?>js/bootstrap-popover.js" ></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <meta charset="utf-8">
    <script>
    // Maps JS
    $(function() { // onload handler

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

        var map = new google.maps.Map($("#map_canvas")[0], mapOptions);

        var places = <?php echo $all_events; ?>;

        var icons = {
            'train':          '<?php echo base_url() ?>img/pin.png',  
            'train-selected': '<?php echo base_url() ?>img/pin.png'  
        }
        
        // overlay
        var overlay = new google.maps.OverlayView();
        overlay.draw = function() {};
        overlay.setMap(map);

        var currentPlace = null;
        var info = $('#placeDetails');
        var details = $('#fullDetails');

        // This iterates through each element in the 'places' array
        $(places).each(function() {
            // This returns a reference to elements in array
            var place = this;
            // Set the marker to the lon lat of a place location
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(place.latitude, place.longitude),
                map:      map,
                title:    place.title,
                icon:     '<?php echo base_url() ?>img/pin.png'
            });
            
            // Do the drop animation for each element
            marker.setAnimation(google.maps.Animation.DROP);

            google.maps.event.addListener(marker, 'mouseover', function() {
                var projection = overlay.getProjection(); 
                var pixel = projection.fromLatLngToContainerPixel(marker.getPosition());
            });
            
            // For each element, we add the event listener...
            google.maps.event.addListener(marker, 'click', function() {
                var hidingMarker = currentPlace;
                var slideIn = function(marker) {  
                    $('#event_title', info).text(place.title);
                    $('#event_desc',  info).text(place.description);  
                    info.animate({right: '0'});  
                }

                $('#event_title', info).text(place.title);
                $('#event_desc',  info).text(place.description);
                $('#event_venue',  info).text("@"+place.venue_name);
                
                // Offset so that we can display the arrow right below the marker
                info.animate({left: parseInt(pixel.x - 146) + "px", top: parseInt(pixel.y) + "px", visibility: "visible"}, 200);
                info.fadeIn(50);
            });
            
            google.maps.event.addListener(marker, 'mouseout', function() {
                info.fadeOut(50);
            });
            
            google.maps.event.addListener(marker, 'click', function() {
                $('#desc_title', details).text(place.title);
                $('#desc_venue', details).text(place.venue_name);
                $('#desc_desc', details).html(place.description_long);
            });

        });

    });
    </script>
    
    <title><?php echo $page_title ?></title>

</head>
<body>
