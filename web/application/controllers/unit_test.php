<?php
class Unit_test extends CI_Controller{
  
   public $eventful;
   public $location;
   public $util;

   public function __construct() {
      parent::__construct();
      $this->load->helper('url');
      $this->load->library('unittestwrapper');
   }

   public function index() {
       //$test_suite = new UnitTestWrapper();
       
       $tests = array();
       $this->unittestwrapper->test_getEvents();
       $this->unittestwrapper->test_helper_titlelizer();
       
       $tests[] = $this->unittestwrapper->test_helper_geolocate();
       
       $data = array('units' => $tests);
       
       $this->load->view('unit_test', $data);
   }   

   public function runAll() {
      $this->test_event_location_validation();
      $this->test_event_json();
      $this->test_event_json();
      $this->test_filter_by_date();
      $this->test_filter_by_category();
      $this->test_get_location();
   }

   public function test_getEvents() {
      $this->unit->run($this->eventful, 'is_object', 'Object Test');
      $default_events = $this->eventful->getEvents();
      $this->unit->run($default_events, 'is_array', 'Array Test');
      return $this->unit->report();
   }

   public function test_event_location_validation() {
      //Check valid events
      $valid_option1 = array('location' => 'New York');
      $valid_evnt1 = $this->eventful->getEvents($valid_option1);
      if (sizeof($valid_evnt1) > 0) {
         $this->unit->run($valid_evnt1[0], 'is_array', 'Array Test 2');
         $this->unit->run($valid_evnt1[0]['title'], 'is_string', 'String Test 2');
      }

      //Check invalid location events
      $option1 = array('location' => 'not-valid');
      $events1 = $this->eventful->getEvents($option1);

      if (sizeof($events1) > 0) {
         $this->unit->run($events1[0], 'is_string', 'Array Test 3');
         $this->unit->run(($events1[0] === 'Location Name is invalid'), 'is_true', 'Validate Message');
      }

      echo $this->unit->report();
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
      // echo $json;
   }
   
   public function test_getLocation() {
       $loc = $this->location->getLocation();
       return $loc;
   }
   
   //Exact ranges can be specified the form 'YYYYMMDD00-YYYYMMDD00', for example '2012042500-2012042700';
   public function test_filter_by_date() {
      $option1 = array('date' => "2011110700-2011110700");
      $option2 = array('date' => "2011110700-2011110900");
      $option3 = array('date' => "2011111800-2011113000");
      $option4 = array('date' => "3432-234 234-23-4-234-2");

      $events1 = $this->eventful->getEvents($option1);
      $events2 = $this->eventful->getEvents($option2);
      $events3 = $this->eventful->getEvents($option3);
      $events4 = $this->eventful->getEvents($option4);
      
      if (sizeof($events1) > 0) {
         $this->unit->run($events1[0], 'is_array', 'Date Array Test 1');
         $this->unit->run($events1[0]['title'], 'is_string', 'Date String Test 1');
      }
      if (sizeof($events2) > 0) {
         $this->unit->run($events2[0], 'is_array', 'Date Array Test 2');
         $this->unit->run($events2[0]['title'], 'is_string', 'Date String Test 2');
      }
      if (sizeof($events3) > 0) {
         $this->unit->run($events3[0], 'is_array', 'Date Array Test 3');
         $this->unit->run($events1[0]['title'], 'is_string', 'Date String Test 3');
      }
      if (sizeof($events4) > 0) {
         // print_r($events4);
         $this->unit->run($events4[0], 'is_string', 'Date Array Test 4');
         $this->unit->run(($events4[0] === 'Incorrect Date format'), 'is_true', 'Date Validate Message');
      }
      
      echo $this->unit->report();
   }
   
   //Categories only have music or movies_film
   public function test_filter_by_category() {
      $category = array('music');
      $options = array('categories' => $category);
      $events = $this->eventful->getEvents($options);

      if (sizeof($events)) {
         $this->unit->run($events[0], 'is_array', 'Category Array Test 1');
         $this->unit->run($events[0]['title'], 'is_string', 'Category String Test 1');
      }

      //Invalid data
      $invalid_category = array('book');
      $invalid_options = array('categories' => $invalid_category);
      $invalid_events = $this->eventful->getEvents($invalid_options);

      if (sizeof($invalid_events) > 0) {
         $this->unit->run($invalid_events[0], 'is_string', 'Category Array Test');
         $this->unit->run(($invalid_events[0] === 'Invalid Categories'), 'is_true', 'Category Validate Message');
      }
      echo $this->unit->report();
   }

   //Test get location in Util
   public function test_get_location()
   {
      $this->load->library('util');
      $location = $this->util->getLocation();
      $special_string = '/(\=|\+|\-|\(|\))/';

      $this->unit->run((!empty($location['zipCode']) && !empty($location['longitude']) && !empty($location['latitude'])), 'is_true', 'Test the object of location, there should have 3 children');
      $this->unit->run((preg_match($special_string, $location['zipCode']) == 0), 'is_true', 'Test ZipCode Special String');
      echo $this->unit->report();
   }

   //Test getPublicUrl in Util
   public function test_getPublicUrl()
   {
      $this->load->library('util');
      $url1 = $this->util->getPublicUrl();

      $this->unit->run((strrpos($url1, 'web') > 0), 'is_true', 'Util getPublicUrl Test 1');

      $this->unit->run((array_pop(preg_split('/web\//', $url1)) == ''), 'is_true', 'Util getPublicUrl Test 2');
      
      echo $this->unit->report();
   }

   public function test_getCategories()
   {
      $fail1 = $this->eventful->getCategories();
      $this->unit->run($fail1, 'is_string', 'Get Categories Fail Test');
      $this->unit->run(($fail1 == "Error: you have to call getEvents first."), 'is_true', 'Error Message Test For getCategories');
   
      $succ1 = $this->eventful->getEvents();
      $succ1 = $this->eventful->getCategories();
      $this->unit->run($succ1, 'is_array', 'Get Categories Successful Test');

      echo $this->unit->report();
   }
   
   //Test Util assignKeyWordsToEvents
   public function test_assignKeyWordsToEvents() {
      $this->load->library('util');
      //Get the events
      $events = $this->eventful->getEvents();
      $assigned_events = $this->util->assignKeyWordsToEvents($events);

      print_r($assigned_events);
   }
}
