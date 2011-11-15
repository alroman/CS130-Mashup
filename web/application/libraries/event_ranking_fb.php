<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event_ranking_fb
{

  public $eventful;
  var $search_prefix = "http://graph.facebook.com/search";
  public $CI;

  public

  function __construct()
  {
    $this->CI=&get_instance();
    $this->CI->load->library('curl');
    $this->CI->load->library('xml');
    $this->CI->load->helper('form');
    $this->CI->load->helper('url');
    // $facebook = new Facebook(array(
    // 'appId'  => '133716586710979',
    // 'secret' => '9df76ec2cd10f1eca00ba9eb98996985',
    // ));
  }

  public

  // input the event array, the function will return an event array sorted by 
  // facebook fan page like counts
  function fb_event_ranking($data, &$venue_to_like_counts)
  {

    $page_like_search = "http://graph.facebook.com/";
    $title_event_map = array();
    $event_rank = array();
    $page_content = null;
    $ranked_event = array();

    foreach($data as $value)
    {
      $title_event_map[$value['venue_name']] = $value;
      $event_rank[$value['venue_name']] = 0;
    }

    foreach($event_rank as $title => $like_count)
    {
      $query = array("q" => $title, "type" => "page", "limit" => '1', );
      $page_content = $this->CI->curl->simple_get($this->search_prefix, $query);
      $json_o = json_decode($page_content);
	  
      if (!empty($json_o->data[0]))
      {
        //get the facebook pageID
        $page_id = $json_o->data[0]->id;
        //fetch like count from the facebook page
        $page_content = $this->CI->curl->simple_get($page_like_search.$page_id);
        $json_a = json_decode($page_content);
        if (isset($json_a->likes))
        {
          $event_rank[$title] = $json_a->likes;
          // print_r($json_a->likes);
        }
      }
    }
	// sort the event based on like count
    arsort($event_rank);
	$venue_to_like_counts = $event_rank;
    // print_r($event_rank);
    foreach($event_rank as $title => $_)
    {
      $ranked_event[] = $title_event_map[$title];
    }
    return $ranked_event;
  }


}