<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Demo_1 extends CI_Controller {
    
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
        // We need to do some light form processing
        $this->load->helper('form');
        
        // Load our internal libraries
        $this->load->library('eventful');
        $this->load->library('location');
        $this->eventful = new Eventful();
        $this->location = new Location();
    }
    
    public function index() {
        $city = null;
        $geoloc = null;
        
        // Check if we have search input
        if(!isset($this->input->post('city_search'))) {
            $city = $this->location->getCity();
            $geoloc = $this->location->getGeo();
        } else {
            $city = $this->input->post('city_search');
        }
        
        // If city could not be guessed, then we default into Los Angeles
        if($city == null) {
            $city = "Los Angeles";
            $geoloc['longitude'] = "34.052234";
            $geoloc['latitude'] = "-118.243685";
        }
        
        // Get events and save the data
        $all_events = $this->eventful->getEvents(array('location' => $city));
        $data = array('all_events' => $all_events, 'geoloc' => $geoloc);
        
        // Load the view with data
        $this->load->view('events_la', $data);
    }
    
}