<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Demo1 extends CI_Controller {
    
    // These members will be used to store the library instances
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
        // We need to do some light form processing
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('curl');
        
        // Load our internal libraries
        $this->load->library('eventful');
        $this->load->library('location');
        $this->load->library('helper');
        // Load the internal classes
        // key: 7XNLVVN3XTGFQtGL
        $this->eventful = new Eventful();
        $this->location = new Location();
    }
    
    public function index() {
        $city = null;
        $geoloc = null;
        
        // Check if we have search input
        $input_city = $this->input->post('city_search');
        
        if(empty($input_city)) {
            $city = $this->location->getCity();
            $geoloc = $this->location->getGeo();
        } else {
            //var_dump($input_city);
            $city = $input_city;
            //$lonlat = $this->__getCityGeo($input_city);
            $lonlat = Helper::geolocate($input_city);
            
            $geoloc['longitude'] = $lonlat['lon'];
            $geoloc['latitude'] = $lonlat['lat'];
        }
        
        // If city could not be guessed, then we default into Los Angeles
        if(empty($geoloc['longitude']) && empty($geoloc['latitude'])) {
            $city = "Los Angeles";
            $geoloc['latitude'] = "34.0522342";
            $geoloc['longitude'] = "-118.2436849";
        }

        // Get events and save the data
        $all_events_x = $this->eventful->getEvents(array('location' => $city));
        
        //$all_events = $this->event_cal_filter($all_events_x);
        $all_events = Helper::JSONize($all_events_x);
        // encode
        
        
        $data = array('all_events' => $all_events, 'geoloc' => $geoloc);

        //var_dump($all_events);
        //$this->__getCityGeo("Los Angeles");
        // Load the view with data
        $this->load->view('demo1', $data);
    }
    
   
}