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
    
    /* Basically, retrieve events tweets, rank it by tweets count, and load it onto view
     * 
     * Steps:
     * 1. Get Events from Eventful Library 
     * 2. Get # of tweets per event
     * 3. Save event objects onto new object "events" additional fields, tweets_count and tweets_link
     * 4. Sort the new events object
     * 5. Load to tweets view
     */
    public function index() {
        
        $city = $this->location->getCity();
        
        if($city == '-')
            $city = "";
        
        $la_events = $this->eventful->getEvents(array('location'=>$city));

        // use to store la_events with tweets_count
        // this array will be stored and returned
        $events = array();        
        foreach ($la_events as $e) {         

            $event = (object)$e;
            $search_res = $this->twitter->getTweetsByEventObject($event);
            
            // Get tweets count
            $tweets = $search_res->children();
            // get rid of non-tweets nodes
            $event->tweets_count = count($tweets) - 7;
            // build hyperlink to retrieve an event's tweets
            $hyperlink = 'tweets/get_tweets/'. urlencode($event->title).'/'.urlencode($event->venue_name);
            $hyperlink = str_replace("+", "%25",$hyperlink);
            $event->tweets_link = $hyperlink;
            // push the this event object with tweets_count onto the array stack
            array_push($events,$event);
        }
        
        // Rank the events
        function cmp($a, $b)
        {
            $oa = (object)$a;
            $ob = (object)$b;
            if ($oa->tweets_count == $ob->tweets_count) {
                return 0;
            }
            return ($oa->tweets_count > $ob->tweets_count) ? -1 : 1;
        }
        usort($events,'cmp');        
        $data = array('events' => $events);
        $this->load->view('tweets', $data);
    }
   
    /*
     * Return list of tweets (as formatted string) for an event   
     * and load it to /get_tweets
     */
    public function get_tweets($title, $venue) {

        $search_res = $this->twitter->getTweetsByEventTitleAndVenue($title, $venue);
        $title = str_replace('%25', ' ', $title);
        $venue = str_replace('%25', ' ', $venue);
        $string = '<h2>' .$title. '</b> at <b>' .$venue.'</b></h2> tweets:<br/><b><br/>';
        foreach ($search_res->entry as $twit1) {

            // Work out the Date plus 8 hours
            // get the current timestamp into an array
  
            $timestamp = time();
            $date_time_array = getdate($timestamp);

            $hours = $date_time_array['hours'];
            $minutes = $date_time_array['minutes'];
            $seconds = $date_time_array['seconds'];
            $month = $date_time_array['mon'];
            $day = $date_time_array['mday'];
            $year = $date_time_array['year'];

            // use mktime to recreate the unix timestamp
            // adding 19 hours to $hours
            $timestamp = mktime($hours + 0,$minutes,$seconds,$month,$day,$year);
            $theDate = strftime('%Y-%m-%d %H:%M:%S',$timestamp);	

            // END DATE FUNCTION

            $description = $twit1->content;

            $description = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $description);  
            $description = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" >\\2</a>'", $description);
            $description = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" >\\2</a>'", $description);

            $retweet = strip_tags($description);

            $date =  strtotime($twit1->updated);
            $dayMonth = date('d M', $date);
            $year = date('y', $date);
            $message = $twit1['content'];
            $datediff = $this->twitter->date_diff($theDate, $date);
            $string .= "<div class='user'><a href=\"".$twit1->author->uri."\" target=\"_blank\"><img border=\"0\" width=\"48\" class=\"twitter_thumb\" src=\"".$twit1->link[1]->attributes()->href."\" title=\"". $twit1->author->name. "\" /></a>\n";
            $string .= "<div class='text'>".$description."<div class='description'>From: ". $twit1->author->name." <a href='http://twitter.com/home?status=RT: ".$retweet."' target='_blank'>Retweet!</a></div><strong>".$datediff."</strong></div><div class='clear'></div></div>";

        }
        $data = array('string' => $string);
        $this->load->view('get_tweets', $data);
      }
}

