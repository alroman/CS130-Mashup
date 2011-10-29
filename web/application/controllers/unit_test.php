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
       $test2 = $this->test_getDefaultEvents();
       $data = array("test1" => $test1, 'test2' => $test2); 
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

   public function test_event_json() {
      $options = array('type' => 'calendar');
      $default_events = $this->eventful->getEvents($options);
      $json = json_encode($default_events);
      $v1 = $this->unit->run(json_decode($json), 'is_array', 'Json validation test');
      echo $this->unit->report();
   }

}
