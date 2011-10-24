<?php

/**
* Eventful API Class
* it receives location and use eventful api to get the result back.
* the result object will contain all the information from eventful.
*
* categories of eventful: http://api.evdb.com/rest/categories/list?app_key=xzbXfQPZsjPVL2qw
 **/
class Eventful extends CI_Controller {

   var $eventful_key;
   var $search_prefix = "http://api.eventful.com/rest/events/search";
   var $fields = array('title', 'description', 'start_time', 
      'stop_time', 'venue', 'venue_url', 
      'venue_address', 'city', 'region',
      'region_abbr', 'country', 'country_abbr',
      'latitude', 'longitude', 'modified', 'image');
   var $eventful_fields = array('title', 'description', 'start_time', 
      'stop_time', 'venue_name', 'venue_url', 
      'venue_address', 'city_name', 'region_name',
      'region_abbr', 'country_name', 'country_abbr',
      'latitude', 'longitude', 'modified', 'image');
   var $events = array();
   var $counter = 0;
   var $event_ids = array();
   
   public function __construct($key="xzbXfQPZsjPVL2qw") {
      $CI =& get_instance();
      $this->eventful_key = $key;
      $CI->load->library('curl');
      $CI->load->library('xml');
   }

   public function getEvents($filter=array('location', 'date', 'categories')) {
      $location = !(empty($filter['location'])) ? $filter['location'] : 'Los Angeles';
      $date = !(empty($filter['date'])) ? $filter['date'] : 'This Week';
      $categories = !(empty($filter['categories'])) ? $filter['categories'] : array('music', 'movies_film');

      $CI =& get_instance();
      
      foreach ($categories as $category) {
         $query = array(
            "location" => $location,
            "date" => $date,
            "category" => $category,
            "app_key" => $this->eventful_key
         );

         $data = $CI->curl->simple_get($this->search_prefix, $query);

         $CI->xml->load($data);
         
         $parsed_obj = $CI->xml->parse();
         $tmp_events = array();

         foreach ($parsed_obj['search'][0]['events'] as $key => $value) {
            foreach ($value['event'] as $k => $event) {
               $e_id = $event['__attrs']['id'];
               if (!in_array($e_id, $this->event_ids)) {
                  $tmp_events []= $event['__value'];
                  $this->event_ids []= $e_id;
               }
            }
         }
         $this->filter($tmp_events);
      }

      return $this->events;
      // print "<pre>".print_r($this->events, true)."</pre>";
      // echo count($this->events);
   }
   
   public function filter($events) {
      $count = count($this->fields);
      foreach ($events as $k => $e) {
         for ($i = 0; $i < $count; $i++) {
            $this->events[$this->event_ids[$this->counter]][$this->fields[$i]] = 
               $e[$this->eventful_fields[$i]];
         }
         $this->counter += 1;
      }
   }

}
