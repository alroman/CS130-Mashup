<div class="topbar">
   <div class="fill greenfill">
     <div class="container">
       <a class="brand" href="<?php echo base_url('/home') ;?>">Entertainment+</a>

       <form class="pull-right" action='<?php echo base_url('/home');?>' method="post">
         <input name="city_search" placeholder="Search by City or Zipcode" type="text" />
         <?php echo form_submit('','Submit'); ?>
       </form>
     </div>
   </div>
   <div class='well sublist'>
      <div class='container'>
       <form action='<?php echo base_url('home/filter');?>' id='tag_form'>
       <ul class="tags">
       <?php foreach($categories as $cat):?>
       <li>
            <div class="input-prepend">
               <label class="add-on tag active category">
                  <input type="checkbox" id='<?php echo $cat;?>' value="<?php echo $cat;?>" class='tag_checkbox' checked='true' style='display:none;'/>
                  <span class='tags_text'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cat;?></span>
               </label>
            </div>
       </li>
       <?php endforeach; ?>
       <?php foreach($keywords as $keyword):?>
       <li>
            <div class="input-prepend">
               <label class="add-on tag active">
                  <input type="checkbox" id='<?php echo $keyword;?>' value="<?php echo $keyword;?>" class='tag_checkbox' checked='true' style='display:none;'/>
                  <span class='tags_text'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $keyword;?></span>
               </label>
            </div>
       </li>
       <?php endforeach;?>
       <li id='tag-wrapper'>
            <div class='input-prepend'>
               <label class='tag-filter' id='addTags'>
                  <span class='add'><strong>+</strong> add tag</span>
               </label>
            </div>
       </li>
       </ul>
       <input type='hidden' name='location' value='<?php echo $location; ?>' id='location'>
       </form>
          <div class="navbuttons" >
          <button onclick="showMap()" class="btn primary square-button" rel="popover" data-original-title="View events on map" data-content="You can view events on the map along with their heat rank!" >map view</button>
          <button onclick="showList()" class="btn info square-button" rel="popover"  data-original-title="View events in a list" data-content="View the events in a list sorted by their heat rank!" >list view</button>
          </div>
      </div>
   </div>
</div>

<div class="row">
    <div class="wrap">
        <div class="span4 event_cal" id="eventCalendar">
          <div id="dest"></div>
          <div id="calendar"></div>
        </div>
    </div>
</div>

<div id="all_events_map" class="map">
  <div id="map_canvas" style="width: 100%; height: 100%"></div>
  <div id="placeDetails" class="eplustooltip below">
        <div class="arrow"></div>
        <div class="inner">
          <h4 id="event_title" class="title"></h4>
          <div class="content">
              <h5 id="event_venue"></h5>
              <p id="event_desc"></p>
          </div>
        </div>
    </div>
      
    <div id="fullDetails" class="eplusdesc" >
        <div class="inner">
          <h4 id="desc_title" class="title">E+ events</h4>
          <div class="content">
              <div id="desc_venue" class="well e-desc-well">
              Welcome!  You can hover over events to view a brief description, or click on an event 
              to view a full description.
              <br/>
              The events listed below matched the keywords you specified.
              </div>
              <div id="desc_desc">
                  <div style="max-height: 400px; overflow: auto">
                  <table class="zebra-striped">
                      <tbody>
                          <?php foreach ($events as $event) { ?>
                          <tr>
                              <td>
                                <?php echo $event['title']; ?>

                                <span class='label notice'><?php echo $event['category']; ?></span>
                                <?php foreach ($event['keywords'] as $keyword) {?>
                                    <span class='label important'><?php echo $keyword; ?></span>
                                <?php } ?>
                              </td>
                          </tr>
                          <?php } ?>
                      </tbody>
                  </table>
                  </div>
                  
                  
              </div>
          </div>
        </div>
    </div>

</div>
<div id="all_events_list" class="container event-list-display" style="display:none">
    <?php
    function heat_desc($heat) {
        switch($heat) {
            case "hot":
                return "We think you will like this event!";
            case "warm":
                return "We think you will maybe like this event.. so so, somewhat.  Let us know!";
            case "neutral":
                return "This is a neutral event.";
            case "cool":
                return "This is a cool event, but probably not the kind of cool you want";
            case "ice":
                return "This event is so cold, it needs you to warm it up!";
            default:
                return "sample text";
        }
    }
    foreach ($events as $e) {
        $event = (object) $e;
        $image = $event->image;
        
//        echo "<pre>";
//        var_dump($event);
//        echo "</pre>";
        

        echo "<div class='well e-well'>";
        echo "<ul class='media-grid floatRight'>";

        if ($image) {
            echo "
             <li>
                   <a href='#'><img class='thumbnail' src='" . $image['medium'][0]['url'][0] . "' alt=''></a>
             </li>";
        }
        echo "</ul>";
         
        echo "<div class='e-title-wrapper'>";
        echo "  <a data-content='". heat_desc($event->heat_rank) ."' rel='popover' href='#' data-original-title='This event is $event->heat_rank!'>";
        echo "      <img src='" . $public_url . "img/map_" . $event->heat_rank . ".png' style='float:left'/>";
        echo "  </a>";
        echo "  <h4 class='blue' style='padding: 0 0 20px 60px'>$event->title</h4></div>";
        
        if($image) {
            echo "<div class='e-trim'>";
        } else {
            echo "<div>";
        }
        
        echo "<div class='well e-band'>";
        echo "  <strong>Don't miss it: </strong>" . date("l, M j", strtotime($event->start_time)) . " @ " . date("g:i A", strtotime($event->start_time));
        echo "  <br/><strong>Go to: </strong>" . $event->venue_name;
        echo "  <br/>$event->venue_address";
        echo "  <br/>$event->city_name";
        echo "  <br/><strong>File under: </strong><span class='label notice'>$event->category</span>
              
              </div>";
        echo "<p><strong>Info: </strong>$event->description</p>";
        echo "<button class='btn primary' onclick='mapIt($event->longitude, $event->latitude)'>Map it!</button>
              </div>
            </div>";
    }
    ?>                  
</div>
