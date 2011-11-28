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
      $this->load->library('facebook_connect');
   }
   
   //in: @array of objects of user's likes, @default_events
   //out: @ranked events based on user's likes substr_count hits
   function rank($user_likes,$get_events) {           

       $ranked_events = array();
       $event_rank = array();
       $title_event_map = array();
        foreach ($get_events as $value)
        {
            $title_event_map[$value['title']] = $value;
            $event_rank[$value['title']] = 0;
        }
//        echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
        foreach($event_rank as $title => $hits_count)
        {
            foreach ($get_events as $event)
            {
               $hits = 0;
               $keywords = $event['title'] . ' ' . $event['venue_name'] . ' ' . $event['description'];
               foreach ($user_likes['data'] as $like)
               {
                   $hits += substr_count(strtolower($keywords), strtolower($like['name']));
               }
               $event_rank[$event['title']] = $hits;
               
            }
//            echo $event_rank[$title] . '<br/>';
        }
        arsort($event_rank);
        foreach($event_rank as $title => $_)
        {
//            echo $event_rank[$title] . '<br/>';
          $ranked_events[] = $title_event_map[$title];
        }
        // get number of events and divide by 5 for popularity scale
	$heatRankIncrement = ceil(count($ranked_events)/5);
	
	$i=0;
      echo '<br /><br /><br /><br /><br />called rank()';

	foreach($ranked_events as $value)
	{
		if ($i < $heatRankIncrement)
		{
			$ranked_events[$i]["heat_rank"] = "hot";
//			print_r($ranked_events[$i]);	
		}
		else if ($i < $heatRankIncrement*2)
		{
			$ranked_events[$i]["heat_rank"] = "warm";
//			print_r($ranked_events[$i]);
		}
		else if ($i < $heatRankIncrement*3)
		{
			$ranked_events[$i]["heat_rank"] = "neutral";
//			print_r($ranked_events[$i]);
		}
		else if ($i < $heatRankIncrement*4)
		{
			$ranked_events[$i]["heat_rank"] = "cool";
//			print_r($ranked_events[$i]);
		}
		else
		{
			$ranked_events[$i]["heat_rank"] = "ice";
//			print_r($ranked_events[$i]);
		}
		$i++;
	}	
        return $ranked_events;
    }

   public function __index($location, $filter=array()) {
       
      //Show the home page
      $events = $this->util->getEvents(array('location' => $location['zipCode']));
      $categories = $this->util->getCategories();
      
      //Facebook API 
      $fb_config = array(
               'appId' => '121701154606843',
               'secret' => '9375f40fc20e1a2025adff48d9f5154c',
            );
      $this->load->library('facebook',$fb_config);                       
      $logged_in = $this->facebook->getUser();
      $params = array(
          'scope' => 'user_likes, friends_likes',
          'redirect_uri' => 'http://localhost/CS130-Mashup/web/index.php/home'
      );
      $login_url_perm = $this->facebook->getLoginUrl($params);
      
      if ($logged_in) { //A Session is found
          try {
  //        echo '<br /><br /><br /><br /><br />logged in';
              //API Call: get user profile info
              $user_profile = $this->facebook->api('/me');
              $data['fb_name'] = $user_profile['name'];
              $data['display_img'] = '<img height="36px" src="http://graph.facebook.com/'.$logged_in.'/picture"/>';
              
              //API Call: get user's likes
              $user_likes = $this->facebook->api('/me/likes');
              $events = $this->rank($user_likes,$events);
                      
          } catch (FacebookApiException $e) {
              $logged_in = null;
          }
      }
      if ($logged_in) {
          $data['logout_url'] = $this->facebook->getLogoutUrl();
      } else {//Session not found or expire, or user logged out
          $data['login_url'] = $login_url_perm;
 //         echo '<br /><br /><br /><br /><br />logged out';
	
          //Wei's ranking using FB Venue
          $venue_to_like_counts = array();
          $events = $this->event_ranking_fb->fb_event_ranking($events, $venue_to_like_counts);
          
      }
      //END Facebook API stuff
            
      //Event Fields Needed
      $fields = $this->fields;
      $json_events = $this->util->event_filter($events, $fields, $this->default_keywords);
      //Decide which view i want to use
      $data['json_events']  = $json_events;
      $data['main_content'] = 'home';
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
