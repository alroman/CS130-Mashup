<?php
class Unit_test extends CI_Controller{
   
   public $eventful;

   public function __construct($key="xzbXfQPZsjPVL2qw") {
      parent::__construct();
      $this->load->library('unit_test');
      $this->load->library('eventful');
      $this->eventful = new Eventful();
   }

   public function index() {
       $test1 = $this->test_getEvents();
       $data = array("test1" => $test1);
       
       $this->load->view('unit_test', $data);
   }   

   public function test_getEvents() {
      $this->unit->run($eventful, 'is_object');
      $default_events = $this->eventful->getEvents();
      $this->unit->run($default_events, 'is_array');
      return $this->unit->report();
   }
}
