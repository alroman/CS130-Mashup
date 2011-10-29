<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
    <script  
    src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js">  
    </script>  
    <script  
    type="text/javascript"  
    src="http://maps.google.com/maps/api/js?sensor=false">  
    </script>  
    <script>  
    // New code goes here  
    $(function() { // onload handler
      var losAngeles = new google.maps.LatLng(34.052234, -118.243685);
      var mapOptions = {
        zoom:      12,
        center:    losAngeles,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }

      var map = new google.maps.Map($("#map_canvas")[0], mapOptions);
      
      var marker = new google.maps.Marker({  
          position: new google.maps.LatLng(-37.818078, 144.966811),  
          map:      map,  
          title:    'Flinders St Station'  
        });  

//      var places =
//        [
//          {
//            "title": "Flinders St Station",
//            "description": "This is a pretty major train station.",
//            "position": [
//              -37.818078,
//              144.966811
//            ]
//          },
//          {
//            "title": "Southern Cross Station",
//            "description": "Did you know it used to be called Spencer St Station?",
//            "position": [
//              -37.818358,
//              144.952417
//            ]
//          }
//        ]

//      var currentPlace = null;
//      var info = $('#placeDetails');
//      var icons = {
//        'train':          'http://blogs.sitepoint.com/wp-content/uploads/2011/04/train.png',
//        'train-selected': 'http://blogs.sitepoint.com/wp-content/uploads/2011/04/train-selected.png'
//      }


//      $.getJSON('places.json', function(places) {
//        $(places).each(function() {
//          var place = this;
//          var marker = new google.maps.Marker({
//            position: new google.maps.LatLng(place.position[0], place.position[1]),
//            map:      map,
//            title:    place.title,
//            icon:     icons['train']
//          });
//
//          google.maps.event.addListener(marker, 'click', function() {
//            var hidingMarker = currentPlace;
//            var slideIn = function(marker) {
//              $('h1', info).text(place.title);
//              $('p',  info).text(place.description);
//
//              info.animate({right: '0'});
//            }
//
//            marker.setIcon(icons['train-selected']);
//
//            if (currentPlace) {
//              currentPlace.setIcon(icons['train']);
//
//              info.animate(
//                { right: '-320px' },
//                { complete: function() {
//                  if (hidingMarker != marker) {
//                    slideIn(marker);
//                  } else {
//                    currentPlace = null;
//                  }
//                }}
//              );
//            } else {
//              slideIn(marker);
//            }
//            currentPlace = marker;
//          });
//        });
//      });
    });
    </script>  
    
    <style type="text/css" >
    .map { 
      width: 100%;
      height: 100%;

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
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
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

    <div class="container">

      <div class="content">
        <div class="page-header">
          <h1>E+ <small>Here is what's happening in <?php echo $display_city ?></small></h1>
        </div>
        <div class="row">
          <div class="span10">
            <h2>Events:</h2>
              
            <div class='map'>
                <div id='map_canvas' style='height:500px; width: 700px'></div>
                <div id='placeDetails'>
                  <h1></h1>
                  <p></p>
                </div>
            </div>

            
          </div>
<!--          <div class="span4">
            <h3>Secondary content</h3>
            nothing here yet...
          </div>-->
        </div>
      </div>

      <footer>
        <p>&copy; E+ 2011</p>

      </footer>

    </div> <!-- /container -->
    
</body>
</html>