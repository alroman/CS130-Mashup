
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