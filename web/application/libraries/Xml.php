<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
 * XML library for CodeIgniter
 *
 *    author: Woody Gilk
 * copyright: (c) 2006
 *   license: http://creativecommons.org/licenses/by-sa/2.5/
 *      file: libraries/Xml.php
 */

class Xml {
   function Xml () {
   }

   private $document;

   public function load_file ($file) {
      /***
       * @public
       * Load an file for parsing
       */
      $bad  = array('|//+|', '|\.\./|');
      $good = array('/', '');
      $file = APPPATH.preg_replace ($bad, $good, $file).'.xml';

      if (! file_exists ($file)) {
         return false;
      }

      return $this->load(file_get_contents($file));
   }  /* END load */

   public function load($xml) {
      if(empty($xml)) {
         return false;
      }

      $this->document = utf8_encode($xml);

      return TRUE;
   }

   public function parse() {

      $xml = $this->document;
      if ($xml == '') return false;

      $doc = new DOMDocument ();
      $doc->preserveWhiteSpace = false;
      if ($doc->loadXML($xml)) {
         $array = $this->flatten_node($doc);
         if (count ($array) > 0) return $array;
      }
      return false;

   }

   private function get_attrs($child, $value) {

      if ($child->hasAttributes()) {
         $attrs = array();

         foreach ($child->attributes as $attribute) {
            $attrs[$attribute->name] = $attribute->value;
         }
         return array('__value' => $value, '__attrs' => $attrs);
      } 
      return $value;                

   }

   private function flatten_node($node) {

      $array = array();

      foreach ($node->childNodes as $child) {

         if ($child->hasChildNodes()) {
            $array[$child->nodeName][] = $this->get_attrs($child, $this->flatten_node($child));

         } elseif ($child->nodeValue == '') {
            $array[$child->nodeName][] = $this->get_attrs($child, '');

         } else {
            return $child->nodeValue;
         }
      }
      return $array;

   }
}

?>
