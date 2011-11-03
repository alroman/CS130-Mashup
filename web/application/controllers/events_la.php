<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Events_la extends CI_Controller {
    
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
        $city = $this->location->getCity();
        //$zip = $this->location->getZipCode();
        if($city == '-')
            $city = "";
        $la_events = $this->eventful->getEvents(array('location' => $city));
        $data = array('la_events' => $la_events, 'msg' => $city);
        $this->load->view('events_la', $data);
    }
    
    public function search($city = '') {
        // Expect this input to always be correct:
        $my_city = !empty($city) ? $city : $this->input->post('city');
        $local_events = $this->eventful->getEvents(array('location' => $my_city));
        
        $data = array('la_events' => $local_events, 'city' => $my_city, 'msg' => 'search');
        $this->load->view('events_la', $data);

    }
}