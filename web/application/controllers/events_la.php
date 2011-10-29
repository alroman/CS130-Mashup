<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Events_la extends CI_Controller {
    
    public function index() {
        $this->load->view('events_la');
    }
}