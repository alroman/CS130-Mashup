<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
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
          <form class="pull-right" action="http://localhost/CS130-Mashup/web/index.php/events_la/search" method="post">
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
                    
            <?php
                
                foreach($la_events as $e) {
                    $event = (object)$e;
                    $title = array_shift($event->title);
                    echo "<table><thead><tr><th>Title</th><th>$title</th></tr></thead><tbody>";

                    // start time
                    echo "<tr><th>Starts</th><th>";
                    $start = array_shift($event->start_time);
                    echo date("F j, Y, g:i a", strtotime($start));
                    echo "</th></tr>";

                    // end time
                    echo "<tr><th>Ends</th><th>";
                    $stop = array_shift($event->stop_time);
                    echo date("F j, Y, g:i a", strtotime($stop));
                    echo "</th></tr>";
                    
                    // venue
                    echo "<tr><th>Venue</th><th>";
                    $venue = array_shift($event->venue);
                    echo $venue;
                    echo "</th></tr>";
                    
                    // Description
                    echo "<tr><th>Description</th><th>";
                    $desc = array_shift($event->description);
                    echo nl2br($desc);
                    echo "</th></tr>";
                    echo "</tbody></table>";
                }
            
            ?>

          </div>
          <div class="span4">
            <h3>Secondary content</h3>
            nothing here yet...
          </div>
        </div>
      </div>

      <footer>
        <p>&copy; E+ 2011</p>

      </footer>

    </div> <!-- /container -->
    
</body>
</html>