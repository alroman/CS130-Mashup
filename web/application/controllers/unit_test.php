<?php
class Unit_test extends CI_Controller{
   
   public $eventful;
   public $location;

   public function __construct() {
      parent::__construct();
      $this->load->helper('url');
      $this->load->library('unittestwrapper');
   }

   public function index() {
       $test_suite = new UnitTestWrapper();
       
       $tests = array();
       $tests[] = $test_suite->test_getEvents();
       $tests[] = $test_suite->test_helper_titlelizer();
       $tests[] = $test_suite->test_helper_geolocate();
       
       $data = array('units' => $tests);
       
       $this->load->view('unit_test', $data);
   }   

}
