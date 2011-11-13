<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>  
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>  
    <script>  
	
    $(function() { // onload handler
      var mapDefaultLocation = new google.maps.LatLng(34.052234, -118.243685);
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
        
        <?php 
        // We're going to create the places array here
        echo "var places = [";
        $last = (object)array_pop($la_events);
        
        $nl = "\n";
        
        foreach($la_events as $e) {
            $event = (object)$e;
            
            echo '{' . $nl;
            echo '"title": "' . $event->title. '",' . $nl;
            echo '"description": "' . $event->venue . '",' . $nl;
            echo '"position": [' . $event->latitude .','. $event->longitude . ']' . $nl;
            echo '},' . $nl;
        }
        echo '{' . $nl;
        echo '"title": "' . $last->title. '",' . $nl;
        echo '"description": "' . $last->venue . '",' . $nl;
        echo '"position": [' . $last->latitude .','. $last->longitude . ']' . $nl;
        echo '}' .$nl ;
        echo "]" . $nl;
        
        ?>
      
        var icons = {
          'theatre':    '<?php echo base_url();?>images/theatre.png',  
          'concerts': 	'<?php echo base_url();?>images/concerts.png',
		  'movies':		'<?php echo base_url();?>images/movies.png',
		  'generic':	'<?php echo base_url();?>images/generic.png'
        }

        var currentPlace = null;
        var info = $('#placeDetails');
        
        // This iterates through each element in the 'places' array
        $(places).each(function() {
          // This returns a reference to elements in array
          var place = this;
          // Set the marker to the lon lat of a place location
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(place.position[0], place.position[1]),
            map:      map,
            title:    place.title,
            icon:     '<?php echo base_url();?>images/generic.png'
          });
          // Do the drop animation for each element
          marker.setAnimation(google.maps.Animation.DROP);
          
            // For each element, we add the event listener...
            google.maps.event.addListener(marker, 'click', function() {
              var hidingMarker = currentPlace;
              var slideIn = function(marker) {  
                $('h3', info).text(place.title);
                $('p',  info).text(place.description);  
                info.animate({right: '0'});  
              }
              
              marker.setIcon(icons['generic']);  
              if (currentPlace) {  
                currentPlace.setIcon(icons['generic']);  
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
      color: white;
      font-size: 80%;

      /* Rounded top left corner */
      border-top-left-radius: 15px;
      -moz-border-radius-topleft: 15px;
      -webkit-border-top-left-radius: 15px;
    }

    /* Fit the text nicely inside the box */
    h1 {
      font-family: sans-serif;
      margin-bottom: 0;
    }
    #placeDetails p {
      margin-top: 0;
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
          <a class="brand" href="#">Entertainment+</a>

          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">Unit Test</a></li>
            <li><a href="#contact">??</a></li>
          </ul>
          <form class="pull-right" action="http://www.studiolino.com/ibm/index.php/events_la/search" method="post">
            <input name="city" placeholder="Search" type="text">
            <?php echo form_submit('','Submit'); ?>
          </form>
        </div>
      </div>
    </div>

    <div class="map">
        <div id="map_canvas" style="width: 100%; height: 100%"></div>
        <div id='placeDetails'>
            <h3></h3>  
            <p></p>  
        </div>
    </div>
</body>
</html>