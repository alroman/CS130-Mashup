<?php

class Helper {
    
    
    static public function fixTitle($title){
        // Break up sentence into individual words
        $words = explode(" ", $title);
        
        foreach($words as &$w) {
            // set to lowercase
            $val = strtolower($w);
            
            // Capitalize first letter
            $w = strtoupper(substr($val, 0, 1)) . substr($val, 1);
        }
        
        // Return string glued back together
        return implode(" ", $tokens);
    }
    
    static public function summarize($desc, $limit) {
        return $this->myTruncate($desc, $limit);
    }
    
    
    // Original PHP code by Chirp Internet: www.chirp.com.au 
    // Please acknowledge use of this code by including this header. 
    
    private function myTruncate($string, $limit, $break=".", $pad="...") { 
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