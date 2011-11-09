<div class="topbar">
   <div class="fill">
     <div class="container">
     <a class="brand" href="<?php echo base_url('/home') ;?>">Entertainment+</a>

       <ul class="nav">
         <li class="active"><a href="<?php echo base_url('/home') ;?>">Home</a></li>
         <li><a href="<?php echo base_url('/unit_test/runAll') ;?>">Unit Test</a></li>
       </ul>
       <form class="pull-right" action=<?php base_url('/home/search');?> method="post">
         <input name="city_search" placeholder="Search" type="text" />
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
