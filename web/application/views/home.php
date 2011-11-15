<div class="topbar">
   <div class="fill">
     <div class="container">
       <a class="brand" href="<?php echo base_url('/home') ;?>">Entertainment+</a>

       <ul class="nav">
         <li class="active"><a href="<?php echo base_url('/home') ;?>">Home</a></li>
         <li><a href="<?php echo base_url('/unit_test/runAll') ;?>">Unit Test</a></li>
       </ul>
       <form class="pull-right" action='<?php echo base_url('home/search');?>' method="post">
         <input name="city_search" placeholder="Search" type="text" />
         <?php echo form_submit('','Submit'); ?>
       </form>
     </div>
   </div>
   <div class='fill sublist'>
      <div class='container'>
       <form action='<?php echo base_url('home/filter');?>' id='tag_form'>
       <ul class="tags">
       <?php foreach($categories as $cat):?>
       <li>
            <div class="input-prepend">
               <label class="add-on tag active">
                  <input type="checkbox" name="cat[]" id='<?php echo $cat;?>' value="<?php echo $cat;?>" disabled='true', class='tag_checkbox' checked='true'>
                  <span class='tags_text'><?php echo $cat;?></span>
               </label>
            </div>
       </li>
       <?php endforeach; ?>
       </ul>
       <input type='hidden' name='location' value='<?php echo $location; ?>' id='location'>
       </form>
       <ul class="nav">
         <li class="active"><a href="<?php echo base_url('/home') ;?>">Map View</a></li>
         <li><a href="<?php echo base_url('listview') ;?>">List View</a></li>
       </ul>
      </div>
   </div>
</div>

<div class="row">
   <div class="span4 event_list fill">
      <?php foreach ($events as $event) { ?>
      <div class='event_title'><?php echo $event['title']; ?></div>
      <?php } ?>
   </div>
</div>


<div class="row">
    <div class="span4 event_cal" id="eventCalendar" style="margin-top: 10px;">
      <div id="dest"></div>
      <div id="calendar"></div>
    </div>
</div>

<div class="map">
  <div id="map_canvas" style="width: 100%; height: 100%"></div>
  <div id='placeDetails'>
      <div id="event_title"></div>
      <div id="event_desc"></div>  
  </div>
</div>
