<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_ranking_fb {
    
    public $eventful;
    // var $search_prefix = "http://graph.facebook.com/search?q=";
    var $search_prefix = "http://graph.facebook.com/search";
	public $CI;
	      
    public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('curl');
		$this->CI->load->library('xml');		
        $this->CI->load->helper('form');
        $this->CI->load->library('eventful');
        // $this->load->library('facebook');
        $this->CI->load->helper('url');
        $this->eventful = new Eventful();
		$this->get_event_fb_id();
		// $facebook = new Facebook(array(
		  // 'appId'  => '133716586710979',
		  // 'secret' => '9df76ec2cd10f1eca00ba9eb98996985',
		// ));
		//printf("hello");
    }
	
	public function get_event_fb_id() {
	
	    // $query = "lady&type=event";
         $query = array(
            "q" => "lady",
            "type" => "event",
         );		
		//$data = file_get_contents ($this->search_prefix.$query);
	    $data = $this->CI->curl->simple_get($this->search_prefix, $query);
		var_dump($data);
	
	
	}
    
    public function index() {
		$city = "";
        $la_events = $this->eventful->getEvents(array('location' => $city));
		var_dump($la_events); 
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