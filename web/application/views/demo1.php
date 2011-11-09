<!DOCTYPE>
<html lang="en">
<head>
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
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
          'train':          'http://localhost/CS130-Mashup/web/images/pin.png',  
          'train-selected': 'http://localhost/CS130-Mashup/web/images/pin.png'  
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
            icon:     'http://localhost/CS130-Mashup/web/images/pin.png'
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
    
    <style type="text/css" >
    .map {
      width: 100%;
      height:  100%;

      /* The following are required to allow absolute positioning of the
       * info window at the bottom right of the map, and for it to be hidden
       * when it is "off map" 
       */
      position: relative; 
      overflow: hidden;
    }
    #placeDetails {
      position: absolute;
      width: 300px;
      bottom: 0;
      right: -320px;
      padding-left: 10px;
      padding-right: 10px;

      /* Semi-transparent background */
    background-color: rgba(0,0,0,0.8);
    color:white;
    
 /*      color: white;
      font-size: 80%;

       Rounded top left corner 
      border-top-left-radius: 15px;
      -moz-border-radius-topleft: 15px;
      -webkit-border-top-left-radius: 15px;*/
    }

    /* Fit the text nicely inside the box */
    #event_title {
      font-family: sans-serif;
      color:white;
      margin-bottom: 0;
      font-weight: bold;
      font-size:16px;
      padding: 5px;
    }
    #event_desc {
        padding:5px;
        font-size: 14px;
    }
    #placeDetails p {
      margin-top: 0;
    }
    .popover {
        display: block;
    }
    </style>
    <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
      
      body {
/*        padding-top: 40px;  40px to make the container go all the way to the bottom of the topbar */
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
      
      .fill {
          opacity: 0.85;
          box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
      }

    </style>

	<meta charset="utf-8">
        <?php $display_city = isset($city) ? $city : "Los Angeles"?>
	<title>Events from <?php echo $display_city ?> presentation</title>

</head>
<body>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="http://localhost/CS130-Mashup/web/index.php/demo1">Entertainment+</a>

          <ul class="nav">
            <li class="active"><a href="http://localhost/CS130-Mashup/web/index.php/demo1">Home</a></li>
            <li><a href="http://localhost/CS130-Mashup/web/index.php/unit_test">Unit Test</a></li>
          </ul>
          <form class="pull-right" action="http://localhost/CS130-Mashup/web/index.php/demo1/" method="post">
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
