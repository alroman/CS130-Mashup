<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tweets extends CI_Controller {
    
    public $eventful;
    public $location;
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('eventful');
        $this->load->library('location');
        $this->load->helper('url');
        $this->eventful = new Eventful();
        $this->location = new Location();
    }
    
    public function index() {
        
        $city = $this->location->getCity();
        
        if($city == '-')
            $city = "";
        
        $la_events = $this->eventful->getEvents(array('location'=>$city));
//        $data = array('la_events' => $la_events, 'msg' => $city);
//        $this->load->view('events_la', $data);
/*
        if (true) {
            $title = 'Johnny English Reborn';
            $venue = '';
            
            $q = '"'. $venue . '" ' . $title . ' AND "' .$title. '"';
            echo "Keyword: ". $q;
            $q = str_replace(" ", "%20", $q);
            $search_query = "http://search.twitter.com/search.atom?lang=en&rpp=100&result_type=mixed&q=" .$q;

            $tw = curl_init();
            curl_setopt($tw, CURLOPT_URL, $search_query);
            curl_setopt($tw, CURLOPT_RETURNTRANSFER, TRUE);
            $twi = curl_exec($tw);
            $search_res = new SimpleXMLElement($twi);
            // Get tweets count
            $tweets = $search_res->children();
            $tweets_count = count($tweets);
           
            echo "<p>Event: " .$title. " playing at " .$venue. " has " .$tweets_count. ".</p>";
            curl_close($tw);
        }
        */
        
        foreach ($la_events as $e) {         
            $event = (object)$e;
            // Twitter Search API Query
            $title = $event->title;
            $venue = $event->venue_name;
             
            $q = $venue . ' "' .$title. '" OR "' .$title. ' :) "';
            //$q = '"'. $venue . '" ' . $title . ' AND "' .$title. '" :)';
            echo "Keyword: ". $q;

            $q = str_replace(" ", "%20", $q);
            $search_query = "http://search.twitter.com/search.atom?lang=en&rpp=100&result_type=mixed&q=" .$q;

            $tw = curl_init();
            curl_setopt($tw, CURLOPT_URL, $search_query);
            curl_setopt($tw, CURLOPT_RETURNTRANSFER, TRUE);
            $twi = curl_exec($tw);
            $search_res = new SimpleXMLElement($twi);
            // Get tweets count
            $tweets = $search_res->children();
            $tweets_count = count($tweets);
           
            echo "<p>Event: " .$title. " playing @" .$venue. " has <b>" .$tweets_count. "</b>.</p>";
            curl_close($tw);

        }
 
//        $this->load->view('tweets', $geocode);
    }
}

