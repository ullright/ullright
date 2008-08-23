<?php

/**
 * Subclass for representing a row from the 'ull_user' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllUser extends BaseUllUser
{
  
  public function __toString() {
    
    return $this->getFirstName() . ' ' . $this->getLastName();
    
  }
  
  public function getNameLastFirst() {
    
    return $this->getLastName() . ' ' . $this->getFirstName();
    
  }
  
  public function getUserLocation() {
    
    $location = $this->getLocation();
    return $location->getName();
//    echo '<pre>',print_r($this),'<pre>';
//    $location_id = $this->getLocationId();
//    echo "<h1>$location_id</h1>";
//    return 'n00b4';
    
  }
  
  public function getShortName() {
    
    $return = '';
    
    $first_name = substr($this->getFirstName(), 0, 1);
    
    if ($first_name) {
      $return .= $first_name . '.';
    }
    
    $last_name = substr($this->getLastName(), 0, 7);
    if (strlen($this->getLastName()) > 7) {
       $last_name .= '.';
    }
    
    $return .= $last_name;
    
    return $return;
  }  
  
}
