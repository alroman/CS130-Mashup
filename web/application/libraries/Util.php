<?php 
/**
 * Util Library for everything
 * All the functions are tested, please look at the unit_test to see how to use 
 * this class.
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
      $this->CI->load->library('helper');
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

   public function event_filter($events, $fields, $keywords = null) 
   {
      if (empty($fields)) {
        return "Error: Second argument - fileds, cannot be empty";
      }

      // Use the helper class since that fixes titles and description lengths
      // It also adds a long-description for full display info
      return Helper::JSONize($events, $fields, $keywords);

   }

   public function getPublicUrl()
   {
      //Load helper
      $this->CI->load->helper('url');
      $splitary = preg_split('/web/i', base_url());
      return $splitary[0] . 'web/';
   }
   
   //Return key words that found in events.
   public function search_keywords($events, $keywords=false) {
      if (!$keywords) {
         $keywords = array('free', 'food');
      }
      $is_existed  = array();
   
      //Find if the key word existed
      foreach ($keywords as $kw) {
         foreach ($events as $e) {
            $words = str_word_count($e['description'], 1);
            foreach ($words as $w) {
               if ($w === $kw) {
                  $is_existed[] = $kw;
                  break;
               }
            }
         }
      }
   
      //Return all the found keywords
      return $is_existed;
   }

   public function getAllKeywords($events)
   {
      $AllKeywords = array();
      foreach ($events as $event) {
         foreach ($event['keywords'] as $keyword) {
            if (!in_array($keyword, $AllKeywords)) {
               $AllKeywords []= $keyword;
            }
         }
      }

      return $AllKeywords;
   }
}
