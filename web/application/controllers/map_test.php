<?php

class Map_test extends CI_Controller {
    
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
		$this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->load->library('location');
        $this->eventful = new Eventful();
        $this->location = new Location();
    }
    
    public function index() {
        $la_events = $this->eventful->getEvents(array('location' => 'Los Angeles'));
//        echo "<pre>";
//        var_dump($la_events);
//        
//        echo "</pre>";
        
        foreach($la_events as $events) {
            if(!isset($events['venue']))
                echo "ERROR";
        }
        $data = array('la_events' => $la_events,);
        
        $this->load->view('map_test', $data);
    }

}