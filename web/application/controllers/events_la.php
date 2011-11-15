<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Events_la extends CI_Controller {
    
    public $eventful;
    public $location;
	public $ranking;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->load->library('location');
        $this->load->helper('url');
		$this->load->library('event_ranking_fb');
        $this->eventful = new Eventful();
        $this->ranking = new Event_ranking_fb();
        $this->location = new Location();
    }
    
    public function index() {
        $city = $this->location->getCity();
		$venue_to_like_counts = array();
        //$zip = $this->location->getZipCode();
        if($city == '-')
            $city = "";
        $la_events = $this->eventful->getEvents(array('location' => $city));
		$la_events = $this->ranking->fb_event_ranking($la_events,$venue_to_like_counts);
        $la_events_cal = $this->event_cal_filter($la_events);
        $data = array('la_events' => $la_events, 'msg' => $city, 'events_cal' => $la_events_cal);
        $this->load->view('events_la', $data);
    }
    
    public function search($city = '') {
        // Expect this input to always be correct:
        $my_city = !empty($city) ? $city : $this->input->post('city');
        $local_events = $this->eventful->getEvents(array('location' => $my_city));
        
        $data = array('la_events' => $local_events, 'city' => $my_city, 'msg' => 'search');
        $this->load->view('events_la', $data);

    }

   /* Filter our the unnecessay data from the whole event object for the
      calendar based on event_calendar_fields array
      @return json object of filted events
   */
    public function event_cal_filter($events) {
        $event_calendar_fields = array('title', 'start_time', 'stop_time');
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