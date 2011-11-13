<?php

class UnitTestWrapper {

    public $eventful;
    public $location;
    public $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        // This is needed to run the Code Igniter Unit Test suite
        $this->CI->load->library('unit_test');
        // Load the Eventful library
        $this->CI->load->library('eventful');
        // Load the location library
        $this->CI->load->library('location');
        // Load Helper library
        $this->CI->load->library('helper');
        
        // These libraries need to be intialized.  Helper library 
        // should contain only static methods.
        $this->eventful = new Eventful();
        $this->location = new location("55.97.245.81");
        
    }

    public function test_getEvents() {
        $this->CI->unit->run($this->eventful, 'is_object', 'Object Test');
        $default_events = $this->eventful->getEvents();
        $this->CI->unit->run($default_events, 'is_array', 'Array Test');
        
        return $this->CI->unit->report();
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
        echo $json;
    }

    public function test_getLocation() {
        $loc = $this->location->getLocation();
        return $loc;
    }
    
    // Test the helper library
    public function test_helper_titlelizer() {
        $test = "Titlelizer correctness";
        $title = "THIS IS a STring With FUnky tITLE";
        
        $this->CI->unit->set_test_items(array('test_name', 'result')); 
        
        $this->CI->unit->run(Helper::titleize($title), "This Is A String With Funky Title", $test);
        
        $test = "Titlelizer empty string";
        $notes = "Checks for empty string";
        $this->CI->unit->run(Helper::titleize(""), "", $test);
        
        return $this->CI->unit->report();
    }
    
    public function test_helper_geolocate() {
        $city = "Los Angeles";
        $test = "Geolocate correctness";
        $lonlat = Helper::geolocate($city);
        
        $this->CI->unit->run($lonlat, 'is_array', $test);
        $this->CI->unit->run($lonlat['lon'], -118.2436849, $test);
        $this->CI->unit->run($lonlat['lat'], 34.0522342, $test);
        
        $city = "dfsdfs";
        $lonlat = Helper::geolocate($city);
        $this->CI->unit->run($lonlat, 'is_null', $test);
        
        return $this->CI->unit->report();
    }

}