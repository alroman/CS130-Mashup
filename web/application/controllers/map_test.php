<?php

class Map_test extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
    }
    
    public function index() {
        $this->load->view('map_test');
    }

}