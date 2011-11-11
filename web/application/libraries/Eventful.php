<?php

/**
* Eventful API Class
* it receives location and use eventful api to get the result back.
* the result object will contain all the information from eventful.
*
* categories of eventful: http://api.evdb.com/rest/categories/list?app_key=xzbXfQPZsjPVL2qw
 **/
class Eventful {

   var $eventful_key;
   var $search_prefix   = "http://api.eventful.com/rest/events/search";
   var $eventful_fields = array('title' , 'description'  , 'start_time'   ,
                        'stop_time'     , 'venue_name'   , 'venue_url'    ,
                        'venue_address' , 'city_name'    , 'region_name'  ,
                        'region_abbr'   , 'country_name' , 'country_abbr' ,
                        'latitude'      , 'longitude'    , 'modified'     ,
                        'image'         , 'category');
   var $events    = array();
   var $counter   = 0;
   var $event_ids = array();
   
   public function __construct($key="xzbXfQPZsjPVL2qw") {
      $CI =& get_instance();
      $this->eventful_key = $key;
      $CI->load->library('curl');
      $CI->load->library('xml');
   }

   public function _validation($filter) {
      $msg = array();
      foreach ($filter as $key => $value) {
         if ($key == 'location' && !empty($value)) {
            //special string
            $special_string = '/(\=|\+|\-|\(|\))/';
            if (preg_match($special_string, $value)) {
               $msg []= "Location Name is invalid";
            }
         } else if ($key == 'date' && !empty($value)) {
            //format: any words, or [0-9]{10}-[0-9]{10}
            $correct_format = '/(This Weekend|Future|Next month|Next 30 days|[0-9]{10}\-[0-9]{10})/';
            if (!preg_match($correct_format, $value)) {
               $msg []= "Incorrect Date format";
            }
         } else if ($key === 'categories' && !empty($value)) {
            //only allow entertainment categories
            $allowed_categories = array('music', 'movies_film');
            if (is_array($value)) {
               foreach ($value as $v) {
                  if (!in_array($v, $allowed_categories)) {
                     $msg []= "Invalid Categories";
                  }
               }
            } else {
               $msg []= "Categories are not an array";
            }
         }
      }
      return $msg;
   }
    
   public function getEvents($filter=array('location', 'date', 'categories')) {
      $msg = $this->_validation($filter);
      if (sizeof($msg) == 0) {
         $location = !(empty($filter['location'])) ? $filter['location'] : 'Los Angeles';
         $date = !(empty($filter['date'])) ? $filter['date'] : 'This Week';
         $categories = !(empty($filter['categories'])) ? $filter['categories'] : array('music', 'movies_film');

         $CI =& get_instance();
         foreach ($categories as $category) {
            $query = array(
               "l" => $location,
               "date" => $date,
               "category" => $category,
               "app_key" => $this->eventful_key
            );
            
            //Beautify the category name
            $cat = ($category == 'movies_film')? 'movies': $category;

            $data = $CI->curl->simple_get($this->search_prefix, $query);

            $CI->xml->load($data);
            
            $parsed_obj = $CI->xml->parse();
            $tmp_events = array();
            
            if (!empty($parsed_obj['search'][0]['events'][0])) {
               foreach ($parsed_obj['search'][0]['events'] as $key => $value) {
                  foreach ($value['event'] as $k => $event) {
                     $e_id = $event['__attrs']['id'];
                     if (!in_array($e_id, $this->event_ids)) {
                        $event['__value']['category'] = array($cat);
                        $tmp_events []= $event['__value'];
                        $this->event_ids []= $e_id;
                     }
                  }
               }
            }

            $this->filter($tmp_events, $this->eventful_fields);
         }

         return $this->events;
      } else {
         return $msg;
      }
   }
   
   public function filter($events, $fields) {
      $count = count($fields);
      foreach ($events as $k => $e) {
         for ($i = 0; $i < $count; $i++) {
            $this->events[$this->counter][$fields[$i]] = 
               array_pop($e[$fields[$i]]);
         }
         $this->counter += 1;
      }
   }

}
