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

      $this->load->helper('form');
   }

   public function index()
   {
      //Decide which view i want to use
      $data['main_content'] = 'home';

      //Show the home page
      $location = $this->util->getLocation();
      $events = $this->util->getEvents(array('location' => $location['zipcode']));
      // echo $location['zipcode'];
      //Event Fields Needed
      $fields = array('title', 'description', 'longitude', 'latitude','venue_name');
      $json_events = $this->util->event_filter($events, $fields);

      $data['all_events'] = $json_events;
      $data['geoloc'] = $location;
      $data['title'] = 'Entertainment+';
      $data['public_url'] = $this->util->getPublicUrl();

      $this->load->view('includes/template', $data);
   }

}
