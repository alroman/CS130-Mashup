<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tweets extends CI_Controller {
    
    public $eventful;
    public $location;
    public $Twitter;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->load->library('location');
        $this->load->library('twitter');
        $this->load->helper('url');
        $this->eventful = new Eventful();
        $this->location = new Location();
        $this->twitter = new Twitter();
    }
    
    public function index() {
        
        $city = $this->location->getCity();
        
        if($city == '-')
            $city = "";
        
        $la_events = $this->eventful->getEvents(array('location'=>$city));
        
        foreach ($la_events as $e) {         

            $event = (object)$e;
            $search_res = $this->twitter->searchResults($event);
                   
            // Get tweets count
            $tweets = $search_res->children();
            $tweets_count = count($tweets) - 7;
            echo "<p>Event: " .$event->title. " playing @" .$event->venue_name. " has <b>" .$tweets_count. "</b>.</p>";

        } 
    }
}

