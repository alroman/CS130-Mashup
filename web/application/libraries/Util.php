<?php 
/**
 * Util Library for everything
 **/
class Util 
{
   var $CI;

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
         $geoloc['zipCode'] = 'Los Angeles';
         $geoloc['latitude'] = "34.05223420";
         $geoloc['longitude'] = "-118.24368490";
      }
      return $geoloc;
   }

   public function getEvents($filter)
   {
      return $this->CI->eventful->getEvents($filter);
   }

   public function getCategories()
   {
      return $this->CI->eventful->getCategories();
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

   public function assignKeyWordsToEvents($events) {
      $keywords = array();
      $assigned_events = array();
      $special_char_pat = '/-/';

      foreach ($events as $k => $e) {
         $assigned_events []= $e;
         $tmp_ary = array();
         
         //Remove all the special characters
         $desc = strip_tags(preg_replace($special_char_pat, '', $e['description']));

         //1 - returns an array containing all the words found inside the string
         $tmp_ary = str_word_count($desc, 1);
         $keywords_ary = array_count_values($tmp_ary);
         arsort($keywords_ary);
         print_r($keywords_ary);
         $tmp_counts_keywords = array_keys($keywords_ary);
         if (sizeof($tmp_counts_keywords) > 0) {
            $assigned_events[$k]['keyword'] = array_pop($tmp_counts_keywords);
            $keywords []= $assigned_events[$k]['keyword'];
         }
      }

      return $assigned_events;
   }
}
