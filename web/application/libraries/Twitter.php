<?php

Class Twitter {

    public function __construct() {
        
    }

    public function searchResults( $event = null ) {
        
        $title = $event->title;
        $venue = $event->venue_name;
        
        $q = $venue . ' "' .$title. '" OR "' .$title. ' :) "';
        echo "Search Query: ". $q;
        $q = str_replace(" ", "%20", $q);
        $url = "http://search.twitter.com/search.atom?q=" . urlencode( $q ) . "&lang=en&rpp=100";
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        $result = curl_exec( $curl );
        curl_close( $curl );
        $return = new SimpleXMLElement( $result );
        return $return;
    }
}
?>
