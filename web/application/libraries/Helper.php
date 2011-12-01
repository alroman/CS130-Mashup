<?php

class Helper {
    
    /**
     * There is an limitation on this function: it cannot label the word in some 
     * cases:
     * 1. Nothing in front of a word
     * 2. Nothing at the end of a word
     *
     * @param type $keywords
     * @param type $string
     * @return type 
     */
    static public function labelize($keywords, $string) {
        // First check if we get a null set of keywords, if we don't
        // then we do a simple word replace in the string.
        if($keywords == null) {
            return $string;
        }

        // Label that we're going to apply to matched keywords
        $label_start = '<span class="label important">';
        $label_end = '</span>';
        
        $pattern = '/(\<span\ class=\"label\ important\"\>)(\w+)(\<\/span\>)/i';
        $string = preg_replace($pattern, '$2', $string);

        // Keyword_matches that show the array of kewords that the event 
        // matches.
        $keyword_matches = array();

        //Fixed bugs for Alfonso's code
        //Match all the keywords based on regular expression
        foreach($keywords as $words) {
            $pattern = '/([ |,|\.|\-]*)('. $words . ')( |,|\.|\-|\:)/i';
            $count   = 0;
            $string  = preg_replace($pattern, '$1'. $label_start . $words. $label_end. '$3', $string, -1, $count);
            if ($count > 0 && !in_array($words, $keyword_matches)) {
                $keyword_matches []= $words;
            }
        }
        // return two objects and use list($a, $b), $a is $out and $b is 
        // $keyword_matches.
        return array($string, $keyword_matches);
    }
    
    /**
     *
     * @param type $events
     * @param type $fields
     * @return type 
     */
    static public function JSONize($events, $fields = null, $keywords = null) {
        if($fields == null) {
            $felds = array('title', 'description', 'longitude', 'latitude','venue_name');
        }
        
        $filtered_events = array();
        
        foreach ($events as $key => $value) {
            $tmp = array();
            
            foreach ($fields as $v) {
                // Fix the title
                if($v == "title" || $v == "venue_name")
                    $tmp[$v] = Helper::titleize(utf8_encode(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', $value[$v])));
                // Trim the description
                // This also creates a '_long' description field in the object
                else if($v == "description") {
                    $tmp_val = nl2br($value[$v]);
                    
                    if(empty($tmp_val))
                        $tmp_val = "No description available";
                    
                    //$tmp[$v] = Helper::summarize(utf8_encode(preg_replace('/[^a-zA-Z0-9_\<\> %\[\]\.\(\)%&-:]/s', '', $tmp_val)));
                    list($tmp[$v."_long"], $tmp['keywords']) = Helper::labelize($keywords, Helper::summarize(utf8_encode(preg_replace('/[^a-zA-Z0-9_\<\> %\[\]\.\(\)%&-:]/s', '', $tmp_val)), 1000));
                    $tmp[$v] = Helper::summarize($tmp[$v."_long"]);
                }
                else 
                    $tmp[$v] = utf8_encode(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', $value[$v]));
            }
            
            $filtered_events []= $tmp;
        }
        
        return $filtered_events; 
    }
    
    static public function simple_filter($events, $keywords = null) {
        foreach($events as &$event) {
            $event['title'] = Helper::titleize($event['title']);
            //Adding keywords for the events
            list($event['description'], $event['keywords']) = Helper::labelize($keywords, Helper::summarize($event['description'], 1000));
        }
        
        return $events;
    }

    /**
     * Given a string, this will format the string into a capitalized title
     * 
     * @param type $title
     * @return type 
     */
    static public function titleize($title){
        // Break up sentence into individual words
        $words = explode(" ", $title);
        
        foreach($words as &$w) {
            // set to lowercase
            $val = strtolower($w);
            
            // Capitalize first letter
            $w = strtoupper(substr($val, 0, 1)) . substr($val, 1);
        }
        
        // Return string glued back together
        return implode(" ", $words);
    }
    
    /**
     * Given a long description, this function will truncate the string by 
     * $limit amount.
     * 
     * @param type $desc text of description
     * @param type $limit truncation size
     * @return type string of truncated description
     */
    static public function summarize($desc, $limit = 150) {
        return Helper::myTruncate($desc, $limit);
    }
    
    
    /**
     * Given a valid city name, this will return the geolocation of the city
     * using the Google Maps API
     * 
     * @param type $city name of city
     * @return type associative array containing geolocation 'lat', 'lon' or null
     */
    static public function geolocate($city) {
        $CI =& get_instance();
        
        $city_url = urlencode($city);
        $data = $CI->curl->simple_get("http://maps.googleapis.com/maps/api/geocode/json?address=$city_url&sensor=false");
        $json_results = json_decode($data);
        
        if(isset($json_results->results[0]->geometry->location)) {
            $loc = $json_results->results[0]->geometry->location;
            return array('lon' => $loc->lng, 'lat' => $loc->lat);
        }
        
        return null;
    }
    
    
    static public function citylocate($long, $lat) {
        $CI =& get_instance();
        
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
        $data = $CI->curl->simple_get("http://maps.googleapis.com/maps/api/geocode/json?address=$city_url&sensor=false");
        $json_results = json_decode($data);
        
        var_dump($json_results);
        // TODO: complete the return of this function

    }
    
    // Original PHP code by Chirp Internet: www.chirp.com.au 
    // Please acknowledge use of this code by including this header. 
    
    private static function myTruncate($string, $limit, $break=".", $pad="...") { 
        // return with no change if string is shorter than $limit 
        if(strlen($string) <= $limit) return $string; 
        
        // is $break present between $limit and the end of the string? 
        if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
            if($breakpoint < strlen($string) - 1) { 
                $string = substr($string, 0, $breakpoint) . $pad; 
            }
        }
        
        return $string; 
    }

}
