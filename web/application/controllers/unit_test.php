<?php
class Unit_test extends CI_Controller{
   
   public $eventful;
   public $location;

   public function __construct($key="xzbXfQPZsjPVL2qw") {
      parent::__construct();
      $this->load->library('unit_test');
      $this->load->library('eventful');
      $this->load->library('location');
      $this->eventful = new Eventful();
      $this->location = new location("55.97.245.81");
   }

   public function index() {
       $test1 = $this->test_getEvents();
       $test2 = $this->test_getDefaultEvents();
       $test3 = $this->test_getLocation();
       $data = array("test1" => $test1, 'test2' => $test2, 'test3' => $test3); 
       $this->load->view('unit_test', $data);
   }   

   public function test_getEvents() {
      $this->unit->run($this->eventful, 'is_object', 'Object Test');
      $default_events = $this->eventful->getEvents();
      $this->unit->run($default_events, 'is_array', 'Array Test');
      return $this->unit->report();
   }
   
   public function test_getDefaultEvents() {
       $default_events = $this->eventful->getEvents();
       return $default_events;
   }
   
   public function test_getLocation() {
       $loc = $this->location->getLocation();
       return $loc;
   }   
}
