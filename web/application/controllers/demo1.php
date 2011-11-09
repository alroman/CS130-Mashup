<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Demo1 extends CI_Controller {
    
    // These members will be used to store the library instances
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
        // We need to do some light form processing
        $this->load->helper('form');
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
            $lonlat = Helper.geolocate($input_city);
            
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
        
        $all_events = $this->event_cal_filter($all_events_x);
        // encode
        
        
        $data = array('all_events' => $all_events, 'geoloc' => $geoloc);

        //var_dump($all_events);
        //$this->__getCityGeo("Los Angeles");
        // Load the view with data
        $this->load->view('demo1', $data);
    }
    
    private function __getCityGeo($city) {
        
        //http://maps.googleapis.com/maps/api/geocode/json?address=Los+Angeles&sensor=false
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($city) . '&sensor=false');
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        $data = curl_exec($ch);
//        curl_close($ch); 

        $city_url = urlencode($city);
        $data = $this->curl->simple_get("http://maps.googleapis.com/maps/api/geocode/json?address=$city_url&sensor=false");
        //var_dump($data);
        $json_results = json_decode($data);
//        echo "<pre>";
//        var_dump($json_results->results[0]);
//        echo "</pre>";
        
        if(isset($json_results->results[0]->geometry->location)) {
            $loc = $json_results->results[0]->geometry->location;
            return array('lon' => $loc->lng, 'lat' => $loc->lat);
        }
    }
    
    public function event_cal_filter($events) {
        $event_calendar_fields = array('title', 'description', 'longitude', 'latitude','venue_name');
        $filtered_events = array();
        foreach ($events as $key => $value) {
            $tmp = array();
            foreach ($event_calendar_fields as $v) {
                $tmp[$v] = utf8_encode(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', $value[$v]));
            }
            $filtered_events []= $tmp;
        }
        return json_encode($filtered_events);
   }
   
}