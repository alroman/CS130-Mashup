<?php 
/**
 * Util Library for everything
 **/
class Util 
{
   public $CI;
   function __construct()
   {
      $this->CI =& get_instance();

      //Load library
      $this->CI->load->library('eventful');
      $this->CI->load->library('location');
   }

   //Using this http://ipinfodb.com/my_ip_location.php
   //For location api
   public function getLocation()
   {
      $geoloc = $this->CI->location->getLocation();

      //validate city name
      $special_string = '/(\=|\+|\-|\(|\))/';

      // If city could not be guessed, then we default into Los Angeles
      if(empty($geoloc['longitude']) 
         || empty($geoloc['latitude'])
         || (preg_match($special_string, $geoloc['zipCode']) != 0)) {
         $geoloc['zipCode'] = '90024';
         $geoloc['latitude'] = "34.0827490";
         $geoloc['longitude'] = "-118.4140820";
      }
      return $geoloc;
   }

   public function getEvents($filter)
   {
      return $this->CI->eventful->getEvents($filter);
   }

   public function event_filter($events, $fields) 
   {
      if (empty($fields)) {
        return "Error: Second argument - fileds, cannot be empty";
      }

      $filtered_events = array();
      foreach ($events as $key => $value) {
         $tmp = array();
         foreach ($fields as $v) {
            $tmp[$v] = utf8_encode(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', $value[$v]));
         }
         $filtered_events []= $tmp;
      }
      return json_encode($filtered_events);
   }

   public function getPublicUrl()
   {
      //Load helper
      $this->CI->load->helper('url');
      $splitary = preg_split('/web/i', base_url());
      return $splitary[0] . 'web/';
   }
}
