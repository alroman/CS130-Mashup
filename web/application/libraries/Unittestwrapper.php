<?php

class UnitTestWrapper {

    public $eventful;
    public $location;
    public $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        $this->CI->load->library('unit_test');
        $this->CI->load->library('eventful');
        $this->CI->load->library('location');
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

}