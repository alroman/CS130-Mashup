<?php

class Map_test extends CI_Controller {
    
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->load->library('location');
        $this->eventful = new Eventful();
        $this->location = new Location();
    }
    
    public function index() {
        $la_events = $this->eventful->getEvents(array('location' => 'Los Angeles'));
        $data = array('la_events' => $la_events,);
        
        $this->load->view('map_test', $data);
    }

}