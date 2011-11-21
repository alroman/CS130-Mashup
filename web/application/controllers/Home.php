<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public $eventful;
    var $search_prefix = "http://graph.facebook.com/search";
    public $CI;
    
	public function __construct()
	{
            parent::__construct();
            $this->load->library('eventful');
            $this->load->library('location');
            $this->location = new Location();
            $this->eventful = new Eventful();
/*
            $this->CI=&get_instance();
            $this->CI->load->library('curl');
            $this->CI->load->library('xml');
            $this->CI->load->helper('form');
            $this->CI->load->helper('url');
  */        
            
//            $this->load->model('Facebook_model');
            
	}

        function rank($user_likes) {
           
            // get events
            $city = $this->location->getCity();
            if ($city == '-')
                $city = "";
            
            $get_events = $this->eventful->getEvents(array('location'=>$city));
            $events = array();
            
            foreach ($get_events as $e)
            {
                $event = (object)$e;
                $texts = $event->title . ' ' . $event->venue_name . ' ' . $event->description;
                $event->fb_hits = 0;
               foreach ($user_likes['data'] as $like)
               {
                   $event->fb_hits += substr_count($texts, $like['name']);
               }
                echo $event->title . ' has ' . $event->fb_hits . ' hits.<br />';
               array_push($events,$event);
            }
            
            function cmp($a,$b)
            {
                $oa = (object)$a;
                $ob = (object)$b;
                if ($oa->fb_hits == $ob->fb_hits) {
                    return 0;
                }
                return ($oa->fb_hits > $ob->fb_hits) ? -1 : 1;
            }
            usort($events,'cmp');
            
            echo '<br /> ### SORTED EVENTS ###<br />';
            foreach ($events as $e)
            {
                echo $e->title . ' has ' . $e->fb_hits . ' hits.<br />';
            }
            return $events;
        }
           
        function get_friends_likes($user,$user_friends) {
            
            foreach ($user_friends['data'] as $friend)
               {
                   $friend_uid = $friend['id'];
                 //  echo $friend_uid . '<br />';
                   if ($user) {
                        try {
                              $friends_likes = $this->facebook->api('/'.$friend_uid.'/likes');
                        } catch (FacebookApiException $e) {
                            $user = null;

                        }
                    }
               }
        }
        
	function index() {
            $fb_config = array(
               'appId' => '121701154606843',
               'secret' => '9375f40fc20e1a2025adff48d9f5154c',
            );
            $this->load->library('facebook',$fb_config);                       
            $user = $this->facebook->getUser();
            $params = array(
                'scope' => 'user_likes, friends_likes',
                'redirect_uri' => 'http://localhost/CS130-Mashup/web/index.php/home'
            );
            $login_url_perm = $this->facebook->getLoginUrl($params);
            
            // FQL: SELECT name, type FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid=100001653491805)
           
            if ($user) {
                try {
                      $user_profile = $this->facebook->api('/me');
                      $data['display_img'] = '<img height="40px" src="http://graph.facebook.com/'.$user.'/picture"/>';
                      $user_likes = $this->facebook->api('/me/likes');                      
                      $user_friends = $this->facebook->api('/me/friends');
                      
                      $all_likes = $this->get_friends_likes($user,$user_friends);
//                       foreach ($user_likes['data'] as $like)
//                       {
//                           echo $like['name'];
//                       }
                      $events = array();
                      
                      // what we got here is an array of sorted events in order of facebook hits
                      $events = $this->rank($user_likes);

                } catch (FacebookApiException $e) {
                    $user = null;
                    
                }
            }
            
            if ($user) {
                $data['logout_url']= $this->facebook->getLogoutUrl();
            } else {
                $data['login_url'] = $login_url_perm;
            }
            $this->load->view('home',$data);
        }	
    
}
?>
