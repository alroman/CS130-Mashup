<!DOCTYPE>
<html lang="en">
<head>
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <link rel="stylesheet" href="<?php echo base_url() ?>bootstrap.min.css"></link>
    <link rel="stylesheet" href="<?php echo base_url() ?>css/styles.css"></link>
    <script src="<?php echo base_url() ?>js/jquery-1.6.2.min.js" ></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script>  
    
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
      
//      var marker = new google.maps.Marker({  
//          position: new google.maps.LatLng(34.07196, -118.254261),  
//          map:      map,  
//          title:    'Some random place',
//          icon:     'http://google-maps-icons.googlecode.com/files/train.png'  
//        });
//        
//        // We'll need to create markers for each event.. 
//        marker.setAnimation(google.maps.Animation.DROP); // Also try DROP  
                
        var places = <?php echo $all_events; ?>;
        
        var icons = {
          'train':          '<?php echo base_url() ?>img/pin.png',  
          'train-selected': '<?php echo base_url() ?>img/pin.png'  
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
            //icon:     'http://localhost/CS130-Mashup/web/img/pin.png'
            icon:     '<?php echo base_url() ?>img/pin.png'
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

    <meta charset="utf-8">
    <?php $display_city = isset($city) ? $city : "Los Angeles"?>
    <title>Events from <?php echo $display_city ?> presentation</title>

</head>
<body>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="<?php echo base_url() ?>index.php/demo1">Entertainment+</a>

          <ul class="nav">
            <li class="active"><a href="<?php echo base_url() ?>index.php/demo1">Home</a></li>
            <li><a href="<?php echo base_url() ?>index.php/unit_test">Unit Test</a></li>
          </ul>
          <form class="pull-right" action="<?php echo base_url() ?>index.php/demo1/" method="post">
            <input name="city_search" placeholder="Search" type="text">
            <?php echo form_submit('','Submit'); ?>
          </form>
        </div>
      </div>
    </div>

    <div class="map">
        <div id="map_canvas" style="width: 100%; height: 100%"></div>
        <div id='placeDetails'>
            <div id="event_title"></div>
            <div id="event_desc"></div>  
        </div>
    </div>
    
</body>
</html>
