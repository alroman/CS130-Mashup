<?php
class Eventful_unit_test extends CI_Controller{
   
   $eventful;

   public function __construct($key="xzbXfQPZsjPVL2qw") {
      parent::__construct();
      $this->load->library('unit_test');
      $this->eventful = new Eventful();
   }

   public function test_getEvents() {
      $CI->unit->run($eventful, 'is_object');
      $default_events = $eventful->getEvents();
      $CI->unit->run($default_events, 'is_array');
      echo $CI->unit->report();
   }
}
