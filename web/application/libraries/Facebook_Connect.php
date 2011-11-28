<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Facebook_Connect
{
    public function __construct()
    {
        $this->CI=&get_instance();
        $this->CI->load->library('curl');
        $this->CI->load->library('xml');
        $this->CI->load->helper('form');
        $this->CI->load->helper('url');
    }
    
}
?>