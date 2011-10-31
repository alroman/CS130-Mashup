<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Events_la extends CI_Controller {
    
    public $eventful;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->eventful = new Eventful();
    }
    
    public function index() {
        $la_events = $this->eventful->getEvents();
        $data = array('la_events' => $la_events);
        $this->load->view('events_la', $data);
    }
    
    public function search($city = '') {
        // Expect this input to always be correct:
        $my_city = !empty($city) ? $city : $this->input->post('city');
        $local_events = $this->eventful->getEvents(array('location' => $my_city));
        
        $data = array('la_events' => $local_events, 'city' => $my_city);
        $this->load->view('events_la', $data);

    }
}