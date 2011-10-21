<?php

/**
* Eventful API Class
* it receives location and use eventful api to get the result back.
* the result object will contain all the information from eventful.
 **/
class Eventfulapi extends CI_Controller {

   var $eventful_key;
   var $search_prefix = "http://api.eventful.com/rest/events/search";
   
   public function __construct($key="xzbXfQPZsjPVL2qw") {
      parent::__construct();
      $this->eventful_key = $key;
      $this->load->library('curl');
   }

   public function index()
   {
      $this->load->view("eventful_api_view.php");
      echo "Hello world!";
   }

   public function searchEvents($filter=array('location', 'date')) {
      $location = !(empty($filter['location'])) ? $filter['location'] : 'Los Angeles';
      $date = !(empty($filter['date'])) ? $filter['date'] : 'This Week';
      $query = array(
         "location" => $location,
         "date" => $date,
         "app_key" => $this->eventful_key
      );

      $data['events'] = $this->curl->simple_get($this->search_prefix, $query);
      // print "<pre>".print_r($data, true)."</pre>";
   }


}
