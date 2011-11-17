<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public $eventful;
    var $search_prefix = "http://graph.facebook.com/search";
    public $CI;
    
	public function __construct()
	{
            parent::__construct();
/*
            $this->CI=&get_instance();
            $this->CI->load->library('curl');
            $this->CI->load->library('xml');
            $this->CI->load->helper('form');
            $this->CI->load->helper('url');
  */        
            
//            $this->load->model('Facebook_model');
            
	}

	function index() {
            $fb_config = array(
               'appId' => '121701154606843',
               'secret' => '9375f40fc20e1a2025adff48d9f5154c',
            );
            $this->load->library('facebook',$fb_config);                       
            $user = $this->facebook->getUser();
            $params = array(
                'scope' => 'user_likes',
                'redirect_uri' => 'http://localhost/CS130-Mashup/web/index.php/home'
            );
            $login_url_perm = $this->facebook->getLoginUrl($params);
            
            // FQL: SELECT name, type FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid=100001653491805)
           
            if ($user) {
                try {
                      $data['user_profile'] = $this->facebook->api('/me');
                      $data['display_img'] = '<img height="40px" src="http://graph.facebook.com/'.$user.'/picture"/>';
                      //echo json_encode($data);
                      $data['likes'] = $this->facebook->api('/me/likes');
                      //echo json_encode($likes);
                    /*
                      $fql = 'SELECT name, type FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid = ' .$user;
                      $likes = $this->facebook->api(array (
                          'method' => 'fql.query',
                          'query' => $fql,
                      ));*/
//              var_dump($likes);
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
