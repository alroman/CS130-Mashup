<?php

Class Twitter {

    public function __construct() {
        
    }

    public function getTweetsByEventObject( $event = null ) {
        
        $title = $event->title;
        $venue = $event->venue_name;
//        $q = $venue . ' "' .$title. '" /OR "' .$title. ' :) "';
        $q = $venue . ' "' .$title. '"';
        $url = "http://search.twitter.com/search.atom?q=" .  urlencode($q) . "&lang=en&rpp=100";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        $result = curl_exec( $curl );
        curl_close( $curl );
        $return = new SimpleXMLElement( $result );
        return $return;
    }
    
    public function getTweetsByEventTitleAndVenue( $title = null, $venue = null ) {
        
        $q = $venue . '%22' .$title. '%22';
        $url = "http://search.twitter.com/search.atom?q=". $q. "&lang=en&rpp=100";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $curl );
        curl_close( $curl );
        $return = new SimpleXMLElement( $result );
        return $return;
    }
    
    

        
 // Calculate how long ago a tweet was posted
    function date_diff($d1, $d2){
	$d1 = (is_string($d1) ? strtotime($d1) : $d1);
	$d2 = (is_string($d2) ? strtotime($d2) : $d2);

	$diff_secs = abs($d1 - $d2);
	$base_year = min(date("Y", $d1), date("Y", $d2));

	$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
	$diffArray = array(
		"years" => date("Y", $diff) - $base_year,
		"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
		"months" => date("n", $diff) - 1,
		"days_total" => floor($diff_secs / (3600 * 24)),
		"days" => date("j", $diff) - 1,
		"hours_total" => floor($diff_secs / 3600),
		"hours" => date("G", $diff),
		"minutes_total" => floor($diff_secs / 60),
		"minutes" => (int) date("i", $diff),
		"seconds_total" => $diff_secs,
		"seconds" => (int) date("s", $diff)
	);
	if($diffArray['days'] > 0){
		if($diffArray['days'] == 1){
			$days = '1 day';
		}else{
			$days = $diffArray['days'] . ' days';
		}
		return $days . ' and ' . $diffArray['hours'] . ' hours ago';
	}else if($diffArray['hours'] > 0){
		if($diffArray['hours'] == 1){
			$hours = '1 hour';
		}else{
			$hours = $diffArray['hours'] . ' hours';
		}
		return $hours . ' and ' . $diffArray['minutes'] . ' minutes ago';
	}else if($diffArray['minutes'] > 0){
		if($diffArray['minutes'] == 1){
			$minutes = '1 minute';
		}else{
			$minutes = $diffArray['minutes'] . ' minutes';
		}
		return $minutes . ' and ' . $diffArray['seconds'] . ' seconds ago';
	}else{
		return 'Less than a minute ago';
	}
    }

}
?>
