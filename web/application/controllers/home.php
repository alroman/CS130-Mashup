<?php
/**
 * This is the home page of the website, and it should show the filtering and 
 * events on top of map.
 * @require Eventful API, Google Map API, Filering
 **/
class Home extends CI_Controller
{
   var $fields = array('title', 'description', 'longitude', 'latitude','venue_name', 'start_time', 'stop_time', 'category', 'heat_rank', 'venue_address','city_name');
   var $default_category = array('music', 'movies', 'comedy');
   var $default_keywords = array('free', 'food', 'tickets', 'comedy', 'ninja', 'turtles', 'movie', 'television archive', 'echo park');

   function __construct()
   {
      //Call the parent construct
      parent::__construct();

      //Get uitl library and it takes care of everything
      $this->load->Library('util');

      //Get helper
      $this->load->helper('form');
      
      $this->load->library('event_ranking_fb');
   }

   public function __index($location, $filter=array()) {
      //Show the home page
      $events = $this->util->getEvents(array('location' => $location['zipCode']));
      $categories = $this->util->getCategories();
      
      $venue_to_like_counts = array();
      $events = $this->event_ranking_fb->fb_event_ranking($events, $venue_to_like_counts);

      //Event Fields Needed
      $fields = $this->fields;
      $json_events = $this->util->event_filter($events, $fields, $this->default_keywords);
      
      //Decide which view i want to use
      $data['main_content'] = 'home';
      $data['json_events']  = $json_events;
      $data['geoloc']       = $location;
      $data['title']        = 'Entertainment+';
      $data['events']       = Helper::simple_filter($events, $this->default_keywords);
      $data['public_url']   = $this->util->getPublicUrl();
      $data['categories']   = $categories;
      $data['location']     = $location['zipCode'];

      $this->load->view('includes/template', $data);
   }

   public function index()
   {
      //Show the home page
      $location = $this->util->getLocation();
      $input = $this->input->post('city_search');
      
      if (!empty($input)) {
         $this->search($input);
      } else {
         $this->__index($location);
      }
   }

   public function search($input)
   {
      //validate input
      $special_string = '/(\=|\+|\-|\(|\))/';
      if (preg_match($special_string, $input)) {
         return "Error: Input invalid string";
      }
      
      $location = $this->__getCityGeo($input);
      $location['zipCode'] = $input;
      
      //Call __index
      $this->__index($location);
   }

   //Implement the AJAX call
   //Using cached events, no more calling eventful every time.
   public function filter() {
      $opts = $this->input->post();
      if (isset($opts['category'])) {
         
         $events          = $opts['events'];
         $cats            = $opts['category'];
         $filtered_events = array();
         $user_tags       = array();

         //filter out the category
         foreach ($events as $e) {
            $tmp_cat = $e['category'];
            foreach ($cats as $cat) {
               if (in_array($cat, $this->default_category)) {
                  //If it is an category
                  //case insensitive comparision
                  if (strcasecmp($tmp_cat, $cat) == 0) {
                     $filtered_events []= $e;
                     break; //break the loop
                  }
               } else if(!in_array($e, $filtered_events)) {
                  //Second chance to output the events based on users tag if any.
                  //If it is not a category, filter out the category based on 
                  //the description.
                  $words = str_word_count($e['description'], 1);
                  foreach ($words as $w) {
                     if (strcasecmp($w, $cat) == 0) {
                        $filtered_events []= $e;
                        break; //break the loop
                     }
                  }
               }
            }
         }
         
         if (isset($opts['tags']) && !empty($opts['tags'])) {
            $tmp = array();
            $this->__filterTags($filtered_events, $opts['tags'], $tmp);
            $filtered_events = $tmp;
         }

         $json_events = json_encode($filtered_events);

         echo $json_events;
      } else {
         $msg = array('error' => 'no events');
         echo json_encode($msg);
      }
   }


   private function __getCityGeo($addr) 
   {
      $city_url = urlencode($addr);
      $data = $this->curl->simple_get("http://maps.googleapis.com/maps/api/geocode/json?address=$city_url&sensor=false");
      $json_results = json_decode($data);

      if(isset($json_results->results[0]->geometry->location)) {
         $loc = $json_results->results[0]->geometry->location;
         return array('longitude' => $loc->lng, 'latitude' => $loc->lat);
      }
   }

}
