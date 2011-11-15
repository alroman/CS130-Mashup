<?php
include('ip2locationlite.class.php');
 
class Location {
    private $ipLite;
	public $location;
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
   function __construct($ip = '') {
	//Load the class
	$this->ipLite = new ip2location_lite;
	$this->ipLite->setKey('b6b7ab93cf2a63d309cace52a6a3d8bc9be76f333c531f044bd378c44ea8afa5');
	//use the following to get remote user ip address.
	if (empty($ip))
     $this->location = $this->ipLite->getCity($_SERVER['REMOTE_ADDR']);  
	else 
	  $this->location = $this->ipLite->getCity($ip);  
    // $this->location = $this->ipLite->getCity('24.24.202.57');  
   }
 
	public function getCity() {
	if (!empty($this->location) && is_array($this->location)
		&& isset($this->location['cityName'])
		&& $this->location['cityName'] != '-') {
		return $this->location['cityName'];
	} else
      return null;	
	}
	
	public function getCountry() {
		if (!empty($this->location) && is_array($this->location)
			&& isset($this->location['countryName'])
			&& $this->location['countryName'] != '-') {
			return $this->location['countryName'];
		} else
		  return null;	
	}
	
	public function getState() {
		if (!empty($this->location) && is_array($this->location)
			&& isset($this->location['regionName'])
			&& $this->location['regionName'] != '-') {
			return $this->location['regionName'];
		} else
		  return null;	
	}
	
	public function getIP() {
		if (!empty($this->location) && is_array($this->location)
			&& isset($this->location['ipAddress'])
			&& $this->location['ipAddress'] != '-') {
			return $this->location['ipAddress'];
		} else
		  return null;	
	}	
	
	public function getZipCode() {
		if (!empty($this->location) && is_array($this->location)
			&& isset($this->location['zipCode'])
			&& $this->location['zipCode'] != '-') {
			return $this->location['zipCode'];
		} else
		  return null;	
	}	
	
	public function getGeo() {
	    $geo = array();
		if (!empty($this->location) && is_array($this->location)
			&& isset($this->location['latitude'])
			&& isset($this->location['longitude'])
			&& $this->location['longitude'] != '-') {
			$geo['longitude'] = $this->location['longitude'];
			$geo['latitude'] = $this->location['latitude'];
			return $geo;
		} else
		  return null;	
	}

	public function getLocation() {
		return $this->location;
	}

}
 