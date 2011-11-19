<?php

/**
* Calendar class
* It is responsible for the calendar module on the main page.
* It gets the request from the calendar and response back the events
 **/
class Calendar extends CI_Controller {

   var $event_calendar_fields = array('title', 'start_time', 'stop_time');
   var $filtered_events = array();
   
   function __construct() {
      parent::__construct();
      $this->load->library('eventful');
      $this->load->helper('url');
      
      $this->eventful = new Eventful();
   }

   /*Get event by date, it required eventful library
   @return an array of events object
   */
   public function getEventByDate() {
      $default_events = $this->eventful->getEvents();
      return $default_events;
   }

   /* Render the calendar_view page to demo the event calendar
   */
   public function index() {
      $data = array();
      $fullevents = $this->getEventByDate();
      $this->filter($fullevents);
      // print "<pre>".print_r($this->filtered_events, 'r')."</pre>";
      $data['events'] = json_encode($this->filtered_events);
      $this->load->view("calendar_view", $data);
   }

   /* Filter our the unnecessay data from the whole event object for the
      calendar based on event_calendar_fields array
      @return filted object
   */
   public function filter($events) {
      foreach ($events as $key => $value) {
         $tmp = array();
         foreach ($this->event_calendar_fields as $v) {
<<<<<<< HEAD
            $tmp[$v] = utf8_encode(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', $value[$v]));
=======
            $tmp[$v] = $value[$v];
>>>>>>> cafc6e1231739576d9495b30df66afac44dd73a9
         }
         $this->filtered_events []= $tmp;
      }
   }
}
