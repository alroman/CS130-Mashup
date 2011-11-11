<?php
/**
 * This is the home page of the website, and it should show the filtering and 
 * events on top of map.
 * @require Eventful API, Google Map API, Filering
 **/
class Home extends CI_Controller
{
   function __construct()
   {
      //Call the parent construct
      parent::__construct();

      //Get uitl library and it takes care of everything
      $this->load->Library('util');

      //Get helper
      $this->load->helper('form');
   }

   public function __index($location) {
      //Show the home page
      $events = $this->util->getEvents(array('location' => $location['zipCode']));
      //Event Fields Needed
      $fields = array('title', 'description', 'longitude', 'latitude','venue_name');
      $json_events = $this->util->event_filter($events, $fields);

      //Decide which view i want to use
      $data['main_content'] = 'home';
      $data['json_events']  = $json_events;
      $data['geoloc']       = $location;
      $data['title']        = 'Entertainment+';
      $data['events']       = $events;
      $data['public_url']   = $this->util->getPublicUrl();

      $this->load->view('includes/template', $data);
   }

   public function index()
   {
      //Show the home page
      $location = $this->util->getLocation();

      $this->__index($location);
   }

   public function search()
   {
      // Check if we have search input
      $input = $this->input->post('city_search');

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
