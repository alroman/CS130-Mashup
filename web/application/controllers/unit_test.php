<?php
class Unit_test extends CI_Controller{
   
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

}
